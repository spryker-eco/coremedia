<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Client\CoreMedia\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CoreMediaFragmentRequestBuilder;
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
}
