<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\CoreMedia;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;

interface CoreMediaClientInterface
{
    /**
     * Specification:
     * - Makes a request from the CoreMediaFragmentRequestTransfer.
     * - Sends the request to CoreMedia REST API server to receive a document fragment.
     * - Returns a string representation of the current requested fragment.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function getDocumentFragment(
        CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): CoreMediaApiResponseTransfer;
}
