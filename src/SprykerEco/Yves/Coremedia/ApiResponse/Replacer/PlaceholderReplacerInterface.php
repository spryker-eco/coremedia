<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\ApiResponse\Replacer;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;

interface PlaceholderReplacerInterface
{
    /**
     * @param string $content
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return string
     */
    public function replace(string $content, CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer): string;
}
