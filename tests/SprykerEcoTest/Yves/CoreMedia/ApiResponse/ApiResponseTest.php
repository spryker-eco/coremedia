<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Yves\CoreMedia\ApiResponse;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use SprykerEco\Client\CoreMedia\CoreMediaClientInterface;
use SprykerEco\Yves\CoreMedia\CoreMediaConfig;
use SprykerEco\Yves\CoreMedia\CoreMediaFactory;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface;
use SprykerEco\Yves\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface;

class ApiResponseTest extends Unit
{
    protected const IS_DEBUG_MODE_ENABLED = false;
    protected const PRODUCT_ABSTRACT_STORAGE_DATA = [
        'url' => '/en/test-product-abstract-012',
        'id_product_abstract' => 1,
    ];
    protected const PRODUCT_CONCRETE_STORAGE_DATA = [
        'url' => '/en/test-product-concrete-055',
        'id_product_abstract' => 1,
        'id_product_concrete' => 2,
    ];
    protected const CATEGORY_URL = '/en/category-12345';
    protected const PRODUCT_ABSTRACT_PRICE = 1000;
    protected const PRODUCT_CONCRETE_PRICE = 500;
    protected const CURRENCY_CODE = 'USD';

    protected const API_RESPONSE_NONEXISTENT_PLACEHOLDER_OBJECT_TYPE = '<a href="&lt;!--CM {&quot;productId&quot;:&quot;012&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;nonexistent-object-type&quot;} CM--&gt;">Incorrect data</a>';

    protected const API_RESPONSE_CORRECT_DATA = '<a href="&lt;!--CM {&quot;productId&quot;:&quot;012&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;">Test product abstract</a> '
    . '<a href="&lt;!--CM {&quot;productId&quot;:&quot;055_65789012&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;">Test product concrete</a> '
    . '<a href="&lt;!--CM {&quot;categoryId&quot;:&quot;12345&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;category&quot;} CM--&gt;">Test category</a>'
    . '&lt;!--CM {&quot;renderType&quot;:&quot;metadata&quot;,&quot;objectType&quot;:&quot;page&quot;,&quot;title&quot;:&quot;testMetaTitle&quot;,&quot;description&quot;:&quot;testMetaDescription&quot;,&quot;keywords&quot;:&quot;testMetaKeywords&quot;,&quot;pageName&quot;:&quot;testMetaPageName&quot;} CM--&gt;'
    . 'Product abstract price: &lt;!--CM {&quot;productId&quot;:&quot;013&quot;,&quot;renderType&quot;:&quot;price&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;'
    . 'Product concrete price: &lt;!--CM {&quot;productId&quot;:&quot;013_34234&quot;,&quot;renderType&quot;:&quot;price&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;';

    protected const API_RESPONSE_INCORRECT_DATA = '<a href="&lt;!--CM {&quot;productId&quot;:&quot;073&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;">Test product abstract</a> '
    . '<a href="&lt;!--CM {&quot;productId&quot;:&quot;056_1234567&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;">Test product concrete</a> '
    . '<a href="&lt;!--CM {&quot;categoryId&quot;:&quot;56789&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;category&quot;} CM--&gt;">Test category</a>'
    . '<!--CM {"renderType":"metadata","objectType":"page","pbe":"pbe","slider":"slider"} CM-->'
    . 'Product abstract price: &lt;!--CM {&quot;productId&quot;:&quot;014&quot;,&quot;renderType&quot;:&quot;price&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;'
    . 'Product concrete price: &lt;!--CM {&quot;productId&quot;:&quot;014_34234&quot;,&quot;renderType&quot;:&quot;price&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;';

    /**
     * @var \SprykerEcoTest\Yves\CoreMedia\CoreMediaYvesTester
     */
    protected $tester;

    /**
     * @dataProvider correctApiResponseDataProvider
     *
     * @param string $correctApiResponseData
     *
     * @return void
     */
    public function testCoreMediaClientProvidesCorrectDataWithReplacedPlaceholders(string $correctApiResponseData): void
    {
        $unprocessedCoreMediaApiResponseTransfer = $this->tester->getCoreMediaApiResponseTransfer([
            CoreMediaApiResponseTransfer::IS_SUCCESSFUL => true,
            CoreMediaApiResponseTransfer::DATA => $correctApiResponseData,
        ]);

        $categoryNodeStorageTransfer = $this->tester->getCategoryNodeStorageTransfer([
            CategoryNodeStorageTransfer::URL => static::CATEGORY_URL,
        ]);

        $coreMediaApiResponseTransfer = $this->prepare(
            $unprocessedCoreMediaApiResponseTransfer,
            static::PRODUCT_ABSTRACT_STORAGE_DATA,
            static::PRODUCT_CONCRETE_STORAGE_DATA,
            $categoryNodeStorageTransfer
        );

        $this->assertEquals(
            $coreMediaApiResponseTransfer->getData(),
            '<a href="/en/test-product-abstract-012">Test product abstract</a> ' .
            '<a href="/en/test-product-concrete-055">Test product concrete</a> ' .
            '<a href="/en/category-12345">Test category</a>' .
            '<meta name="title" content="testMetaTitle"><meta name="description" content="testMetaDescription"><meta name="keywords" content="testMetaKeywords"><meta name="pageName" content="testMetaPageName">' .
            'Product abstract price: USD1000' .
            'Product concrete price: USD500'
        );
    }

    /**
     * @dataProvider nonexistentPlaceholderObjectTypeApiResponseDataProvider
     *
     * @param string $nonexistentPlaceholderObjectTypeApiResponseData
     *
     * @return void
     */
    public function testCoreMediaClientReturnsTheSameDataOnIncorrectPlaceholderObjectType(
        string $nonexistentPlaceholderObjectTypeApiResponseData
    ): void {
        $unprocessedCoreMediaApiResponseTransfer = $this->tester->getCoreMediaApiResponseTransfer([
            CoreMediaApiResponseTransfer::IS_SUCCESSFUL => true,
            CoreMediaApiResponseTransfer::DATA => $nonexistentPlaceholderObjectTypeApiResponseData,
        ]);

        $categoryNodeStorageTransfer = $this->tester->getCategoryNodeStorageTransfer([
            CategoryNodeStorageTransfer::URL => static::CATEGORY_URL,
        ]);

        $coreMediaApiResponseTransfer = $this->prepare(
            $unprocessedCoreMediaApiResponseTransfer,
            static::PRODUCT_ABSTRACT_STORAGE_DATA,
            static::PRODUCT_CONCRETE_STORAGE_DATA,
            $categoryNodeStorageTransfer
        );

        $this->assertEquals(
            $coreMediaApiResponseTransfer->getData(),
            $nonexistentPlaceholderObjectTypeApiResponseData
        );
    }

    /**
     * @dataProvider incorrectApiResponseDataProvider
     *
     * @param string $incorrectApiResponseData
     *
     * @return void
     */
    public function testCoreMediaClientFailsOnIncorrectPlaceholdersData(string $incorrectApiResponseData): void
    {
        $unprocessedCoreMediaApiResponseTransfer = $this->tester->getCoreMediaApiResponseTransfer([
            CoreMediaApiResponseTransfer::IS_SUCCESSFUL => true,
            CoreMediaApiResponseTransfer::DATA => $incorrectApiResponseData,
        ]);

        $categoryNodeStorageTransfer = $this->tester->getCategoryNodeStorageTransfer([]);

        $coreMediaApiResponseTransfer = $this->prepare(
            $unprocessedCoreMediaApiResponseTransfer,
            [],
            [],
            $categoryNodeStorageTransfer
        );

        $this->assertEquals(
            $coreMediaApiResponseTransfer->getData(),
            '<a href="">Test product abstract</a> <a href="">Test product concrete</a> <a href="">Test category</a>'
            . '<!--CM {"renderType":"metadata","objectType":"page","pbe":"pbe","slider":"slider"} CM-->'
            . 'Product abstract price: '
            . 'Product concrete price: '
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
    protected function prepare(
        CoreMediaApiResponseTransfer $unprocessedCoreMediaApiResponseTransfer,
        array $productAbstractStorageData,
        array $productConcreteStorageData,
        CategoryNodeStorageTransfer $categoryNodeStorageTransfer
    ): CoreMediaApiResponseTransfer {
        $productStorageClient = $this->getProductStorageClientMock(
            $productAbstractStorageData,
            $productConcreteStorageData
        );
        $categoryStorageClient = $this->getCategoryStorageClientMock($categoryNodeStorageTransfer);
        $priceProductStorageClient = $this->getPriceProductStorageClientMock();
        $priceProductClient = $this->getPriceProductClientMock();
        $moneyClient = $this->getMoneyClientMock();

        $apiResponse = $this->getCoreMediaFactoryMock(
            $productStorageClient,
            $categoryStorageClient,
            $priceProductStorageClient,
            $priceProductClient,
            $moneyClient
        )->createApiResponse();

        return $apiResponse->prepare(
            $this->getCoreMediaClientMock($unprocessedCoreMediaApiResponseTransfer)->getDocumentFragment(
                $this->tester->getCoreMediaFragmentRequestTransfer()
            ),
            'en_US'
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
     * @return \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getProductStorageClientMock(
        array $productAbstractStorageData,
        array $productConcreteStorageData
    ): CoreMediaToProductStorageClientInterface {
        $coreMediaToProductStorageClientBridge = $this->getMockBuilder(CoreMediaToProductStorageClientInterface::class)->getMock();
        $coreMediaToProductStorageClientBridge
            ->method('findProductAbstractStorageDataByMapping')
            ->willReturnCallback(function (string $mappingType, string $identifier) use ($productAbstractStorageData) {
                return strpos($identifier, '_') ? null : $productAbstractStorageData;
            });
        $coreMediaToProductStorageClientBridge
            ->method('findProductConcreteStorageDataByMapping')
            ->willReturnCallback(function (string $mappingType, string $identifier) use ($productConcreteStorageData) {
                return strpos($identifier, '_') ? $productConcreteStorageData : null;
            });

        return $coreMediaToProductStorageClientBridge;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     *
     * @return \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
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
     * @return \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getPriceProductStorageClientMock(): CoreMediaToPriceProductStorageClientInterface
    {
        $coreMediaToPriceProductStorageClientBridge = $this->getMockBuilder(CoreMediaToPriceProductStorageClientInterface::class)->getMock();
        $coreMediaToPriceProductStorageClientBridge->method('getPriceProductAbstractTransfers')->willReturn([
            $this->tester->getPriceProductTransfer([
                PriceProductTransfer::MONEY_VALUE => $this->tester->getMoneyValueTransfer([
                    MoneyValueTransfer::NET_AMOUNT => static::PRODUCT_ABSTRACT_PRICE,
                ]),
            ]),
        ]);
        $coreMediaToPriceProductStorageClientBridge->method('getResolvedPriceProductConcreteTransfers')->willReturn([
            $this->tester->getPriceProductTransfer([
                PriceProductTransfer::MONEY_VALUE => $this->tester->getMoneyValueTransfer([
                    MoneyValueTransfer::NET_AMOUNT => static::PRODUCT_CONCRETE_PRICE,
                ]),
            ]),
        ]);

        return $coreMediaToPriceProductStorageClientBridge;
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getPriceProductClientMock(): CoreMediaToPriceProductClientInterface
    {
        $coreMediaToPriceProductClientBridge = $this->getMockBuilder(CoreMediaToPriceProductClientInterface::class)->getMock();
        $coreMediaToPriceProductClientBridge->method('resolveProductPriceTransfer')->willReturnCallback(function (array $priceProductTransfers) {
            return $this->tester->getCurrentProductPriceTransfer([
                CurrentProductPriceTransfer::PRICE => $priceProductTransfers[0]->getMoneyValue()->getNetAmount(),
                CurrentProductPriceTransfer::CURRENCY => $this->tester->getCurrencyTransfer(),
            ]);
        });

        return $coreMediaToPriceProductClientBridge;
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMoneyClientMock(): CoreMediaToMoneyClientInterface
    {
        $coreMediaToMoneyClientBridge = $this->getMockBuilder(CoreMediaToMoneyClientInterface::class)->getMock();
        $coreMediaToMoneyClientBridge->method('formatWithSymbol')->willReturnCallback(function (MoneyTransfer $moneyTransfer) {
            return static::CURRENCY_CODE . $moneyTransfer->getAmount();
        });

        return $coreMediaToMoneyClientBridge;
    }

    /**
     * @param \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface $productStorageClient
     * @param \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface $categoryStorageClient
     * @param \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface $priceProductClient
     * @param \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface $moneyClient
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Yves\CoreMedia\CoreMediaFactory
     */
    protected function getCoreMediaFactoryMock(
        CoreMediaToProductStorageClientInterface $productStorageClient,
        CoreMediaToCategoryStorageClientInterface $categoryStorageClient,
        CoreMediaToPriceProductStorageClientInterface $priceProductStorageClient,
        CoreMediaToPriceProductClientInterface $priceProductClient,
        CoreMediaToMoneyClientInterface $moneyClient
    ): CoreMediaFactory {
        $coreMediaFactoryMock = $this->getMockBuilder(CoreMediaFactory::class)
            ->setMethods([
                'getConfig',
                'getUtilEncodingService',
                'getProductStorageClient',
                'getCategoryStorageClient',
                'getPriceProductStorageClient',
                'getPriceProductClient',
                'getMoneyClient',
            ])->getMock();

        $coreMediaFactoryMock->method('getConfig')->willReturn(
            $this->getCoreMediaConfigMock()
        );
        $coreMediaFactoryMock->method('getUtilEncodingService')->willReturn(
            $this->getUtilEncodingMock()
        );
        $coreMediaFactoryMock->method('getProductStorageClient')->willReturn($productStorageClient);
        $coreMediaFactoryMock->method('getCategoryStorageClient')->willReturn($categoryStorageClient);
        $coreMediaFactoryMock->method('getPriceProductStorageClient')->willReturn($priceProductStorageClient);
        $coreMediaFactoryMock->method('getPriceProductClient')->willReturn($priceProductClient);
        $coreMediaFactoryMock->method('getMoneyClient')->willReturn($moneyClient);

        return $coreMediaFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Yves\CoreMedia\CoreMediaConfig
     */
    protected function getCoreMediaConfigMock(): CoreMediaConfig
    {
        $coreMediaConfigMock = $this->getMockBuilder(CoreMediaConfig::class)
            ->setMethods(['isDebugModeEnabled'])->getMock();
        $coreMediaConfigMock->method('isDebugModeEnabled')->willReturn(static::IS_DEBUG_MODE_ENABLED);

        return $coreMediaConfigMock;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Client\CoreMedia\CoreMediaClientInterface
     */
    protected function getCoreMediaClientMock(
        CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
    ): CoreMediaClientInterface {
        $coreMediaClientMock = $this->getMockBuilder(CoreMediaClientInterface::class)
            ->setMethods(['getDocumentFragment'])
            ->getMock();
        $coreMediaClientMock->method('getDocumentFragment')->willReturn($coreMediaApiResponseTransfer);

        return $coreMediaClientMock;
    }

    /**
     * @return string[][]
     */
    public function correctApiResponseDataProvider(): array
    {
        return [
            [static::API_RESPONSE_CORRECT_DATA],
            [html_entity_decode(static::API_RESPONSE_CORRECT_DATA)],
        ];
    }

    /**
     * @return string[][]
     */
    public function incorrectApiResponseDataProvider(): array
    {
        return [
            [static::API_RESPONSE_INCORRECT_DATA],
            [html_entity_decode(static::API_RESPONSE_INCORRECT_DATA)],
        ];
    }

    /**
     * @return string[][]
     */
    public function nonexistentPlaceholderObjectTypeApiResponseDataProvider(): array
    {
        return [
            [static::API_RESPONSE_NONEXISTENT_PLACEHOLDER_OBJECT_TYPE],
            [html_entity_decode(static::API_RESPONSE_NONEXISTENT_PLACEHOLDER_OBJECT_TYPE)],
        ];
    }
}
