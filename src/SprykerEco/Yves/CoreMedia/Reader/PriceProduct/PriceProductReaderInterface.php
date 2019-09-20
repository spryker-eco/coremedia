<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\Reader\PriceProduct;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;

interface PriceProductReaderInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer|null
     */
    public function findCurrentAbstractProductPrice(int $idProductAbstract): ?CurrentProductPriceTransfer;

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer|null
     */
    public function findCurrentConcreteProductPrice(
        int $idProductConcrete,
        int $idProductAbstract
    ): ?CurrentProductPriceTransfer;
}
