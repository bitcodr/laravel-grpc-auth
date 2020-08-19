<?php

namespace App\Services\Grpc;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use App\Services\Grpc\Contracts\ErrorHandler;
use App\Services\Grpc\Contracts\ClientFactory;
use ProtocolBuffer\Auth\SignInRequest;
use ProtocolBuffer\Auth\SignUpRequest;
use ProtocolBuffer\Auth\Response;
use ProtocolBuffer\Auth\AuthServiceInterface;

class GrpcUserProvider implements UserProvider
{
    /**
     * Error handler.
     *
     * @var ErrorHandler
     */
    protected ErrorHandler $errorHandler;

    /**
     * Auth service client.
     *
     * @var mixed
     */
    protected $authServiceClient;

    /**
     * Create new instance.
     *
     * @param ClientFactory $grpcClientFactory
     * @param ErrorHandler $errorHandler
     */
    public function __construct(ClientFactory $grpcClientFactory, ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;

        $this->authServiceClient = $grpcClientFactory->make(AuthServiceInterface::class);
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param mixed $identifier
     * @param string $token
     * @return void
     */
    public function retrieveByToken($identifier, $token)
    {
        // Implemented later
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param Authenticatable $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Implemented later
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $request = new SignInRequest();

        $request->setEmail($credentials['email']);
        $request->setPassword($credentials['password']);

        [$response, $status] = $this->authServiceClient->Login($request)->wait();

        $this->errorHandler->handle($status, 3);

        return $this->generateAuthenticable($response);
    }



    /**
     * Validate a user against the given credentials.
     *
     * @param Authenticatable $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // Implemented later
    }

    /**
     * Generate authenticable.
     *
     *
     * @param Response $userResponse
     * @return  Authenticatable
     */
    protected function generateAuthenticable(Response $userResponse)
    {
        $user = new User;

        $user->id = $userResponse->getId();
        $user->email = $userResponse->getEmail();
        $user->name = $userResponse->getName();

        return $user;
    }
}
