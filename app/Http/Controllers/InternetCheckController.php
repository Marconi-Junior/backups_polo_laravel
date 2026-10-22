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
        $port = 443; // Porta HTTPS padrão web
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

        // $host = 'google.com';
        // $startTime = microtime(true);
        // $online = false;

        // try {
        //     // Reutiliza a estratégia do Guzzle/cURL que configurou no AppServiceProvider
        //     // Forçando IPv4 para compatibilidade total com o WSL2
        //     $response = Http::withHeaders([
        //         'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0'
        //     ])->withOptions([
        //         'curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4],
        //         'connect_timeout' => 5,
        //         'timeout' => 10
        //     ])->get("http://{$host}");

        //     if ($response->successful() || $response->status() >= 200 || $response->status() < 500) {
        //         $online = true;
        //     }
        // } catch (\Exception $e) {
        //     $online = false;
        // }

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
}
