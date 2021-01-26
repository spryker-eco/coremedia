<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Coremedia;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Coremedia\CoremediaConstants;

/**
 * @method \SprykerEco\Shared\Coremedia\CoremediaConfig getSharedConfig()
 */
class CoremediaConfig extends AbstractBundleConfig
{
    protected const FRAGMENT_BASE_PATH = '/blueprint/servlet/service/fragment/';

    /**
     * @api
     *
     * @return string
     */
    public function getCoremediaHost(): string
    {
        return $this->get(CoremediaConstants::COREMEDIA_HOST, null);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->getSharedConfig()->isDebugModeEnabled();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getFragmentBasePath(): string
    {
        return static::FRAGMENT_BASE_PATH;
    }

    /**
     * [
     *     'ApplicationStoreName1' => 'CoremediaStoreName1',
     *     ...
     * ]
     *
     * @api
     *
     * @return string[]
     */
    public function getApplicationStoreMapping(): array
    {
        return [];
    }

    /**
     * [
     *     'CoremediaStoreName1` => ['en_US' => 'en-GB', 'de_DE' => 'de-DE'],
     *     ...
     * ]
     *
     * @api
     *
     * @return string[][]
     */
    public function getApplicationStoreLocaleMapping(): array
    {
        return [];
    }
}
