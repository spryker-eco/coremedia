<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Client\CoreMedia;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use SprykerEco\Client\CoreMedia\CoreMediaDependencyProvider;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface;

class CoreMediaClientTest extends Unit
{
    protected const PRODUCT_ABSTRACT_STORAGE_DATA = [];
    protected const PRODUCT_CONCRETE_STORAGE_DATA = [];
    protected const CATEGORY_STORAGE_DATA = [];

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

    /**
     * @return void
     */
    protected function setCategoryStorageClientDependency(): void
    {
        $this->tester->setDependency(
            CoreMediaDependencyProvider::CLIENT_CATEGORY_STORAGE,
            $this->getCoreMediaToCategoryStorageClientBridgeMock()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getCoreMediaToProductStorageClientBridgeMock(): CoreMediaToProductStorageClientInterface
    {
        $coreMediaToProductStorageClientBridge = $this->getMockBuilder(CoreMediaToProductStorageClientInterface::class)->getMock();
        $coreMediaToProductStorageClientBridge->method('findProductAbstractStorageDataByMapping')->willReturn(
            static::PRODUCT_ABSTRACT_STORAGE_DATA
        );
        $coreMediaToProductStorageClientBridge->method('findProductConcreteStorageDataByMapping')->willReturn(
            static::PRODUCT_CONCRETE_STORAGE_DATA
        );;

        return $coreMediaToProductStorageClientBridge;
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getCoreMediaToCategoryStorageClientBridgeMock(): CoreMediaToCategoryStorageClientInterface
    {
        $coreMediaToCategoryStorageClientBridge = $this->getMockBuilder(CoreMediaToCategoryStorageClientInterface::class)->getMock();
        $coreMediaToCategoryStorageClientBridge->method('getCategoryNodeById')->willReturn(
            (new CategoryNodeStorageTransfer())->setUrl('category_url')
        );

        return $coreMediaToCategoryStorageClientBridge;
    }
}
