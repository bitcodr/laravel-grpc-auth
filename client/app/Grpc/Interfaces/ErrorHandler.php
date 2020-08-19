<?php   namespace App\Grpc\Interfaces;

interface ErrorHandler
{

    /**
     * @param $status
     * @param null $codeToSend
     * @return mixed
     */
    public function handle($status, $codeToSend = null);
}
