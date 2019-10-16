<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Coremedia\Api\Builder;

use Generated\Shared\Transfer\CoremediaFragmentRequestTransfer;

interface UrlBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return string
     */
    public function buildDocumentFragmentApiUrl(
        CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): string;
}
