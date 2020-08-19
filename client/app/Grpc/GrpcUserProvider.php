<?php   namespace App\Grpc;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use App\Grpc\Interfaces\ErrorHandler;
use App\Grpc\Interfaces\ClientFactory;
use ProtocolBuffer\Auth\SignInRequest;
use ProtocolBuffer\Auth\SignUpRequest;
use ProtocolBuffer\Auth\Response;
use ProtocolBuffer\Auth\AuthServiceInterface;

class GrpcUserProvider implements UserProvider
{

    protected ErrorHandler $errorHandler;


    protected $authServiceClient;


    public function __construct(ClientFactory $grpcClientFactory, ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;

        $this->authServiceClient = $grpcClientFactory->make(AuthServiceInterface::class);
    }


    public function retrieveByCredentials(array $credentials)
    {
        $request = new SignInRequest();

        $request->setEmail($credentials['email']);
        $request->setPassword($credentials['password']);

        [$response, $status] = $this->authServiceClient->Login($request)->wait();

        $this->errorHandler->handle($status, 3);

        $user = new User;

        $user->id = $response->getId();
        $user->email = $response->getEmail();
        $user->name = $response->getName();

        return $user;
    }

    public function retrieveByToken($identifier, $token)
    {
        // TODO: Implement retrieveByToken() method.
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // TODO: Implement validateCredentials() method.
    }

    public function retrieveById($identifier)
    {
        // TODO: Implement retrieveById() method.
    }
}
