<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\CoreMedia\CoreMediaConfig as SharedCoreMediaConfig;
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
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->get(CoreMediaConstants::ENABLE_DEBUG, false);
    }

    /**
     * @return string
     */
    public function getFragmentBasePath(): string
    {
        return static::FRAGMENT_BASE_PATH;
    }

    /**
     * [
     *     'ApplicationStoreName1' => 'CoreMediaStoreName1',
     *     ...
     * ]
     *
     * @return string[]
     */
    public function getApplicationStoreMapping(): array
    {
        return [];
    }

    /**
     * [
     *     'CoreMediaStoreName1` => ['en_US' => 'en-GB', 'de_DE' => 'de-DE'],
     *     ...
     * ]
     *
     * @return string[][]
     */
    public function getApplicationStoreLocaleMapping(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getPlaceholderPattern(): string
    {
        return '/(?:(?:&lt;|<)!--CM\s*)(?P<' . SharedCoreMediaConfig::PREG_MATCH_PLACEHOLDER_KEY . '>(?:(?!CM--(&gt;|>)).|\s)*)(?:\s*\CM--(?:&gt;|>))/i';
    }
}
