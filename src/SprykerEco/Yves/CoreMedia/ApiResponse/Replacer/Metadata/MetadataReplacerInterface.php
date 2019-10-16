<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\ApiResponse\Replacer\Metadata;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;

interface MetadataReplacerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return string
     */
    public function replaceMetaTag(CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer): string;
}
