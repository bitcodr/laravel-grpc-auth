<?php

namespace Tests\Unit;

use App\Service\Grpc\Interfaces\ClientFactory;
use App\Service\Grpc\Interfaces\ErrorHandler;
use App\Providers\GrpcUserProvider;
use ProtocolBuffer\Auth\AuthServiceInterface;
use ProtocolBuffer\Auth\Response;
use Tests\TestCase;

class GrpcUserProviderTest extends TestCase
{
    protected $grpcClientFactory;

    protected $errorHandler;

    public function setUp(): void
    {
        parent::setUp();

        $this->errorHandler = $this->mock(ErrorHandler::class);
        $this->grpcClientFactory = $this->mock(ClientFactory::class);
    }

    public function testItIsUserProvider()
    {
        $this->grpcClientFactory->shouldReceive('make')->once();

        $grpcUserProvider = new GrpcUserProvider($this->grpcClientFactory, $this->errorHandler);

        $this->assertTrue($grpcUserProvider instanceof \Illuminate\Contracts\Auth\UserProvider);
    }

    public function testItCanFindUserById()
    {
        $authServiceClient = $this->mock(AuthServiceInterface::class);
        $baseStub = $this->mock(\Grpc\BaseStub::class); // It's a dummy class
        $userResponse = new Response();

        $userResponse->setId(1);
        $userResponse->setToken('token');

        $this->errorHandler->shouldReceive('handle');
        $this->grpcClientFactory->shouldReceive('make')->once()->andReturn($authServiceClient);

        $baseStub->shouldReceive('wait')->andReturn([
            $userResponse,
            [
                'code' => 0,
                'descriptions' => ''
            ]
        ]);

        $authServiceClient->shouldReceive('UserById')->andReturn($baseStub);

        $grpcUserProvider = new GrpcUserProvider($this->grpcClientFactory, $this->errorHandler);

        $user = $grpcUserProvider->retrieveByCredentials([
            "email" => "email@domain.com",
            "password" => "12345",
        ]);

        $this->assertTrue($user instanceof \Illuminate\Contracts\Auth\Authenticatable);
        $this->assertSame($user->id, 1);
    }

    public function testItCanFindUserByCredentials()
    {
        $authServiceClient = $this->mock(AuthServiceInterface::class);
        $baseStub = $this->mock(\Grpc\BaseStub::class); // It's a dummy class
        $userResponse = new Response();

        $userResponse->setId(1);
        $userResponse->setToken('token');

        $this->errorHandler->shouldReceive('handle');
        $this->grpcClientFactory->shouldReceive('make')->once()->andReturn($authServiceClient);

        $baseStub->shouldReceive('wait')->andReturn([
            $userResponse,
            [
                'code' => 0,
                'descriptions' => ''
            ]
        ]);

        $authServiceClient->shouldReceive('Login')->andReturn($baseStub);

        $grpcUserProvider = new GrpcUserProvider($this->grpcClientFactory, $this->errorHandler);

        $user = $grpcUserProvider->retrieveByCredentials([
            'email' => 'email@email.com',
            'password' => '123456'
        ]);

        $this->assertTrue($user instanceof \Illuminate\Contracts\Auth\Authenticatable);
        $this->assertSame($user->id, 1);
    }

    public function testItCanFindUserByEmail()
    {
        $authServiceClient = $this->mock(AuthServiceInterface::class);
        $baseStub = $this->mock(\Grpc\BaseStub::class); // It's a dummy class
        $userResponse = new Response();

        $userResponse->setId(1);
        $userResponse->setToken('token');

        $this->errorHandler->shouldReceive('handle');
        $this->grpcClientFactory->shouldReceive('make')->once()->andReturn($authServiceClient);

        $baseStub->shouldReceive('wait')->andReturn([
            $userResponse,
            [
                'code' => 0,
                'descriptions' => ''
            ]
        ]);

        $authServiceClient->shouldReceive('UserByEmail')->andReturn($baseStub);

        $grpcUserProvider = new \App\Providers\GrpcUserProvider($this->grpcClientFactory, $this->errorHandler);

        $user = $grpcUserProvider->retrieveByCredentials([
            'email' => 'email@email.com',
            'password' => '123456'
        ]);

        $this->assertTrue($user instanceof \Illuminate\Contracts\Auth\Authenticatable);
        $this->assertSame($user->id, 1);
    }
}
