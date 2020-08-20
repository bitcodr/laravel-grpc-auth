<?php   namespace App\Service\Grpc\Interfaces;

interface ClientFactory
{
    /**
     * @param string $client
     * @return mixed
     */
    public function make(string $client);
}
