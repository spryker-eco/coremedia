<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Shared\CoreMedia;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CoreMediaConfig extends AbstractBundleConfig
{
    public const PREG_MATCH_PLACEHOLDER_KEY = 'placeholder';

    /**
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->get(CoreMediaConstants::ENABLE_DEBUG, false);
    }
}
