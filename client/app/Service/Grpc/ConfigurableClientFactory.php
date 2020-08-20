<?php   namespace App\Service\Grpc;

use App\Service\Grpc\Interfaces\ClientFactory;
use Illuminate\Contracts\Config\Repository as Config;

class ConfigurableClientFactory implements ClientFactory
{

    /**
     * @var Config
     */
    protected Config $config;


    /**
     * ConfigurableClientFactory constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }


    /**
     * @param string $client
     * @return mixed
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
     * @param array $config
     * @return \Grpc\ChannelCredentials
     */
    protected function createTlsCredentials(array $config)
    {
        $cert = file_get_contents($config['cert']);

        return \Grpc\ChannelCredentials::createSsl($cert);
    }


    /**
     * @param array $config
     * @return \Grpc\ChannelCredentials
     */
    protected function createInsecureCredentials(array $config)
    {
        return \Grpc\ChannelCredentials::createInsecure();
    }
}
