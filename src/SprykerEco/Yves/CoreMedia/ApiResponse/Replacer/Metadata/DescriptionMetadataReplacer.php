<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\Metadata;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;

class DescriptionMetadataReplacer extends AbstractMetadataReplacer
{
    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return string
     */
    public function replaceMetatag(CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer): string
    {
        if ($coreMediaPlaceholderTransfer->getDescription() !== null) {
            return sprintf($this->config->getMetaTagFormat(), CoreMediaPlaceholderTransfer::DESCRIPTION, $coreMediaPlaceholderTransfer->getDescription());
        }

        return '';
    }
}
