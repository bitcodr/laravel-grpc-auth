<?php   namespace App\Grpc\Interfaces;

use Throwable;

interface Validator
{
    /**
     * Validate data.
     * 
     * @param   array   $data
     * @param   array   $rules
     * 
     * @return  void
     * 
     * @throws  Throwable
     */
    public function validate(array $data, array $rules): void;
}