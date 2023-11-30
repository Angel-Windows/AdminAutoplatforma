<?php
namespace App\Services;

use League\Flysystem\Filesystem;
use AbstractAdapter;
use GuzzleHttp\Client;

class RemoteServerAdapter extends AbstractAdapter
{
    protected $client;

    public function __construct(array $config)
    {
        $this->client = new Client([
            'base_uri' => $config['url'], // URL вашего удаленного сервера
        ]);
    }

    // Методы для работы с удаленным сервером, например, write(), put(), etc.
}
