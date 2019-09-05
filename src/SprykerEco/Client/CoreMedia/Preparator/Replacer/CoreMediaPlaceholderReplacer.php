<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Preparator\Replacer;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;

class CoreMediaPlaceholderReplacer implements CoreMediaPlaceholderReplacerInterface
{
    /**
     * @param string $content
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return string
     */
    public function replace(string $content, CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer): string
    {
        if ($coreMediaPlaceholderTransfer->getPlaceholderReplacement() === null) {
            return $content;
        }

        return str_replace(
            $coreMediaPlaceholderTransfer->getPlaceholderBody(),
            $coreMediaPlaceholderTransfer->getPlaceholderReplacement(),
            $content
        );
    }
}
