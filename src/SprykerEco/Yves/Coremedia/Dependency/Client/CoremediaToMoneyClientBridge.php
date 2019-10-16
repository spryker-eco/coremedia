<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\Dependency\Client;

use Generated\Shared\Transfer\MoneyTransfer;

class CoremediaToMoneyClientBridge implements CoremediaToMoneyClientInterface
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
