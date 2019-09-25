<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CoreMedia\Formatter;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;

interface ProductPriceFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     *
     * @return string
     */
    public function getFormattedProductPrice(CurrentProductPriceTransfer $currentProductPriceTransfer): string;
}
