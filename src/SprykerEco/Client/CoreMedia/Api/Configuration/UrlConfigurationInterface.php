<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Api\Configuration;

use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;

interface UrlConfigurationInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return string
     */
    public function getDocumentFragmentApiUrl(
        CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): string;
}
