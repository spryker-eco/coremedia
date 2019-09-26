<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Api\Configuration;

use SprykerEco\Client\CoreMedia\Api\Exception\UrlConfigurationException;
use SprykerEco\Client\CoreMedia\CoreMediaConfig;

class UrlConfiguration implements UrlConfigurationInterface
{
    /**
     * @var \SprykerEco\Client\CoreMedia\CoreMediaConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Client\CoreMedia\CoreMediaConfig $config
     */
    public function __construct(CoreMediaConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @throws \SprykerEco\Client\CoreMedia\Api\Exception\UrlConfigurationException
     *
     * @return string
     */
    public function getCoreMediaHost(): string
    {
        $coreMediaHost = $this->config->getCoreMediaHost();

        if (!$coreMediaHost) {
            throw new UrlConfigurationException('Please specify the CoreMedia host in configuration.');
        }

        return $coreMediaHost;
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->config->getFragmentBasePath();
    }

    /**
     * @param string $storeName
     *
     * @throws \SprykerEco\Client\CoreMedia\Api\Exception\UrlConfigurationException
     *
     * @return string
     */
    public function getStore(string $storeName): string
    {
        $applicationStoreMapping = $this->config->getApplicationStoreMapping();

        if (!isset($applicationStoreMapping[$storeName])) {
            throw new UrlConfigurationException(
                sprintf('Cannot find storeId by store name "%s" in application store mapping.', $storeName)
            );
        }

        return $applicationStoreMapping[$storeName];
    }

    /**
     * @param string $store
     * @param string $localeName
     *
     * @throws \SprykerEco\Client\CoreMedia\Api\Exception\UrlConfigurationException
     *
     * @return string
     */
    public function getLocale(string $store, string $localeName): string
    {
        $applicationStoreLocaleMapping = $this->config->getApplicationStoreLocaleMapping();

        if (!isset($applicationStoreLocaleMapping[$store])) {
            throw new UrlConfigurationException(
                sprintf('Not defined storeId "%s" in application store locale mapping.', $store)
            );
        }

        if (!isset($applicationStoreLocaleMapping[$store][$localeName])) {
            throw new UrlConfigurationException(
                sprintf(
                    'Cannot find locale by locale name "%s" for storeId "%s" in application store locale mapping.',
                    $localeName,
                    $store
                )
            );
        }

        return $applicationStoreLocaleMapping[$store][$localeName];
    }
}
