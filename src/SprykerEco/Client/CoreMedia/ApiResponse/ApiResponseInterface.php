<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\ApiResponse;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;

interface ApiResponseInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function prepare(
        CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer,
        string $locale
    ): CoreMediaApiResponseTransfer;
}
