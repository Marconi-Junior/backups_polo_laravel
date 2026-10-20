<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InternetStatusNotification extends Notification
{
    use Queueable;

    protected array $data;

    public function __construct(array $data){
        $this->data = $data;
    }

    public function via($notifiable): array{
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage{
        
        $statusStr = $this->data['online'] ? 'ONLINE' : 'OFFLINE';
        
        return (new MailMessage)
            ->subject("Relatório de Conexão: Instância {$statusStr}")
            ->greeting('Olá, Administrador!')
            ->line('Foi executado um teste de conectividade manual a partir do painel.')
            ->line("Status da Conexão: **{$statusStr}**")
            ->line("Latência medida: **{$this->data['latency']} ms**")
            ->line("Host testado: **{$this->data['host']}**")
            ->line("Data/Hora: **{$this->data['checked_at']}**")
            ->action('Acessar o Painel', route('internet.index'))
            ->line('Polo de Inovação do IFCE.');
    }
}
