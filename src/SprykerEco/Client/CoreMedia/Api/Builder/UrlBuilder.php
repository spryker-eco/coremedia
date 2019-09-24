<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Api\Builder;

use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use SprykerEco\Client\CoreMedia\Api\Exception\UrlBuilderException;
use SprykerEco\Client\CoreMedia\CoreMediaConfig;

class UrlBuilder implements UrlBuilderInterface
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
    public function buildDocumentFragmentApiUrl(
        CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): string {
        $queryParamString = $this->getQueryStringFromCoreMediaFragmentRequestTransfer(
            $coreMediaFragmentRequestTransfer
        );

        return $this->getCoreMediaHost() . $this->config->getFragmentBasePath() . $queryParamString;
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
            implode(';', array_filter($queryParams))
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
     * @throws \SprykerEco\Client\CoreMedia\Api\Exception\UrlBuilderException
     *
     * @return string
     */
    protected function getStoreIdByStoreName(string $storeName): string
    {
        $applicationStoreMapping = $this->config->getApplicationStoreMapping();

        if (!isset($applicationStoreMapping[$storeName])) {
            throw new UrlBuilderException(
                sprintf('Cannot find storeId by store name "%s" in application store mapping.', $storeName)
            );
        }

        return $applicationStoreMapping[$storeName];
    }

    /**
     * @param string $storeId
     * @param string $localeName
     *
     * @throws \SprykerEco\Client\CoreMedia\Api\Exception\UrlBuilderException
     *
     * @return string
     */
    protected function getLocaleByStoreIdAndLocaleName(string $storeId, string $localeName): string
    {
        $applicationStoreLocaleMapping = $this->config->getApplicationStoreLocaleMapping();

        if (!isset($applicationStoreLocaleMapping[$storeId])) {
            throw new UrlBuilderException(
                sprintf('Not defined storeId "%s" in application store locale mapping.', $storeId)
            );
        }

        if (!isset($applicationStoreLocaleMapping[$storeId][$localeName])) {
            throw new UrlBuilderException(
                sprintf(
                    'Cannot find locale by locale name "%s" for storeId "%s" in application store locale mapping.',
                    $localeName,
                    $storeId
                )
            );
        }

        return $applicationStoreLocaleMapping[$storeId][$localeName];
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

        if (is_scalar($value)) {
            return sprintf(static::HTTP_QUERY_KEY_VALUE_PATTERN, $key, rawurlencode($value));
        }

        return '';
    }

    /**
     * @throws \SprykerEco\Client\CoreMedia\Api\Exception\UrlBuilderException
     *
     * @return string
     */
    protected function getCoreMediaHost(): string
    {
        $coreMediaHost = $this->config->getCoreMediaHost();

        if (!$coreMediaHost) {
            throw new UrlBuilderException('Please specify the CoreMedia host in configuration.');
        }

        return $coreMediaHost;
    }
}
