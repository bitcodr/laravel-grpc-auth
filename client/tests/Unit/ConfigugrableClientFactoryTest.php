<?php

namespace Tests\Unit;

use App\Grpc\ConfigurableClientFactory;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;

class ConfigugrableClientFactoryTest extends TestCase
{
    /** @var MockInterface|Repository */
    protected $config;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = $this->mock(Repository::class);
    }

    public function testItIaAConfigRepository()
    {
        $configurableClientRepository = new ConfigurableClientFactory($this->config);

        $this->assertTrue($configurableClientRepository instanceof \App\Services\Grpc\Contracts\ClientFactory);
    }

    public function testItCanCreateInsecureConnection()
    {
        $this->config->shouldReceive('get')
                     ->with('grpc.services.Protobuf\\Identity\\AuthServiceClient')
                     ->once()
                     ->andReturn([
                         'host' => 'test-host',
                         'authentication' => 'insecure'
                     ]);

        $configurableClientRepository = new ConfigurableClientFactory($this->config);

        $client = $configurableClientRepository->make('Protobuf\\Identity\\AuthServiceClient');

        $this->assertSame($client->getTarget(), 'test-host');
    }

    public function testItCanCreateTlsConnection()
    {
        $this->config->shouldReceive('get')
                     ->with('grpc.services.Protobuf\\Identity\\AuthServiceClient')
                     ->once()
                     ->andReturn([
                         'host' => 'test-host',
                         'authentication' => 'tls',
                         'cert' => './tests/test.crt'
                     ]);

        $configurableClientRepository = new ConfigurableClientFactory($this->config);

        $client = $configurableClientRepository->make('Protobuf\\Identity\\AuthServiceClient');

        $this->assertSame($client->getTarget(), 'test-host');
    }
}
