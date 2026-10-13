<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\Storage::extend('google', function ($app, $config) {
            $client = new \Google\Client();
            
            // Força o uso de IPv4 resolvendo o problema crônico do WSL2
            $guzzleClient = new \GuzzleHttp\Client([
                \GuzzleHttp\RequestOptions::CONNECT_TIMEOUT => 20,
                \GuzzleHttp\RequestOptions::TIMEOUT => 60,
                'curl' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4
                ]
            ]);
            $client->setHttpClient($guzzleClient);

            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);
            
            $service = new \Google\Service\Drive($client);
            
            // Prepara as opções do adaptador
            $options = [];
            if (!empty($config['folderId'])) {                
                $options['rootFolderId'] = $config['folderId']; // Isso diz para o pacote usar este ID longo como a raiz padrão"
            }
            
            // Instancia o adaptador passando as opções no terceiro parâmetro
            $adapter = new \Masbug\Flysystem\GoogleDriveAdapter(
                $service, 
                $config ['folder'] ?? '/',
                $options
            );
                        
            return new \Illuminate\Filesystem\FilesystemAdapter(
                new \League\Flysystem\Filesystem($adapter),
                $adapter,
                $config
            );
        });
    }
}