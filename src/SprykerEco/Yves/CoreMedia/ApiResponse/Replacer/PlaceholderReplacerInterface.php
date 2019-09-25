<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
