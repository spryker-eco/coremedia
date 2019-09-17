<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientBridge;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToMoneyClientBridge;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientBridge;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientBridge;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientBridge;
use SprykerEco\Yves\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceBridge;

class CoreMediaDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const CLIENT_CATEGORY_STORAGE = 'CLIENT_CATEGORY_STORAGE';
    public const CLIENT_PRICE_PRODUCT_STORAGE = 'CLIENT_PRICE_PRODUCT_STORAGE';
    public const CLIENT_PRICE_PRODUCT = 'CLIENT_PRICE_PRODUCT';
    public const CLIENT_MONEY = 'CLIENT_MONEY';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const URL_GENERATOR = 'URL_GENERATOR';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addUtilEncodingService($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addCategoryStorageClient($container);
        $container = $this->addPriceProductStorageClient($container);
        $container = $this->addPriceProductClient($container);
        $container = $this->addMoneyClient($container);
        $container = $this->addUrlGenerator($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
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
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
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
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
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

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPriceProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRICE_PRODUCT_STORAGE, function (Container $container) {
            return new CoreMediaToPriceProductStorageClientBridge(
                $container->getLocator()->priceProductStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPriceProductClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRICE_PRODUCT, function (Container $container) {
            return new CoreMediaToPriceProductClientBridge(
                $container->getLocator()->priceProduct()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMoneyClient(Container $container): Container
    {
        $container->set(static::CLIENT_MONEY, function (Container $container) {
            return new CoreMediaToMoneyClientBridge(
                $container->getLocator()->money()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUrlGenerator(Container $container): Container
    {
        $container->set(static::URL_GENERATOR, function () {
            return (new Pimple())->getApplication()['url_generator'];
        });

        return $container;
    }
}
