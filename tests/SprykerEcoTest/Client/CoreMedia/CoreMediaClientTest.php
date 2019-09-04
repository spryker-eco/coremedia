<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Client\CoreMedia;

use Codeception\Test\Unit;
use SprykerEco\Client\CoreMedia\CoreMediaDependencyProvider;

class CoreMediaClientTest extends Unit
{
    /**
     * @var \SprykerEcoTest\Client\CoreMedia\CoreMediaClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCoreMediaClientProvidesCorrectDataWithReplacedPlaceholders()
    {
    }

    /**
     * @return void
     */
    protected function setProductStorageClientDependency(): void
    {
        $this->tester->setDependency(
            CoreMediaDependencyProvider::CLIENT_PRODUCT_STORAGE,
            $this->getCoreMediaToProductStorageClientBridgeMock()
        );
    }
}
