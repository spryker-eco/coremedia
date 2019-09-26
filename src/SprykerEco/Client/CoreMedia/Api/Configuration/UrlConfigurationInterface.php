<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Api\Configuration;

interface UrlConfigurationInterface
{
    /**
     * @return string
     */
    public function getCoreMediaHost(): string;

    /**
     * @return string
     */
    public function getBasePath(): string;

    /**
     * @param string $storeName
     *
     * @return string
     */
    public function getStore(string $storeName): string;

    /**
     * @param string $store
     * @param string $localeName
     *
     * @return string
     */
    public function getLocale(string $store, string $localeName): string;
}
