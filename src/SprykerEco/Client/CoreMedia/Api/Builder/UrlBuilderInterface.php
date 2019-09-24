<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Api\Builder;

use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;

interface UrlBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return string
     */
    public function buildDocumentFragmentApiUrl(
        CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): string;
}
