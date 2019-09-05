<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Client\CoreMedia;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use SprykerEco\Client\CoreMedia\Api\Executor\RequestExecutorInterface;
use SprykerEco\Client\CoreMedia\CoreMediaConfig;
use SprykerEco\Client\CoreMedia\CoreMediaFactory;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface;
use SprykerEco\Client\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface;

class CoreMediaClientTest extends Unit
{
    protected const CORE_MEDIA_HOST = 'https://test.coremedia.com';
    protected const IS_DEBUG_MODE_ENABLED = false;
    protected const APPLICATION_STORE_MAPPING = [
        'DE' => 'test-store',
    ];
    protected const APPLICATION_STORE_LOCALE_MAPPING = [
        'test-store' => [
            'en_US' => 'en-GB',
        ],
    ];
    protected const PRODUCT_ABSTRACT_STORAGE_DATA = [
        'url' => '/en/test-product-abstract-012',
    ];
    protected const PRODUCT_CONCRETE_STORAGE_DATA = [
        'url' => '/en/test-product-concrete-055',
    ];
    protected const CATEGORY_URL = '/en/category-12345';
    protected const API_RESPONSE_NONEXISTENT_PLACEHOLDER_OBJECT_TYPE = '<a href="&lt;!--CM {&quot;productId&quot;:&quot;012&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;nonexistent-object-type&quot;} CM--&gt;">Incorrect data</a>';

    /**
     * @var \SprykerEcoTest\Client\CoreMedia\CoreMediaClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCoreMediaClientProvidesCorrectDataWithReplacedPlaceholders()
    {
        $unprocessedCoreMediaApiResponseTransfer = $this->tester->getCoreMediaApiResponseTransfer([
            CoreMediaApiResponseTransfer::IS_SUCCESSFUL => true,
            CoreMediaApiResponseTransfer::DATA => $this->tester->getCorrectCoreMediaApiResponseData(),
        ]);

        $categoryNodeStorageTransfer = $this->tester->getCategoryNodeStorageTransfer([
            CategoryNodeStorageTransfer::URL => static::CATEGORY_URL,
        ]);

        $coreMediaApiResponseTransfer = $this->getDocumentFragmentData(
            $unprocessedCoreMediaApiResponseTransfer,
            static::PRODUCT_ABSTRACT_STORAGE_DATA,
            static::PRODUCT_CONCRETE_STORAGE_DATA,
            $categoryNodeStorageTransfer
        );

        $this->assertEquals(
            $coreMediaApiResponseTransfer->getData(),
            '<a href="/en/test-product-abstract-012">Test product abstract</a> ' .
            '<a href="/en/test-product-abstract-012">Test product concrete</a> ' .
            '<a href="/en/category-12345">Test category</a>'
        );
    }

    /**
     * @return void
     */
    public function testCoreMediaClientReturnsTheSameDataOnIncorrectPlaceholderObjectType()
    {
        $unprocessedCoreMediaApiResponseTransfer = $this->tester->getCoreMediaApiResponseTransfer([
            CoreMediaApiResponseTransfer::IS_SUCCESSFUL => true,
            CoreMediaApiResponseTransfer::DATA => static::API_RESPONSE_NONEXISTENT_PLACEHOLDER_OBJECT_TYPE,
        ]);

        $categoryNodeStorageTransfer = $this->tester->getCategoryNodeStorageTransfer([
            CategoryNodeStorageTransfer::URL => static::CATEGORY_URL,
        ]);

        $coreMediaApiResponseTransfer = $this->getDocumentFragmentData(
            $unprocessedCoreMediaApiResponseTransfer,
            static::PRODUCT_ABSTRACT_STORAGE_DATA,
            static::PRODUCT_CONCRETE_STORAGE_DATA,
            $categoryNodeStorageTransfer
        );

        $this->assertEquals(
            $coreMediaApiResponseTransfer->getData(),
            static::API_RESPONSE_NONEXISTENT_PLACEHOLDER_OBJECT_TYPE
        );
    }

    /**
     * @return void
     */
    public function testCoreMediaClientFailsOnIncorrectPlaceholdersData()
    {
        $unprocessedCoreMediaApiResponseTransfer = $this->tester->getCoreMediaApiResponseTransfer([
            CoreMediaApiResponseTransfer::IS_SUCCESSFUL => true,
            CoreMediaApiResponseTransfer::DATA => $this->tester->getIncorrectCoreMediaApiResponseData(),
        ]);

        $categoryNodeStorageTransfer = $this->tester->getCategoryNodeStorageTransfer([]);

        $coreMediaApiResponseTransfer = $this->getDocumentFragmentData(
            $unprocessedCoreMediaApiResponseTransfer,
            [],
            [],
            $categoryNodeStorageTransfer
        );

        $this->assertEquals(
            $coreMediaApiResponseTransfer->getData(),
            '<a href="">Test product abstract</a> <a href="">Test product concrete</a> <a href="">Test category</a>'
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaApiResponseTransfer $unprocessedCoreMediaApiResponseTransfer
     * @param array $productAbstractStorageData
     * @param array $productConcreteStorageData
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    protected function getDocumentFragmentData(
        CoreMediaApiResponseTransfer $unprocessedCoreMediaApiResponseTransfer,
        array $productAbstractStorageData,
        array $productConcreteStorageData,
        CategoryNodeStorageTransfer $categoryNodeStorageTransfer
    ) {
        $requestExecutor = $this->getRequestExecutorMock($unprocessedCoreMediaApiResponseTransfer);
        $productStorageClient = $this->getProductStorageClientMock(
            $productAbstractStorageData,
            $productConcreteStorageData
        );

        $categoryStorageClient = $this->getCategoryStorageClientMock($categoryNodeStorageTransfer);

        $coreMediaClient = $this->tester->getCoreMediaClient();
        $coreMediaClient->setFactory(
            $this->getCoreMediaFactoryMock(
                $requestExecutor,
                $productStorageClient,
                $categoryStorageClient
            )
        );

        return $coreMediaClient->getDocumentFragment(
            $this->tester->getCoreMediaFragmentRequestTransfer([
                CoreMediaFragmentRequestTransfer::STORE => 'DE',
                CoreMediaFragmentRequestTransfer::LOCALE => 'en_US',
            ])
        );
    }

    /**
     * @return \Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getUtilEncodingMock()
    {
        $utilEncodingMock = $this->getMockBuilder(CoreMediaToUtilEncodingServiceInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['decodeJson'])
            ->getMock();

        $utilEncodingMock
            ->method('decodeJson')
            ->willReturnCallback(function ($json, $assoc) {
                return json_decode($json, $assoc);
            });

        return $utilEncodingMock;
    }

    /**
     * @param array $productAbstractStorageData
     * @param array $productConcreteStorageData
     *
     * @return \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getProductStorageClientMock(
        array $productAbstractStorageData,
        array $productConcreteStorageData
    ): CoreMediaToProductStorageClientInterface {
        $coreMediaToProductStorageClientBridge = $this->getMockBuilder(CoreMediaToProductStorageClientInterface::class)->getMock();
        $coreMediaToProductStorageClientBridge->method('findProductAbstractStorageDataByMapping')->willReturn(
            $productAbstractStorageData
        );
        $coreMediaToProductStorageClientBridge->method('findProductConcreteStorageDataByMapping')->willReturn(
            $productConcreteStorageData
        );

        return $coreMediaToProductStorageClientBridge;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     *
     * @return \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getCategoryStorageClientMock(
        CategoryNodeStorageTransfer $categoryNodeStorageTransfer
    ): CoreMediaToCategoryStorageClientInterface {
        $coreMediaToCategoryStorageClientBridge = $this->getMockBuilder(CoreMediaToCategoryStorageClientInterface::class)->getMock();
        $coreMediaToCategoryStorageClientBridge->method('getCategoryNodeById')->willReturn(
            $categoryNodeStorageTransfer
        );

        return $coreMediaToCategoryStorageClientBridge;
    }

    /**
     * @param \SprykerEco\Client\CoreMedia\Api\Executor\RequestExecutorInterface $requestExecutor
     * @param \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface $productStorageClient
     * @param \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface $categoryStorageClient
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Client\CoreMedia\CoreMediaFactory
     */
    protected function getCoreMediaFactoryMock(
        RequestExecutorInterface $requestExecutor,
        CoreMediaToProductStorageClientInterface $productStorageClient,
        CoreMediaToCategoryStorageClientInterface $categoryStorageClient
    ): CoreMediaFactory {
        $coreMediaFactoryMock = $this->getMockBuilder(CoreMediaFactory::class)
            ->setMethods([
                'createCoreMediaApiRequestExecutor',
                'getConfig',
                'getUtilEncodingService',
                'getProductStorageClient',
                'getCategoryStorageClient',
            ])->getMock();
        $coreMediaFactoryMock->method('createCoreMediaApiRequestExecutor')->willReturn($requestExecutor);
        $coreMediaFactoryMock->method('getConfig')->willReturn(
            $this->getCoreMediaConfigMock()
        );
        $coreMediaFactoryMock->method('getUtilEncodingService')->willReturn(
            $this->getUtilEncodingMock()
        );
        $coreMediaFactoryMock->method('getProductStorageClient')->willReturn($productStorageClient);
        $coreMediaFactoryMock->method('getCategoryStorageClient')->willReturn($categoryStorageClient);

        return $coreMediaFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Client\CoreMedia\CoreMediaConfig
     */
    protected function getCoreMediaConfigMock(): CoreMediaConfig
    {
        $coreMediaConfigMock = $this->getMockBuilder(CoreMediaConfig::class)
            ->setMethods([
                'getCoreMediaHost',
                'isDebugModeEnabled',
                'getApplicationStoreMapping',
                'getApplicationStoreLocaleMapping',
            ])
            ->getMock();
        $coreMediaConfigMock->method('isDebugModeEnabled')->willReturn(static::IS_DEBUG_MODE_ENABLED);
        $coreMediaConfigMock->method('getCoreMediaHost')->willReturn(static::CORE_MEDIA_HOST);
        $coreMediaConfigMock->method('getApplicationStoreMapping')
            ->willReturn(static::APPLICATION_STORE_MAPPING);
        $coreMediaConfigMock->method('getApplicationStoreLocaleMapping')
            ->willReturn(static::APPLICATION_STORE_LOCALE_MAPPING);

        return $coreMediaConfigMock;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Client\CoreMedia\Api\Executor\RequestExecutorInterface
     */
    protected function getRequestExecutorMock(
        CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
    ): RequestExecutorInterface {
        $coreMediaFactoryMock = $this->getMockBuilder(RequestExecutorInterface::class)
            ->setMethods(['execute'])
            ->getMock();
        $coreMediaFactoryMock->method('execute')->willReturn($coreMediaApiResponseTransfer);

        return $coreMediaFactoryMock;
    }
}
