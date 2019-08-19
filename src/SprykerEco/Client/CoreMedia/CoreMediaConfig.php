<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\CoreMedia\CoreMediaConstants;

class CoreMediaConfig extends AbstractBundleConfig
{
    protected const FRAGMENT_BASE_PATH = '/blueprint/servlet/service/fragment/';

    /**
     * @return string
     */
    public function getCoreMediaHost(): string
    {
        return $this->get(CoreMediaConstants::CORE_MEDIA_HOST, null);
    }

    /**
     * @return string
     */
    public function getFragmentBasePath(): string
    {
        return static::FRAGMENT_BASE_PATH;
    }

    /**
     * @return string[]
     */
    public function getApplicationStoreMapping(): array
    {
        return [];
    }

    /**
     * @return string[][]
     */
    public function getApplicationStoreLocaleMapping(): array
    {
        return [];
    }
}
