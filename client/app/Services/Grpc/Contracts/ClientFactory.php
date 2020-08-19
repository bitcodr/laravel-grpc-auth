<?php

namespace App\Services\Grpc\Contracts;

interface ClientFactory
{
    /**
     * Make grpc client
     *
     * @param string $client
     * @return  mixed
     */
    public function make(string $client);
}
