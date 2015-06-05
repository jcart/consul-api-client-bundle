<?php

namespace JC\ConsulApiClientBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class JCConsulApiClientExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['clients'] as $id => $client) {
            $httpClientId = sprintf('jc_consul_api_client.http_client.%s', $id);
            $httpClientReference = new Reference($httpClientId);

            $factoryId = sprintf('jc_consul_api_client.service_factory.%s', $id);
            $factoryRefence = new Reference($factoryId);

            $baseUrl = sprintf('%s://%s:%s', $client['secure'] ? 'https' : 'http', $client['host'], $client['port']);

            $container->register($httpClientId, 'GuzzleHttp\Client')
                ->addArgument(['base_url' => $baseUrl]);

            $container->register($factoryId, 'SensioLabs\Consul\ServiceFactory')
                ->addArgument([])
                ->addArgument(isset($client['logger']) ? new Reference($client['logger']) : null)
                ->addArgument($httpClientReference);

            $container->register(sprintf('jc_consul_api_client.kv.%s', $id), 'SensioLabs\Consul\Services\KV')
                ->setFactory(array($factoryRefence, 'get'))
                ->addArgument('kv');

            $container->register(sprintf('jc_consul_api_client.agent.%s', $id), 'SensioLabs\Consul\Services\Agent')
                ->setFactory(array($factoryRefence, 'get'))
                ->addArgument('agent');                

            $container->register(sprintf('jc_consul_api_client.health.%s', $id), 'SensioLabs\Consul\Services\Health')
                ->setFactory(array($factoryRefence, 'get'))
                ->addArgument('health');

            $container->register(sprintf('jc_consul_api_client.catalog.%s', $id), 'SensioLabs\Consul\Services\Catalog')
                ->setFactory(array($factoryRefence, 'get'))
                ->addArgument('catalog');

            $container->register(sprintf('jc_consul_api_client.sesion.%s', $id), 'SensioLabs\Consul\Services\Session')
                ->setFactory(array($factoryRefence, 'get'))
                ->addArgument('session');                      
        }
    }
}