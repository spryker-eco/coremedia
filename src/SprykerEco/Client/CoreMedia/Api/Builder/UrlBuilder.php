<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\CoreMedia\Api\Builder;

use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfigurationInterface;

class UrlBuilder implements UrlBuilderInterface
{
    protected const HTTP_QUERY_KEY_VALUE_PATTERN = '%s=%s';

    protected const NAME_QUERY_PARAMS = [
        CoreMediaFragmentRequestTransfer::PAGE_ID,
        CoreMediaFragmentRequestTransfer::PRODUCT_ID,
        CoreMediaFragmentRequestTransfer::CATEGORY_ID,
        CoreMediaFragmentRequestTransfer::VIEW,
        CoreMediaFragmentRequestTransfer::PLACEMENT,
    ];

    /**
     * @var \SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfigurationInterface
     */
    protected $urlConfiguration;

    /**
     * @param \SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfigurationInterface $urlConfiguration
     */
    public function __construct(UrlConfigurationInterface $urlConfiguration)
    {
        $this->urlConfiguration = $urlConfiguration;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return string
     */
    public function buildDocumentFragmentApiUrl(
        CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): string {
        $query = $this->transformCoreMediaFragmentRequestTransferPropertiesToQueryString(
            $coreMediaFragmentRequestTransfer
        );

        return $this->urlConfiguration->getCoreMediaHost() . $this->urlConfiguration->getBasePath() . $query;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return string
     */
    protected function transformCoreMediaFragmentRequestTransferPropertiesToQueryString(
        CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): string {
        $store = $this->urlConfiguration->getStore($coreMediaFragmentRequestTransfer->getStore());
        $locale = $this->urlConfiguration->getLocale($store, $coreMediaFragmentRequestTransfer->getLocale());
        $coreMediaFragmentRequestTransferArray = $coreMediaFragmentRequestTransfer->toArray(true, true);
        $queryParams = [];

        foreach ($coreMediaFragmentRequestTransferArray as $key => $value) {
            if (in_array($key, static::NAME_QUERY_PARAMS, true)) {
                $queryParams[] = $this->serializeCoreMediaFragmentRequestTransferProperty($key, $value);
            }
        }

        return sprintf(
            '%s/%s/params;%s',
            $store,
            $locale,
            implode(';', array_filter($queryParams))
        );
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return string
     */
    protected function serializeCoreMediaFragmentRequestTransferProperty(string $key, $value): string
    {
        if (is_bool($value)) {
            return sprintf(static::HTTP_QUERY_KEY_VALUE_PATTERN, $key, var_export($value, true));
        }

        if (is_scalar($value)) {
            return sprintf(static::HTTP_QUERY_KEY_VALUE_PATTERN, $key, $value);
        }

        return '';
    }
}
