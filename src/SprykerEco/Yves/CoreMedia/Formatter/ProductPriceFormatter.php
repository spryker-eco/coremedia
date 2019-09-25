<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
