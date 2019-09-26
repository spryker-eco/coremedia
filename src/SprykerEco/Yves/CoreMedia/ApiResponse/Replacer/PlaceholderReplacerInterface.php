<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse\Replacer;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;

interface PlaceholderReplacerInterface
{
    /**
     * @param string $content
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return string
     */
    public function replace(string $content, CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer): string;
}
