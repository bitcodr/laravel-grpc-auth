<?php namespace Tests\Unit;

use App\Grpc\Interfaces\ServiceInvoker;
use App\Grpc\Kernel;
use App\Grpc\Interfaces\Kernel as kernelInterface;
use App\Grpc\LaravelServiceInvoker;
use ProtocolBuffer\Auth\AuthServiceInterface;
use Tests\TestCase;

class GrpcKernelTest extends TestCase
{
    protected $kernel;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(
            kernelInterface::class,
            Kernel::class
        );

        $this->app->singleton(
            ServiceInvoker::class,
            LaravelServiceInvoker::class
        );

        $this->kernel = $this->app->make(Kernel::class);
    }

    public function testItCanRegisterService()
    {
        $this->kernel->registerService(AuthServiceInterface::class);

        $this->assertTrue(true);
    }
}
