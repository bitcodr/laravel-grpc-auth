<?php

namespace App\Services\Grpc;

use App\Services\Grpc\Contracts\ClientFactory;
use Grpc\ChannelCredentials;
use Illuminate\Contracts\Config\Repository as Config;

class ConfigurableClientFactory implements ClientFactory
{
    /**
     * Config repository.
     *
     * @var     Config
     */
    protected Config $config;

    /**
     * Create new instance.
     *
     * @var     Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Make grpc client
     *
     * @param string $client
     * @return  mixed
     */
    public function make(string $client)
    {
        $config = $this->config->get("grpc.services.{$client}");

        $authentication = strtoupper($config['authentication']);
        $authenticationMethod = "create{$authentication}Credentials";

        $credentials = $this->{$authenticationMethod}($config);

        $client = new $client($config['host'], [
            'credentials' => $credentials
        ]);

        return $client;
    }

    /**
     * Create tls creadentials
     *
     * @param   array   $config
     *
     * @return  ChannelCredentials
     */
    protected function createTlsCredentials(array $config)
    {
        $cert = file_get_contents($config['cert']);

        return ChannelCredentials::createSsl($cert);
    }

    /**
     * Create insecure creadentials
     *
     * @param   array   $config
     *
     * @return  ChannelCredentials
     */
    protected function createInsecureCredentials(array $config)
    {
        return ChannelCredentials::createInsecure();
    }
}
