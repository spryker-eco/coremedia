<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Coremedia;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CoremediaConfig extends AbstractBundleConfig
{
    public const PREG_MATCH_PLACEHOLDER_KEY = 'placeholder';

    /**
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->get(CoremediaConstants::ENABLE_DEBUG, false);
    }
}
