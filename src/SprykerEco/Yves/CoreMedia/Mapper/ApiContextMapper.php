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
        return (new CoreMediaFragmentRequestTransfer())
            ->fromArray($cmsSlotContentRequestTransfer->getParams(), true)
            ->requireStore()
            ->requireLocale();
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotContentResponseTransfer
     */
    public function mapCoreMediaApiResponseTransferToCmsSlotContentResponseTransfer(
        CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
    ): CmsSlotContentResponseTransfer {
        return (new CmsSlotContentResponseTransfer())->setContent($coreMediaApiResponseTransfer->getData());
    }
}
