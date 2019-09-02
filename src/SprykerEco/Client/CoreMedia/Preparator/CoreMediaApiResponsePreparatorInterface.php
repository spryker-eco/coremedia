<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Preparator;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;

interface CoreMediaApiResponsePreparatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function prepare(
        CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
    ): CoreMediaApiResponseTransfer;
}
