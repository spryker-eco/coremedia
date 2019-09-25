<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
