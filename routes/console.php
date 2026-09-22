<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Schema;
use App\Models\Backup;

// Verifica se a tabela já existe para evitar erros ao rodar migrations do zero
if (Schema::hasTable('backups')){
    // Busca todas as conexões cadastradas pelos usuários
    $backups = Backup::all();

    foreach ($backups as $backup){
        // Agenda o comando criado passando o ID do banco e a expressão CRON salva pelo usuário
        Schedule::command("db:run-backup {$backup->id}")
            ->cron($backup->cron)
            ->withoutOverlapping() // Segurança crucial para dumps pesados
            ->appendOutputTo(storage_path('logs/backup-schedule.log')); // Guarda logs do resultado
    }
}

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');