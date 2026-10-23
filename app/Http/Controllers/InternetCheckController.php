<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use App\Notifications\InternetStatusNotification;

class InternetCheckController extends Controller
{
    public function index(){
        return view('internet.index');
    }

    public function testConnection(){
    // //Para o caso de um campo dinâmico vindo da view
    // public function testConnection(Request $request) {
    //     $host = $request->input('host', 'google.com');

        $host = 'polodeinovacao.ifce.edu.br';
        $port = 443; // Porta HTTPS padrão
        $timeout = 2; // Limite de 2 segundos para o ping
        
        $startTime = microtime(true);
        
        // Abre uma ligação direta de socket (ignora o overhead do cURL/HTTP)
        $socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
        
        if ($socket) {
            $online = true;
            fclose($socket); // Fecha a ligação imediatamente
        } else {
            $online = false;
        }

        $endTime = microtime(true);
        $latency = round(($endTime - $startTime) * 1000);

        $results = [
            'online' => $online,
            'latency' => $online ? $latency : 0,
            'host' => $host,
            'checked_at' => now()->format('d/m/Y H:i:s')
        ];

        session(['internet_test_result' => $results]);

        return redirect()->route('internet.index')->with('success', 'Teste de conexão concluído!');
    }

    public function sendEmailReport(Request $request) {
        $result = session('internet_test_result');

        if (!$result) {
            return redirect()->route('internet.index')->with('error', 'Por favor, execute o teste de conexão antes de enviar o relatório.');
        }

        // Envia a notificação para o e-mail configurado no ficheiro .env (MAIL_FROM_ADDRESS)
        Notification::route('mail', config('mail.from.address'))
            ->notify(new InternetStatusNotification($result));

        return redirect()->route('internet.index')->with('success', 'Relatório enviado com sucesso por E-mail!');
    }

    public function sendTelegramReport(Request $request) {
        
        $result = session('internet_test_result');

        if (!$result) {
            return redirect()->route('internet.index')->with('error', 'Por favor, execute o teste de conexão antes de enviar o relatório.');
        }

        // 1. Monta a mensagem formatada em Markdown
        $statusEmoji = $result['online'] ? '✅ ONLINE' : '❌ OFFLINE';
        
        $message = "*Relatório de Estado da Internet*\n\n";
        $message .= "Status: {$statusEmoji}\n";
        $message .= "Host: `{$result['host']}`\n";
        $message .= "Latência: *{$result['latency']} ms*\n";
        $message .= "Verificado em: {$result['checked_at']}";

        // 2. Recupera as credenciais do .env
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        try {
            // 3. Dispara a requisição para a API do Telegram
            $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown', // Permite negrito e formatação de código
            ]);

            if ($response->successful()) {
                return redirect()->route('internet.index')->with('success', 'Relatório enviado com sucesso para o Telegram!');
            }

            return redirect()->route('internet.index')->with('error', 'Falha ao enviar para o Telegram. Verifique as credenciais.');

        } catch (\Exception $e) {
            return redirect()->route('internet.index')->with('error', 'Erro de conexão com a API do Telegram: ' . $e->getMessage());
        }
    }
}
