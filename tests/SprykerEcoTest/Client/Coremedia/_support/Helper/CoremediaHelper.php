<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Client\Coremedia\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CoremediaFragmentRequestBuilder;
use Generated\Shared\Transfer\CoremediaFragmentRequestTransfer;

class CoremediaHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CoremediaFragmentRequestTransfer
     */
    public function getCoremediaFragmentRequestTransfer(array $seedData = []): CoremediaFragmentRequestTransfer
    {
        return (new CoremediaFragmentRequestBuilder($seedData))->build();
    }
}
