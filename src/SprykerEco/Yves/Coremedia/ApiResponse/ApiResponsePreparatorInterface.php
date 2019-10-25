<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\ApiResponse;

use Generated\Shared\Transfer\CoremediaApiResponseTransfer;

interface ApiResponsePreparatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoremediaApiResponseTransfer $coreMediaApiResponseTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    public function prepare(
        CoremediaApiResponseTransfer $coreMediaApiResponseTransfer,
        string $locale
    ): CoremediaApiResponseTransfer;
}
