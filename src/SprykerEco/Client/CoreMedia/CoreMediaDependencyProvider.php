<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\CoreMedia;

use GuzzleHttp\Client;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleBridge;

class CoreMediaDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_GUZZLE = 'CLIENT_GUZZLE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addGuzzleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addGuzzleClient(Container $container): Container
    {
        $container->set(static::CLIENT_GUZZLE, function () {
            return new CoreMediaToGuzzleBridge($this->createGuzzleHttpClient());
        });

        return $container;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function createGuzzleHttpClient(): Client
    {
        return new Client();
    }
}
