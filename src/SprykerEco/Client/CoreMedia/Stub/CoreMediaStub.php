<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Stub;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use SprykerEco\Client\CoreMedia\Api\ApiClientInterface;
use SprykerEco\Client\CoreMedia\ApiResponse\ApiResponseInterface;

class CoreMediaStub implements CoreMediaStubInterface
{
    /**
     * @var \SprykerEco\Client\CoreMedia\Api\ApiClientInterface
     */
    protected $apiClient;

    /**
     * @var \SprykerEco\Client\CoreMedia\ApiResponse\ApiResponseInterface
     */
    protected $apiResponse;

    /**
     * @param \SprykerEco\Client\CoreMedia\Api\ApiClientInterface $apiClient
     * @param \SprykerEco\Client\CoreMedia\ApiResponse\ApiResponseInterface $apiResponse
     */
    public function __construct(
        ApiClientInterface $apiClient,
        ApiResponseInterface $apiResponse
    ) {
        $this->apiClient = $apiClient;
        $this->apiResponse = $apiResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function getDocumentFragment(
        CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): CoreMediaApiResponseTransfer {
        $coreMediaApiResponseTransfer = $this->apiClient->getDocumentFragment($coreMediaFragmentRequestTransfer);

        return $this->apiResponse->prepare($coreMediaApiResponseTransfer, $coreMediaFragmentRequestTransfer->getLocale());
    }
}
