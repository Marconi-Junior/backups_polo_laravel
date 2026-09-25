<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Backup;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class RunDatabaseBackup extends Command
{
    protected $signature = 'db:run-backup {id}';
    protected $description = 'Executa o dump do banco de dados conectando de forma remota via rede interna';

    public function handle()
{
    $id = $this->argument('id');
    $backup = Backup::find($id);

    if (!$backup){
        $this->error("Configuração de backup #{$id} não encontrada");
        return Command::FAILURE;
    }

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
    
    // Injeta a senha usando a sintaxe estável do Laravel
    $result = Process::env([
        'PGPASSWORD' => $backup->senha
    ])->run($command);

    // Valida se o processo foi bem-sucedido e se o arquivo realmente possui conteúdo
    if ($result->successful() && file_exists($targetPath) && filesize($targetPath) > 0){
        $this->info("Backup concluído com sucesso! Salvo como: backups/{$filename}");
        return Command::SUCCESS;
    }

    $this->error("Erro ao gerar o banco ou arquivo gerado vazio.");
    if ($result->errorOutput()) {
        $this->error("Detalhes do erro: " . $result->errorOutput());
    }
    
    if (file_exists($targetPath)) {
        unlink($targetPath);
    }
    
    return Command::FAILURE;
}


}