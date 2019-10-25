<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\Mapper;

use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;
use Generated\Shared\Transfer\CmsSlotContentResponseTransfer;
use Generated\Shared\Transfer\CoremediaApiResponseTransfer;
use Generated\Shared\Transfer\CoremediaFragmentRequestTransfer;

interface ApiContextMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CoremediaFragmentRequestTransfer
     */
    public function mapCmsSlotContentRequestToCoremediaFragmentRequest(
        CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
    ): CoremediaFragmentRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\CoremediaApiResponseTransfer $coreMediaApiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotContentResponseTransfer
     */
    public function mapCoremediaApiResponseTransferToCmsSlotContentResponseTransfer(
        CoremediaApiResponseTransfer $coreMediaApiResponseTransfer
    ): CmsSlotContentResponseTransfer;
}
