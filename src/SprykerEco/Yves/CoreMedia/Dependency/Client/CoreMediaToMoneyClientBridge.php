<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CoreMedia\Dependency\Client;

use Generated\Shared\Transfer\MoneyTransfer;

class CoreMediaToMoneyClientBridge implements CoreMediaToMoneyClientInterface
{
    /**
     * @var \Spryker\Client\Money\MoneyClientInterface
     */
    protected $moneyClient;

    /**
     * @param \Spryker\Client\Money\MoneyClientInterface $moneyClient
     */
    public function __construct($moneyClient)
    {
        $this->moneyClient = $moneyClient;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithSymbol(MoneyTransfer $moneyTransfer): string
    {
        return $this->moneyClient->formatWithSymbol($moneyTransfer);
    }
}
