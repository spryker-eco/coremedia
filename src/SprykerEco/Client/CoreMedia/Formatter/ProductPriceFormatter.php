<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Formatter;

use Generated\Shared\Transfer\MoneyTransfer;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface;

class ProductPriceFormatter implements ProductPriceFormatterInterface
{
    /**
     * @var \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @var \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface
     */
    protected $moneyClient;

    /**
     * @param \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface $priceProductClient
     * @param \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface $moneyClient
     */
    public function __construct(
        CoreMediaToPriceProductClientInterface $priceProductClient,
        CoreMediaToMoneyClientInterface $moneyClient
    ) {
        $this->priceProductClient = $priceProductClient;
        $this->moneyClient = $moneyClient;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return string|null
     */
    public function getFormattedProductPrice(array $priceProductTransfers): ?string
    {
        $currentProductPriceTransfer = $this->priceProductClient->resolveProductPriceTransfer($priceProductTransfers);

        if (!$currentProductPriceTransfer->getCurrency() || !$currentProductPriceTransfer->getPrice()) {
            return null;
        }

        $moneyTransfer = (new MoneyTransfer())
            ->setCurrency($currentProductPriceTransfer->getCurrency())
            ->setAmount((string)$currentProductPriceTransfer->getPrice());

        return $this->moneyClient->formatWithSymbol($moneyTransfer);
    }
}
