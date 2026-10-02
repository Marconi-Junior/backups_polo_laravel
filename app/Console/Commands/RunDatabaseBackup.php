<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Backup;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BackupStatusNotification;

class RunDatabaseBackup extends Command
{
    protected $signature = 'db:run-backup {id}';
    protected $description = 'Executa o dump do banco de dados conectando de forma remota via rede interna';

    public function handle() {

        $id = $this->argument('id');
        $backup = Backup::find($id);

        if (!$backup){
            $this->error("Configuração de backup #{$id} não encontrada");
            return Command::FAILURE;
        }

        //Define o e-mail do administrador que receberá os alertas
        $emailAdmin = config('mail.from.address', 'seu-email@dominio.com');

        $filename = "backup-{$backup->banco}-" . now()->format('Y-m-d-H-i-s') . ".sql";
        
        // CORREÇÃO: Define o caminho absoluto da pasta backups
        $directoryPath = storage_path("app/backups");
        $targetPath = "{$directoryPath}/{$filename}";

        // CORREÇÃO: Cria o diretório físico usando PHP nativo com permissões de escrita corretas
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }

        // Localiza o container de banco 'db' na rede interna do Docker Compose
        $host = ($backup->ip === '127.0.0.1' || $backup->ip === 'localhost') ? 'db' : $backup->ip;

        // Monta o comando básico do pg_dump redirecionando a saída para o arquivo
        $command = sprintf(
            'pg_dump -h %s -p %s -U %s %s > %s',
            escapeshellarg($host),
            escapeshellarg($backup->porta),
            escapeshellarg($backup->usuario),            
            escapeshellarg($backup->banco),
            escapeshellarg($targetPath)
        );

        $this->info("Iniciando dump do banco de dados: {$backup->banco}...");
        
        //Envolve todo processo crítido (geração, upload e e-mail)
        try{
            // Injeta a senha usando a sintaxe estável do Laravel
            $result = Process::env([
                'PGPASSWORD' => $backup->senha
            ])->run($command);

            // Valida se o processo foi bem-sucedido e se o arquivo realmente possui conteúdo
            if ($result->successful() && file_exists($targetPath) && filesize($targetPath) > 0){
                $this->info("Backup concluído com sucesso! Salvo como: backups/{$filename}");
                
                // Bloco de sucesso, envio do e-mail de confirmação
                Notification::route('mail', $emailAdmin)->notify(
                    new BackupStatusNotification(
                        'sucesso',
                        "O banco '{$backup->banco}' foi processado. Arquivo: {$filename} (Salvo localmente)"
                    )
                );
                return Command::SUCCESS;
            }
            // Exceção manual caso o pg_dump falhe ou gere arquivo vazio
            $erroOutput = $result->errorOutput() ?: "Arquivo gerado vazio ou corrompido";
            throw new \Exception("Erro no pg_dump: " . $erroOutput);
        }
        catch(\Exception $e)
        {        
            // O bloco catch captura qualquer falha
            $this->error("Falha no processo de backup: " . $e->getMessage());

            // Envia o e-mail de alerta de erro
            Notification::route('mail', $emailAdmin)->notify(
                new BackupStatusNotification('erro', "Falha ao processar o bando '{$backup->banco}' . Detalhes: " . $e->getMessage())
            );

            // Limpeza do arquivo local corrompido, se existir
            if (file_exists($targetPath)) {
                unlink($targetPath);
            }        
            return Command::FAILURE;
        }            
    }
}