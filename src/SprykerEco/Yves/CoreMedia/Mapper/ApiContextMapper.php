<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\Mapper;

use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;
use Generated\Shared\Transfer\CmsSlotContentResponseTransfer;
use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use SprykerEco\Yves\CoreMedia\Exception\MissingRequestParameterException;

class ApiContextMapper implements ApiContextMapperInterface
{
    protected const PATTERN_MISSING_REQUEST_PARAMETER_EXCEPTION = 'The "%s" param is missing in the request to CoreMedia.';

    /**
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @throws \SprykerEco\Yves\CoreMedia\Exception\MissingRequestParameterException
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
        $coreMediaFragmentRequestTransfer->fromArray($requestParameters, true);

        return $coreMediaFragmentRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotContentResponseTransfer
     */
    public function mapCoreMediaApiResponseTransferToCmsSlotContentResponseTransfer(
        CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
    ): CmsSlotContentResponseTransfer {
        $cmsSlotContentResponseTransfer = new CmsSlotContentResponseTransfer();

        return $cmsSlotContentResponseTransfer->setContent(
            $this->getContentFromCoreMediaApiResponseTransfer($coreMediaApiResponseTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
     *
     * @return string
     */
    protected function getContentFromCoreMediaApiResponseTransfer(
        CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
    ): string {
        if (!$coreMediaApiResponseTransfer->getIsSuccessful()) {
            return '';
        }

        return $coreMediaApiResponseTransfer->getData();
    }
}
