<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BackupStatusNotification extends Notification
{
    use Queueable;

    private $status;
    private $mensagem;

    // Recebe o status ('sucesso' ou 'erro') e a mensagem descritiva do backup
    public function __construct($status, $mensagem)
    {
        $this->status = $status;
        $this->mensagem = $mensagem;
    }

    public function via($notifiable)
    {
        return ['mail']; // Define o canal de envio como e-mail
    }

    public function toMail($notifiable)
    {
        $assunto = $this->status === 'sucesso' 
            ? '✅ Backup Realizado com Sucesso!' 
            : '🚨 FALHA: Erro ao Realizar Backup';

        $textoPrincipal = $this->status === 'sucesso' 
            ? 'O backup agendado do banco de dados foi concluído sem problemas.' 
            : 'Atenção: Ocorreu um erro ao tentar gerar o backup agendado.';

        return (new MailMessage)
            ->subject($assunto)
            ->greeting('Olá!')
            ->line($textoPrincipal)
            ->line('Detalhes: ' . $this->mensagem)
            ->line('Obrigado por utilizar o nosso sistema de monitoramento!');
    }
}
