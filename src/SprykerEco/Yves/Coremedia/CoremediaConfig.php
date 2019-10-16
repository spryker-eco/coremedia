<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Coremedia\CoremediaConfig as SharedCoremediaConfig;

/**
 * @method \SprykerEco\Shared\Coremedia\CoremediaConfig getSharedConfig()
 */
class CoremediaConfig extends AbstractBundleConfig
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
        return '/(?:(?:&lt;|<)!--CM\s*)(?P<' . SharedCoremediaConfig::PREG_MATCH_PLACEHOLDER_KEY . '>(?:(?!CM--(&gt;|>)).|\s)*)(?:\s*\CM--(?:&gt;|>))/i';
    }

    /**
     * @return string
     */
    public function getMetaTagFormat(): string
    {
        return '<meta name="%s" content="%s">';
    }
}
