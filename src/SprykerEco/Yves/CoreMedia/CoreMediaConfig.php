<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CoreMedia;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\CoreMedia\CoreMediaConfig as SharedCoreMediaConfig;

/**
 * @method \SprykerEco\Shared\CoreMedia\CoreMediaConfig getSharedConfig()
 */
class CoreMediaConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->getSharedConfig()->isDebugModeEnabled();
    }

    /**
     * @return string
     */
    public function getPlaceholderPattern(): string
    {
        return '/(?:(?:&lt;|<)!--CM\s*)(?P<' . SharedCoreMediaConfig::PREG_MATCH_PLACEHOLDER_KEY . '>(?:(?!CM--(&gt;|>)).|\s)*)(?:\s*\CM--(?:&gt;|>))/i';
    }

    /**
     * @return string
     */
    public function getMetaTagFormat(): string
    {
        return '<meta name="%s" content="%s">';
    }
}
