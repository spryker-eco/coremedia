<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\Plugin\ShopCmsSlot;

use Generated\Shared\Transfer\CmsSlotDataTransfer;
use Generated\Shared\Transfer\CmsSlotRequestTransfer;
use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerEco\Client\CoreMedia\Api\Exception\MissingRequestParameterException;
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface;

/**
 * @method \SprykerEco\Client\CoreMedia\CoreMediaClient getClient()
 */
class CoreMediaCmsSlotContentPlugin extends AbstractPlugin implements CmsSlotContentPluginInterface
{
    protected const CORE_MEDIA_API_RESPONSE_STATUS_FAIL = false;
    protected const PATTERN_MISSING_REQUEST_PARAMETER_EXCEPTION = 'The "%s" param is missing in the request to CoreMedia.';

    /**
     * @param \Generated\Shared\Transfer\CmsSlotRequestTransfer $cmsSlotRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotDataTransfer
     */
    public function getSlotContent(CmsSlotRequestTransfer $cmsSlotRequestTransfer): CmsSlotDataTransfer
    {
        $cmsSlotDataTransfer = new CmsSlotDataTransfer();

        $coreMediaApiResponseTransfer = $this->getClient()->getDocumentFragment(
            $this->getCoreMediaFragmentRequestTransfer($cmsSlotRequestTransfer)
        );

        return $cmsSlotDataTransfer->setContent(
            $this->getContentFromCoreMediaApiResponseTransfer($coreMediaApiResponseTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
     *
     * @return string
     */
    protected function getContentFromCoreMediaApiResponseTransfer(CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer): string
    {
        if ($coreMediaApiResponseTransfer->getStatus() === static::CORE_MEDIA_API_RESPONSE_STATUS_FAIL) {
            return '';
        }

        return $coreMediaApiResponseTransfer->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotRequestTransfer $cmsSlotRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer
     */
    protected function getCoreMediaFragmentRequestTransfer(
        CmsSlotRequestTransfer $cmsSlotRequestTransfer
    ): CoreMediaFragmentRequestTransfer {
        $coreMediaFragmentRequestTransfer = new CoreMediaFragmentRequestTransfer();
        $coreMediaFragmentRequestTransfer = $this->mapCoreMediaFragmentRequestTransferMandatoryProperties(
            $coreMediaFragmentRequestTransfer,
            $cmsSlotRequestTransfer
        );
        $coreMediaFragmentRequestTransfer = $this->mapCoreMediaFragmentRequestTransferOptionalProperties(
            $coreMediaFragmentRequestTransfer,
            $cmsSlotRequestTransfer
        );

        return $coreMediaFragmentRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     * @param \Generated\Shared\Transfer\CmsSlotRequestTransfer $cmsSlotRequestTransfer
     *
     * @throws \SprykerEco\Client\CoreMedia\Api\Exception\MissingRequestParameterException
     *
     * @return \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer
     */
    protected function mapCoreMediaFragmentRequestTransferMandatoryProperties(
        CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer,
        CmsSlotRequestTransfer $cmsSlotRequestTransfer
    ): CoreMediaFragmentRequestTransfer {
        $requestParameters = $cmsSlotRequestTransfer->getParams();

        if (!isset($requestParameters[CoreMediaFragmentRequestTransfer::STORE])) {
            throw new MissingRequestParameterException(
                sprintf(static::PATTERN_MISSING_REQUEST_PARAMETER_EXCEPTION, CoreMediaFragmentRequestTransfer::STORE)
            );
        }

        if (!isset($requestParameters[CoreMediaFragmentRequestTransfer::LOCALE])) {
            throw new MissingRequestParameterException(
                sprintf(static::PATTERN_MISSING_REQUEST_PARAMETER_EXCEPTION, CoreMediaFragmentRequestTransfer::LOCALE)
            );
        }

        /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
        $storeTransfer = $requestParameters[CoreMediaFragmentRequestTransfer::STORE];
        $coreMediaFragmentRequestTransfer->setStore($storeTransfer->getName());
        $coreMediaFragmentRequestTransfer->setLocale($requestParameters[CoreMediaFragmentRequestTransfer::LOCALE]);

        return $coreMediaFragmentRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     * @param \Generated\Shared\Transfer\CmsSlotRequestTransfer $cmsSlotRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer
     */
    protected function mapCoreMediaFragmentRequestTransferOptionalProperties(
        CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer,
        CmsSlotRequestTransfer $cmsSlotRequestTransfer
    ): CoreMediaFragmentRequestTransfer {
        $requestParameters = $cmsSlotRequestTransfer->getParams();
        $mainParameter = null;

        if (isset($requestParameters[CoreMediaFragmentRequestTransfer::PAGE_ID])) {
            $coreMediaFragmentRequestTransfer->setPageId($requestParameters[CoreMediaFragmentRequestTransfer::PAGE_ID]);
            $mainParameter = CoreMediaFragmentRequestTransfer::PAGE_ID;
        }

        if (isset($requestParameters[CoreMediaFragmentRequestTransfer::PRODUCT_ID]) && !$mainParameter) {
            $coreMediaFragmentRequestTransfer->setProductId($requestParameters[CoreMediaFragmentRequestTransfer::PRODUCT_ID]);
            $mainParameter = CoreMediaFragmentRequestTransfer::PRODUCT_ID;
        }

        if (isset($requestParameters[CoreMediaFragmentRequestTransfer::CATEGORY_ID]) && !$mainParameter) {
            $coreMediaFragmentRequestTransfer->setCategoryId($requestParameters[CoreMediaFragmentRequestTransfer::CATEGORY_ID]);
            $mainParameter = CoreMediaFragmentRequestTransfer::CATEGORY_ID;
        }

        if ($cmsSlotRequestTransfer->getCmsSlotKey() && !$mainParameter) {
            $coreMediaFragmentRequestTransfer->setExternalRef($cmsSlotRequestTransfer->getCmsSlotKey());
        }

        if (isset($requestParameters[CoreMediaFragmentRequestTransfer::PLACEMENT])) {
            $coreMediaFragmentRequestTransfer->setPlacement($requestParameters[CoreMediaFragmentRequestTransfer::PLACEMENT]);
        }

        if (isset($requestParameters[CoreMediaFragmentRequestTransfer::VIEW])) {
            $coreMediaFragmentRequestTransfer->setView($requestParameters[CoreMediaFragmentRequestTransfer::VIEW]);
        }

        return $coreMediaFragmentRequestTransfer;
    }
}
