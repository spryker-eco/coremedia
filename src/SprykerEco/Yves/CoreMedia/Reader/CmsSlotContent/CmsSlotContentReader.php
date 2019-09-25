<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\Reader\CmsSlotContent;

use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;
use Generated\Shared\Transfer\CmsSlotContentResponseTransfer;
use SprykerEco\Client\CoreMedia\CoreMediaClientInterface;
use SprykerEco\Yves\CoreMedia\ApiResponse\ApiResponsePreparatorInterface;
use SprykerEco\Yves\CoreMedia\Mapper\ApiContextMapperInterface;

class CmsSlotContentReader implements CmsSlotContentReaderInterface
{
    /**
     * @var \SprykerEco\Client\CoreMedia\CoreMediaClientInterface
     */
    protected $coreMediaClient;

    /**
     * @var \SprykerEco\Yves\CoreMedia\Mapper\ApiContextMapperInterface
     */
    protected $apiContextMapper;

    /**
     * @var \SprykerEco\Yves\CoreMedia\ApiResponse\ApiResponsePreparatorInterface
     */
    protected $apiResponsePreparator;

    /**
     * @param \SprykerEco\Client\CoreMedia\CoreMediaClientInterface $coreMediaClient
     * @param \SprykerEco\Yves\CoreMedia\Mapper\ApiContextMapperInterface $apiContextMapper
     * @param \SprykerEco\Yves\CoreMedia\ApiResponse\ApiResponsePreparatorInterface $apiResponsePreparator
     */
    public function __construct(
        CoreMediaClientInterface $coreMediaClient,
        ApiContextMapperInterface $apiContextMapper,
        ApiResponsePreparatorInterface $apiResponsePreparator
    ) {
        $this->coreMediaClient = $coreMediaClient;
        $this->apiContextMapper = $apiContextMapper;
        $this->apiResponsePreparator = $apiResponsePreparator;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotContentResponseTransfer
     */
    public function getDocumentFragment(
        CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
    ): CmsSlotContentResponseTransfer {
        $coreMediaFragmentRequestTransfer = $this->apiContextMapper->mapCmsSlotContentRequestToCoreMediaFragmentRequest(
            $cmsSlotContentRequestTransfer
        );
        $coreMediaApiResponseTransfer = $this->coreMediaClient->getDocumentFragment($coreMediaFragmentRequestTransfer);

        if (!$coreMediaApiResponseTransfer->getIsSuccessful()) {
            return (new CmsSlotContentResponseTransfer())->setContent('');
        }

        $coreMediaApiResponseTransfer = $this->apiResponsePreparator->prepare(
            $coreMediaApiResponseTransfer,
            $coreMediaFragmentRequestTransfer->getLocale()
        );

        return $this->apiContextMapper
            ->mapCoreMediaApiResponseTransferToCmsSlotContentResponseTransfer($coreMediaApiResponseTransfer);
    }
}
