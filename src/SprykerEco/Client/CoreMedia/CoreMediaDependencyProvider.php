<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia;

use GuzzleHttp\Client;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientBridge;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientBridge;
use SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleBridge;
use SprykerEco\Client\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceBridge;

class CoreMediaDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_GUZZLE = 'CLIENT_GUZZLE';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const CLIENT_CATEGORY_STORAGE = 'CLIENT_CATEGORY_STORAGE';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addGuzzleClient($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addCategoryStorageClient($container);

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
            return new CoreMediaToGuzzleBridge(new Client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new CoreMediaToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_STORAGE, function (Container $container) {
            return new CoreMediaToProductStorageClientBridge(
                $container->getLocator()->productStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCategoryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_CATEGORY_STORAGE, function (Container $container) {
            return new CoreMediaToCategoryStorageClientBridge(
                $container->getLocator()->categoryStorage()->client()
            );
        });

        return $container;
    }
}