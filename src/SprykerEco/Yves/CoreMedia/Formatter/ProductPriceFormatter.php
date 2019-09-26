<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\Formatter;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface;

class ProductPriceFormatter implements ProductPriceFormatterInterface
{
    /**
     * @var \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface
     */
    protected $moneyClient;

    /**
     * @param \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface $moneyClient
     */
    public function __construct(CoreMediaToMoneyClientInterface $moneyClient)
    {
        $this->moneyClient = $moneyClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     *
     * @return string
     */
    public function getFormattedProductPrice(CurrentProductPriceTransfer $currentProductPriceTransfer): string
    {
        $moneyTransfer = (new MoneyTransfer())
            ->setCurrency($currentProductPriceTransfer->getCurrency())
            ->setAmount((string)$currentProductPriceTransfer->getPrice());

        return $this->moneyClient->formatWithSymbol($moneyTransfer);
    }
}
