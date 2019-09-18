<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\Metadata;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;

class KeywordsMetadataReplacer extends AbstractMetadataReplacer
{
    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return string
     */
    public function replaceMetaTag(CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer): string
    {
        if ($coreMediaPlaceholderTransfer->getKeywords() !== null) {
            return sprintf($this->config->getMetaTagFormat(), CoreMediaPlaceholderTransfer::KEYWORDS, $coreMediaPlaceholderTransfer->getKeywords());
        }

        return '';
    }
}
