<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Backup;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class RunDatabaseBackup extends Command
{
    protected $signature = 'db:run-backup {id}';
    protected $description = 'Executa o dump do banco de dados de uma conexão expecífica';

    public function handle()
    {
        $id = $this->argument('id');
        $backup = Backup::find($id);

        if (!$backup){
            $this->error("Configuração de backup #{$id} não encontrada");
            return Command::FAILURE;
        }

        $filename = "backup-{$backup->banco}-" . now()->format('Y-m-d-H-i-s') . ".sql";

        // Define o caminho temporário onde o arquivo será gerado no servidor
        $tempPath = storage_path("app/{$filename}");

        $host = ($backup->ip === '127.0.0.1' || $backup->id === 'localhost') ? 'db' : $backup-ip;

        // Monta o comando do mysqldump de forma segura
        $command = sprintf(
            'PGPASSWORD=%s pg_dump -h %s -p %s -U %s %s > %s',
            escapeshellarg($backup->senha),
            escapeshellarg($host),
            escapeshellarg($backup->porta),
            escapeshellarg($backup->usuario),            
            escapeshellarg($backup->banco),
            escapeshellarg($tempPath)
        );

        $this->info("iniciando dump do banco: {$backup->banco}...");

        // Executa o comando de terminal nativo
        $result = Process::run($command);

        if ($result->successful() && file_exist($tempPath) && filesize($tempPath) > 0){
            // Move o arquivo temporário para o disco do Laravel (Local, AWS S3, etc.)
            // Configurado por padrão em storage/app/private/backups (Laravel 11) ou storage/app/backups
            Storage::disk('local')->put("backups/{$filename}", file_get_contents($tempPath));
            unlink($tempPath); // Deleta o arquivo temporário da máquina

            $this->info("Backup concluído com sucesso! Salvo como: backup/{$filename}");
            return Command::SUCCESS;
        }

        $this->error("Erro ao gerar o banco: " . $result->errorOutput());
        if (file_exist($tempPath)) {
            unlink($tempPath);
        }
        return Command::FAILURE;
    }
}