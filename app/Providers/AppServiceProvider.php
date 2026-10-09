<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
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
                // Isso diz para o pacote: "Use este ID longo como a raiz padrão"
                $options['rootFolderId'] = $config['folderId'];
            }
            
            // Instancia o adaptador passando as opções no terceiro parâmetro
            $adapter = new \Masbug\Flysystem\GoogleDriveAdapter(
                $service, 
                '/backups_polo', // O caminho virtual inicial sempre começa em '/' dentro da pasta configurada
                $options
            );
            
            // // CORREÇÃO AQUI: Note a barra invertida extra entre Flysystem e GoogleDrive
            // $adapter = new \Masbug\Flysystem\GoogleDriveAdapter(
            //     $service, 
            //     $config['folderId'] ?? '/'
            // );

            return new \Illuminate\Filesystem\FilesystemAdapter(
                new \League\Flysystem\Filesystem($adapter),
                $adapter,
                $config
            );
        });
    }
}