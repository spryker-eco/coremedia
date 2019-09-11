<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Reader\PriceProduct;

use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface;

class PriceProductAbstractStorageReader implements PriceProductAbstractStorageReaderInterface
{
    /**
     * @var \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @var \Generated\Shared\Transfer\PriceProductTransfer[][]
     */
    protected static $priceProductAbstractDataCache = [];

    /**
     * @param \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface $priceProductStorageClient
     */
    public function __construct(CoreMediaToPriceProductStorageClientInterface $priceProductStorageClient)
    {
        $this->priceProductStorageClient = $priceProductStorageClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getPriceProductAbstractTransfers(int $idProductAbstract): array
    {
        if (isset(static::$priceProductAbstractDataCache[$idProductAbstract])) {
            return static::$priceProductAbstractDataCache[$idProductAbstract];
        }

        static::$priceProductAbstractDataCache[$idProductAbstract] = $this->priceProductStorageClient
            ->getPriceProductAbstractTransfers($idProductAbstract);

        return static::$priceProductAbstractDataCache[$idProductAbstract];
    }
}
