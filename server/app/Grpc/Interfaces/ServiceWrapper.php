<?php   namespace App\Grpc\Interfaces;

use Spiral\GRPC\ContextInterface;
use Spiral\GRPC\Exception\InvokeException;
use Spiral\GRPC\Exception\NotFoundException;

interface ServiceWrapper
{
    /**
     * Retrive service name.
     * 
     * @return  string
     */
    public function getName(): string;

    /**
     * Retrieve public methods.
     * 
     * @return  array
     */
    public function getMethods(): array;

    /**
     * Invoke service.
     * 
     * @param string                        $method
     * @param ContextInterface $context
     * @param string                        $input
     * 
     * @return string
     *
     * @throws NotFoundException
     * @throws InvokeException
     */
    public function invoke(string $method, ContextInterface $context, ?string $input): string;
}