<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\Mapper;

use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use SprykerEco\Client\CoreMedia\Api\Exception\MissingRequestParameterException;

class CoreMediaMapper implements CoreMediaMapperInterface
{
    protected const PATTERN_MISSING_REQUEST_PARAMETER_EXCEPTION = 'The "%s" param is missing in the request to CoreMedia.';

    /**
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @throws \SprykerEco\Client\CoreMedia\Api\Exception\MissingRequestParameterException
     *
     * @return \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer
     */
    public function mapCmsSlotContentRequestToCoreMediaFragmentRequest(
        CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
    ): CoreMediaFragmentRequestTransfer {
        $requestParameters = $cmsSlotContentRequestTransfer->getParams();

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

        $coreMediaFragmentRequestTransfer = new CoreMediaFragmentRequestTransfer();

        /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
        $storeTransfer = $requestParameters[CoreMediaFragmentRequestTransfer::STORE];
        $coreMediaFragmentRequestTransfer->setStore($storeTransfer->getName());
        $coreMediaFragmentRequestTransfer->setLocale($requestParameters[CoreMediaFragmentRequestTransfer::LOCALE]);

        if (isset($requestParameters[CoreMediaFragmentRequestTransfer::CATEGORY_ID])) {
            $coreMediaFragmentRequestTransfer->setCategoryId($requestParameters[CoreMediaFragmentRequestTransfer::CATEGORY_ID]);
        }

        if (isset($requestParameters[CoreMediaFragmentRequestTransfer::PRODUCT_ID])) {
            $coreMediaFragmentRequestTransfer->setProductId($requestParameters[CoreMediaFragmentRequestTransfer::PRODUCT_ID]);
        }

        if (isset($requestParameters[CoreMediaFragmentRequestTransfer::PAGE_ID])) {
            $coreMediaFragmentRequestTransfer->setPageId($requestParameters[CoreMediaFragmentRequestTransfer::PAGE_ID]);
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
