<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Stub;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use SprykerEco\Client\CoreMedia\Api\CoreMediaApiClientInterface;
use SprykerEco\Client\CoreMedia\Preparator\CoreMediaApiResponsePreparatorInterface;

class CoreMediaStub implements CoreMediaStubInterface
{
    /**
     * @var \SprykerEco\Client\CoreMedia\Api\CoreMediaApiClientInterface
     */
    protected $coreMediaApiClient;

    /**
     * @var \SprykerEco\Client\CoreMedia\Preparator\CoreMediaApiResponsePreparatorInterface
     */
    protected $coreMediaApiResponsePreparator;

    /**
     * @param \SprykerEco\Client\CoreMedia\Api\CoreMediaApiClientInterface $coreMediaApiClient
     * @param \SprykerEco\Client\CoreMedia\Preparator\CoreMediaApiResponsePreparatorInterface $coreMediaApiResponsePreparator
     */
    public function __construct(
        CoreMediaApiClientInterface $coreMediaApiClient,
        CoreMediaApiResponsePreparatorInterface $coreMediaApiResponsePreparator
    ) {
        $this->coreMediaApiClient = $coreMediaApiClient;
        $this->coreMediaApiResponsePreparator = $coreMediaApiResponsePreparator;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function getDocumentFragment(
        CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): CoreMediaApiResponseTransfer {
        $coreMediaApiResponseTransfer = $this->coreMediaApiClient->getDocumentFragment($coreMediaFragmentRequestTransfer);

        return $this->coreMediaApiResponsePreparator->prepare($coreMediaApiResponseTransfer, $coreMediaFragmentRequestTransfer->getLocale());
    }
}
