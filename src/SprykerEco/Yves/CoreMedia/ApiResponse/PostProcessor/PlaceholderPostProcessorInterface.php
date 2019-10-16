<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\ApiResponse\PostProcessor;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;

interface PlaceholderPostProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CoremediaPlaceholderTransfer
     */
    public function addReplacement(
        CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): CoremediaPlaceholderTransfer;
}
