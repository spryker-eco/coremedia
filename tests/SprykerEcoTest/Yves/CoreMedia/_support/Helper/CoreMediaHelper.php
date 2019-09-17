<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Yves\CoreMedia\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CategoryNodeStorageBuilder;
use Generated\Shared\DataBuilder\CoreMediaApiResponseBuilder;
use Generated\Shared\DataBuilder\CoreMediaFragmentRequestBuilder;
use Generated\Shared\DataBuilder\CurrencyBuilder;
use Generated\Shared\DataBuilder\CurrentProductPriceBuilder;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

class CoreMediaHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer
     */
    public function getCoreMediaFragmentRequestTransfer(array $seedData = []): CoreMediaFragmentRequestTransfer
    {
        return (new CoreMediaFragmentRequestBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function getCoreMediaApiResponseTransfer(array $seedData = []): CoreMediaApiResponseTransfer
    {
        return (new CoreMediaApiResponseBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeStorageTransfer(array $seedData = []): CategoryNodeStorageTransfer
    {
        return (new CategoryNodeStorageBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function getPriceProductTransfer(array $seedData = []): PriceProductTransfer
    {
        return (new PriceProductBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    public function getMoneyValueTransfer(array $seedData = []): MoneyValueTransfer
    {
        return (new MoneyValueBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function getCurrentProductPriceTransfer(array $seedData = []): CurrentProductPriceTransfer
    {
        return (new CurrentProductPriceBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrencyTransfer(array $seedData = []): CurrencyTransfer
    {
        return (new CurrencyBuilder($seedData))->build();
    }
}
