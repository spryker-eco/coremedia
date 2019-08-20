<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Api\Configuration;

use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use SprykerEco\Client\CoreMedia\Api\Exception\InvalidHostException;
use SprykerEco\Client\CoreMedia\CoreMediaConfig;

class UrlConfiguration implements UrlConfigurationInterface
{
    protected const HTTP_QUERY_KEY_VALUE_PATTERN = '%s=%s';

    /**
     * @var \SprykerEco\Client\CoreMedia\CoreMediaConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Client\CoreMedia\CoreMediaConfig $config
     */
    public function __construct(CoreMediaConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return string
     */
    public function getDocumentFragmentApiUrl(
        CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): string {
        $queryParamString = $this->getQueryStringFromCoreMediaFragmentRequestTransfer(
            $coreMediaFragmentRequestTransfer
        );

        return $this->buildCoreMediaApiUrl($this->config->getFragmentBasePath() . $queryParamString);
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return string
     */
    protected function getQueryStringFromCoreMediaFragmentRequestTransfer(
        CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): string {
        $storeId = $this->getStoreIdByStoreName($coreMediaFragmentRequestTransfer->getStore());
        $locale = $this->getLocaleByStoreIdAndLocaleName($storeId, $coreMediaFragmentRequestTransfer->getLocale());
        $queryParams = $this->getQueryParamsFromCoreMediaFragmentRequestTransfer($coreMediaFragmentRequestTransfer);

        return sprintf(
            '%s/%s/params;%s',
            $storeId,
            $locale,
            implode(';', $queryParams)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return string[]
     */
    protected function getQueryParamsFromCoreMediaFragmentRequestTransfer(
        CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): array {
        $queryParams = [
            $this->getQueryKeyValueString(
                CoreMediaFragmentRequestTransfer::PAGE_ID,
                $coreMediaFragmentRequestTransfer->getPageId()
            ),
            $this->getQueryKeyValueString(
                CoreMediaFragmentRequestTransfer::EXTERNAL_REF,
                $coreMediaFragmentRequestTransfer->getExternalRef()
            ),
            $this->getQueryKeyValueString(
                CoreMediaFragmentRequestTransfer::PRODUCT_ID,
                $coreMediaFragmentRequestTransfer->getProductId()
            ),
            $this->getQueryKeyValueString(
                CoreMediaFragmentRequestTransfer::CATEGORY_ID,
                $coreMediaFragmentRequestTransfer->getCategoryId()
            ),
            $this->getQueryKeyValueString(
                CoreMediaFragmentRequestTransfer::VIEW,
                $coreMediaFragmentRequestTransfer->getView()
            ),
            $this->getQueryKeyValueString(
                CoreMediaFragmentRequestTransfer::PLACEMENT,
                $coreMediaFragmentRequestTransfer->getPlacement()
            ),
        ];

        return $queryParams;
    }

    /**
     * @param string $storeName
     *
     * @return string
     */
    protected function getStoreIdByStoreName(string $storeName): string
    {
        return $this->config->getApplicationStoreMapping()[$storeName];
    }

    /**
     * @param string $storeId
     * @param string $localeName
     *
     * @return string
     */
    protected function getLocaleByStoreIdAndLocaleName(string $storeId, string $localeName): string
    {
        return $this->config->getApplicationStoreLocaleMapping()[$storeId][$localeName];
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return string
     */
    protected function getQueryKeyValueString(string $key, $value): string
    {
        if (is_bool($value)) {
            return sprintf(static::HTTP_QUERY_KEY_VALUE_PATTERN, $key, var_export($value, true));
        }

        if ($value === null) {
            return '';
        }

        if (is_scalar($value)) {
            return sprintf(static::HTTP_QUERY_KEY_VALUE_PATTERN, $key, rawurlencode($value));
        }

        return '';
    }

    /**
     * @param string $urlPath
     *
     * @return string
     */
    protected function buildCoreMediaApiUrl(string $urlPath): string
    {
        return $this->getCoreMediaHost() . $urlPath;
    }

    /**
     * @throws \SprykerEco\Client\CoreMedia\Api\Exception\InvalidHostException
     *
     * @return string
     */
    protected function getCoreMediaHost(): string
    {
        $coreMediaHost = $this->config->getCoreMediaHost();

        if (!$coreMediaHost) {
            throw new InvalidHostException('Please specify the CoreMedia host.');
        }

        return $coreMediaHost;
    }
}
