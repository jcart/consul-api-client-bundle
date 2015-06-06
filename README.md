Overview
--------

This bundle is a bridge between Symfony2 configuration and [consul-php-sdk](https://github.com/sensiolabs/consul-php-sdk) library.

Installation
------------

Download the dependency via composer

```{r, engine='bash', count_lines}
composer require jcart/consul-api-client-bundle
```

Install the bundle into your AppKernel. Add the following line to the bundle defintions:

```php
new JC\ConsulApiClientBundle\JCConsulApiClientBundle(),
```

Configuration
-------------

The configuration supports a list of consul clients.

```
jc_consul_api_client:
    clients:
        primary:
            host: consul.dev
            port: 8500
            logger: logger
            secret: your_secret_consul_key
```

Usage
-----

For each client configuration defined, the bundle will register the container the following services for each client

```
jc_consul_api_client.kv.%s
jc_consul_api_client.agent.%s
jc_consul_api_client.health.%s
jc_consul_api_client.catalog.%s
jc_consul_api_client.kv.%s
jc_consul_api_client.session.%s
```
