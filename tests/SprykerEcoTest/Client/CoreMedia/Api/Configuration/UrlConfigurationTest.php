<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Client\CoreMedia\Api\Configuration;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use SprykerEco\Client\CoreMedia\CoreMediaConfig;
use SprykerEco\Client\CoreMedia\CoreMediaFactory;

class UrlConfigurationTest extends Unit
{
    protected const CORE_MEDIA_HOST = 'https://test.coremedia.com';
    protected const APPLICATION_STORE_MAPPING = [
        'DE' => 'test-store',
    ];
    protected const APPLICATION_STORE_LOCALE_MAPPING = [
        'test-store' => [
            'en_US' => 'en-GB',
        ],
    ];

    /**
     * @var \SprykerEcoTest\Client\CoreMedia\CoreMediaClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUrlConfigurationProvidesCorrectUrlForApiCall(): void
    {
        $coreMediaFragmentRequestTransfer = $this->tester->getCoreMediaFragmentRequestTransfer([
            CoreMediaFragmentRequestTransfer::STORE => 'DE',
            CoreMediaFragmentRequestTransfer::LOCALE => 'en_US',
            CoreMediaFragmentRequestTransfer::PRODUCT_ID => 111,
            CoreMediaFragmentRequestTransfer::CATEGORY_ID => 222,
            CoreMediaFragmentRequestTransfer::PAGE_ID => 'test-page',
            CoreMediaFragmentRequestTransfer::PLACEMENT => 'header',
            CoreMediaFragmentRequestTransfer::EXTERNAL_REF => 'test-cms-slot-key',
            CoreMediaFragmentRequestTransfer::VIEW => 'asDefaultFragment',
        ]);

        $coreMediaFactoryMock = $this->getCoreMediaFactoryMock();
        $urlConfiguration = $coreMediaFactoryMock->createUrlConfiguration();
        $url = $urlConfiguration->getDocumentFragmentApiUrl($coreMediaFragmentRequestTransfer);

        $this->assertEquals('https://test.coremedia.com/blueprint/servlet/service/fragment/test-store/en-GB/' .
            'params;pageId=test-page;externalRef=test-cms-slot-key;productId=111;categoryId=222;' .
            'view=asDefaultFragment;placement=header', $url);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Client\CoreMedia\CoreMediaConfig
     */
    protected function getCoreMediaConfigMock(): CoreMediaConfig
    {
        $coreMediaConfigMock = $this->getMockBuilder(CoreMediaConfig::class)
            ->setMethods(['getCoreMediaHost', 'getApplicationStoreMapping', 'getApplicationStoreLocaleMapping'])
            ->getMock();
        $coreMediaConfigMock->method('getCoreMediaHost')->willReturn(static::CORE_MEDIA_HOST);
        $coreMediaConfigMock->method('getApplicationStoreMapping')
            ->willReturn(static::APPLICATION_STORE_MAPPING);
        $coreMediaConfigMock->method('getApplicationStoreLocaleMapping')
            ->willReturn(static::APPLICATION_STORE_LOCALE_MAPPING);

        return $coreMediaConfigMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Client\CoreMedia\CoreMediaFactory
     */
    protected function getCoreMediaFactoryMock(): CoreMediaFactory
    {
        $coreMediaFactoryMock = $this->getMockBuilder(CoreMediaFactory::class)
            ->setMethods(['getConfig'])
            ->getMock();
        $coreMediaFactoryMock->method('getConfig')->willReturn($this->getCoreMediaConfigMock());

        return $coreMediaFactoryMock;
    }
}
