<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Client\CoreMedia\Api\Configuration;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use SprykerEco\Client\CoreMedia\Api\Exception\UrlConfigurationException;
use SprykerEco\Client\CoreMedia\CoreMediaConfig;
use SprykerEco\Client\CoreMedia\CoreMediaFactory;

class UrlBuilderTest extends Unit
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
    public function testUrlBuilderProvidesCorrectUrlForApiRequest(): void
    {
        $coreMediaFragmentRequestTransfer = $this->tester->getCoreMediaFragmentRequestTransfer([
            CoreMediaFragmentRequestTransfer::STORE => 'DE',
            CoreMediaFragmentRequestTransfer::LOCALE => 'en_US',
            CoreMediaFragmentRequestTransfer::PRODUCT_ID => 111,
            CoreMediaFragmentRequestTransfer::CATEGORY_ID => 222,
            CoreMediaFragmentRequestTransfer::PAGE_ID => 'test-page',
            CoreMediaFragmentRequestTransfer::PLACEMENT => 'header',
            CoreMediaFragmentRequestTransfer::VIEW => 'asDefaultFragment',
        ]);

        $coreMediaFactoryMock = $this->getCoreMediaFactoryMock();
        $urlBuilder = $coreMediaFactoryMock->createUrlBuilder();
        $url = $urlBuilder->buildDocumentFragmentApiUrl($coreMediaFragmentRequestTransfer);

        $this->assertEquals('https://test.coremedia.com/blueprint/servlet/service/fragment/test-store/en-GB/' .
            'params;productId=111;categoryId=222;pageId=test-page;' .
            'placement=header;view=asDefaultFragment', $url);
    }

    /**
     * @return void
     */
    public function testUrlBuilderProvidesCorrectUrlOnNullParametersProvided(): void
    {
        $coreMediaFragmentRequestTransfer = $this->tester->getCoreMediaFragmentRequestTransfer([
            CoreMediaFragmentRequestTransfer::STORE => 'DE',
            CoreMediaFragmentRequestTransfer::LOCALE => 'en_US',
            CoreMediaFragmentRequestTransfer::PRODUCT_ID => null,
            CoreMediaFragmentRequestTransfer::CATEGORY_ID => 222,
            CoreMediaFragmentRequestTransfer::PAGE_ID => null,
            CoreMediaFragmentRequestTransfer::PLACEMENT => null,
            CoreMediaFragmentRequestTransfer::VIEW => null,
        ]);

        $coreMediaFactoryMock = $this->getCoreMediaFactoryMock();
        $urlBuilder = $coreMediaFactoryMock->createUrlBuilder();
        $url = $urlBuilder->buildDocumentFragmentApiUrl($coreMediaFragmentRequestTransfer);

        $this->assertEquals('https://test.coremedia.com/blueprint/servlet/service/fragment/test-store/en-GB/' .
            'params;categoryId=222', $url);
    }

    /**
     * @return void
     */
    public function testUrlBuilderFailsOnIncorrectStoreProvided(): void
    {
        $coreMediaFragmentRequestTransfer = $this->tester->getCoreMediaFragmentRequestTransfer([
            CoreMediaFragmentRequestTransfer::STORE => 'wrong-store',
            CoreMediaFragmentRequestTransfer::LOCALE => 'en_US',
            CoreMediaFragmentRequestTransfer::PRODUCT_ID => 111,
            CoreMediaFragmentRequestTransfer::CATEGORY_ID => 222,
            CoreMediaFragmentRequestTransfer::PAGE_ID => 'test-page',
            CoreMediaFragmentRequestTransfer::PLACEMENT => 'header',
            CoreMediaFragmentRequestTransfer::VIEW => 'asDefaultFragment',
        ]);

        $coreMediaFactoryMock = $this->getCoreMediaFactoryMock();
        $urlBuilder = $coreMediaFactoryMock->createUrlBuilder();

        $this->expectException(UrlConfigurationException::class);
        $urlBuilder->buildDocumentFragmentApiUrl($coreMediaFragmentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUrlBuilderFailsOnIncorrectLocaleProvided(): void
    {
        $coreMediaFragmentRequestTransfer = $this->tester->getCoreMediaFragmentRequestTransfer([
            CoreMediaFragmentRequestTransfer::STORE => 'DE',
            CoreMediaFragmentRequestTransfer::LOCALE => 'wrong_locale',
            CoreMediaFragmentRequestTransfer::PRODUCT_ID => 111,
            CoreMediaFragmentRequestTransfer::CATEGORY_ID => 222,
            CoreMediaFragmentRequestTransfer::PAGE_ID => 'test-page',
            CoreMediaFragmentRequestTransfer::PLACEMENT => 'header',
            CoreMediaFragmentRequestTransfer::VIEW => 'asDefaultFragment',
        ]);

        $coreMediaFactoryMock = $this->getCoreMediaFactoryMock();
        $urlBuilder = $coreMediaFactoryMock->createUrlBuilder();

        $this->expectException(UrlConfigurationException::class);
        $urlBuilder->buildDocumentFragmentApiUrl($coreMediaFragmentRequestTransfer);
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
