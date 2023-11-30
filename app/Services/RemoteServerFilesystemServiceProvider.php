<?php
namespace App\Services;

use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;

class RemoteServerFilesystemServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \Storage::extend('remote_server', function ($app, $config) {
            return new Filesystem(new RemoteServerAdapter($config));
        });
    }
}
