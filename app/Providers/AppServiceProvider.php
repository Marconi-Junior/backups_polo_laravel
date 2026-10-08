<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Masbug\FlysystemGoogleDrive\GoogleDriveAdapter;

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
            
            // Autenticação estática local via JSON - sem chamadas de rede externas para login
            $client->setAuthConfig(base_path('google-service-account.json'));
            $client->addScope(\Google\Service\Drive::DRIVE);

            $service = new \Google\Service\Drive($client);
            
            // Usamos o adaptador padrão de arquivos locais do Laravel adaptado para a API do Google
            // Isso elimina para sempre a necessidade do pacote "masbug" ou "yaza"
            $adapter = new \League\Flysystem\Local\LocalFilesystemAdapter(storage_path('app/backups'));

            // Criamos uma classe anônima para interceptar o método put e mandar direto pro Google Drive
            return new class($service, $config['folderId']) extends \Illuminate\Filesystem\FilesystemAdapter {
                protected $drive;
                protected $folderId;

                public function __construct($service, $folderId) {
                    $this->drive = $service;
                    $this->folderId = $folderId;
                }

                public function put($path, $contents, $options = []): bool {
                    try {
                        $fileMetadata = new \Google\Service\Drive\DriveFile([
                            'name' => basename($path),
                            'parents' => [$this->folderId]
                        ]);
                        $this->drive->files->create($fileMetadata, [
                            'data' => $contents,
                            'mimeType' => 'application/sql',
                            'uploadType' => 'multipart'
                        ]);
                        return true;
                    } catch (\Exception $e) {
                        return false;
                    }
                }
            };
        });
    }
}