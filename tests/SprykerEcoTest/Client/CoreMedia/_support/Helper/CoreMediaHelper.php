<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Client\CoreMedia\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CategoryNodeStorageBuilder;
use Generated\Shared\DataBuilder\CoreMediaApiResponseBuilder;
use Generated\Shared\DataBuilder\CoreMediaFragmentRequestBuilder;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;

class CoreMediaHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer
     */
    public function getCoreMediaFragmentRequestTransfer(array $seedData = []): CoreMediaFragmentRequestTransfer
    {
        return (new CoreMediaFragmentRequestBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function getCoreMediaApiResponseTransfer(array $seedData = []): CoreMediaApiResponseTransfer
    {
        return (new CoreMediaApiResponseBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeStorageTransfer(array $seedData = []): CategoryNodeStorageTransfer
    {
        return (new CategoryNodeStorageBuilder($seedData))->build();
    }
}
