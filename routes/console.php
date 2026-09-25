<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Schema;
use App\Models\Backup;

// Verifica se a tabela já existe para evitar erros ao rodar migrations do zero
if (Schema::hasTable('backups') && Schema::hasColumn('backups', 'frequencia')){
    // Busca todas as conexões cadastradas pelos usuários
    $backups = Backup::where('frequencia', '!=', 'nunca')->get();

    foreach ($backups as $backup){
        // Cria o agendamento do comando passando o ID
        $scheduledCommand = Schedule::command("db:run-backup {$backup->id}")
            //->cron($backup->cron)
            ->withoutOverlapping() // Segurança crucial para dumps pesados
            ->appendOutputTo(storage_path('logs/backup-schedule.log')); // Guarda logs do resultado

        // Vincula o método nativo do Laravel correspondente ao select
        switch($backup->frequencia){
            case 'trimestral':
                $scheduledCommand->cron('0 0 1 */3 *'); 
                break;

            case 'mensal':
                $scheduledCommand->monthly();
                break;

            case 'semanal':
                $scheduledCommand->weekly();
                break;

            case 'alternado':
                $scheduledCommand->cron('0 0 * * 1,3,5');
                break;
        }
    }
}

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');