<?php   namespace App\Service\Grpc\Interfaces;

interface ErrorHandler
{

    /**
     * @param $status
     * @param null $codeToSend
     * @return mixed
     */
    public function handle($status, $codeToSend = null);
}
