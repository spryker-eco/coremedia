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
    protected const API_URL_PATTERN = '%s%s';
    protected const HTTP_QUERY_KEY_VALUE_PATTERN = '%s=%s';
    protected const QUERY_PATH_FOR_FRAGMENT_REQUEST_PATTERN = '%s/%s/params;%s?fragmentKey=%s';

    /**
     * @var \SprykerEco\Client\CoreMedia\CoreMediaConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Client\CoreMedia\CoreMediaConfig $config
     */
    public function __construct(
        CoreMediaConfig $config
    ) {
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
        $pageId = $this->getQueryKeyValueString(
            CoreMediaFragmentRequestTransfer::PAGE_ID,
            $coreMediaFragmentRequestTransfer->getPageId()
        );

        $queryParams = $this->getQueryParamsFromCoreMediaFragmentRequestTransfer($coreMediaFragmentRequestTransfer);

        return sprintf(
            static::QUERY_PATH_FOR_FRAGMENT_REQUEST_PATTERN,
            $storeId,
            $locale,
            $pageId,
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
        $categoryId = $coreMediaFragmentRequestTransfer->getCategoryId();

        if ($coreMediaFragmentRequestTransfer->getProductId()) {
            $categoryId = null;
        }

        $queryParams = [
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
                $categoryId
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
        return $this->getStoreMapping()[$storeName];
    }

    /**
     * @param string $storeId
     * @param string $localeName
     *
     * @return string
     */
    protected function getLocaleByStoreIdAndLocaleName(string $storeId, string $localeName): string
    {
        return $this->getLocaleMapping()[$storeId][$localeName];
    }

    /**
     * @return string[]
     */
    protected function getStoreMapping(): array
    {
        return $this->config->getStoreMapping();
    }

    /**
     * @return array
     */
    protected function getLocaleMapping(): array
    {
        return $this->config->getLocaleMapping();
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
            return sprintf(static::HTTP_QUERY_KEY_VALUE_PATTERN, $key, '');
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
        return sprintf(static::API_URL_PATTERN, $this->getCoreMediaHost(), $urlPath);
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
