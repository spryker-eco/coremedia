<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Stub;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use SprykerEco\Client\CoreMedia\Api\ApiClientInterface;
use SprykerEco\Client\CoreMedia\Preparator\ApiResponsePreparatorInterface;

class CoreMediaStub implements CoreMediaStubInterface
{
    /**
     * @var \SprykerEco\Client\CoreMedia\Api\ApiClientInterface
     */
    protected $apiClient;

    /**
     * @var \SprykerEco\Client\CoreMedia\Preparator\ApiResponsePreparatorInterface
     */
    protected $apiResponsePreparator;

    /**
     * @param \SprykerEco\Client\CoreMedia\Api\ApiClientInterface $apiClient
     * @param \SprykerEco\Client\CoreMedia\Preparator\ApiResponsePreparatorInterface $apiResponsePreparator
     */
    public function __construct(
        ApiClientInterface $apiClient,
        ApiResponsePreparatorInterface $apiResponsePreparator
    ) {
        $this->apiClient = $apiClient;
        $this->apiResponsePreparator = $apiResponsePreparator;
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

        return $this->apiResponsePreparator->prepare($coreMediaApiResponseTransfer, $coreMediaFragmentRequestTransfer->getLocale());
    }
}
