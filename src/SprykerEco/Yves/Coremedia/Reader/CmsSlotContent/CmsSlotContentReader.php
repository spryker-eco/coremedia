<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\Reader\CmsSlotContent;

use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;
use Generated\Shared\Transfer\CmsSlotContentResponseTransfer;
use SprykerEco\Client\Coremedia\CoremediaClientInterface;
use SprykerEco\Yves\Coremedia\ApiResponse\ApiResponsePreparatorInterface;
use SprykerEco\Yves\Coremedia\Mapper\ApiContextMapperInterface;

class CmsSlotContentReader implements CmsSlotContentReaderInterface
{
    /**
     * @var \SprykerEco\Client\Coremedia\CoremediaClientInterface
     */
    protected $coreMediaClient;

    /**
     * @var \SprykerEco\Yves\Coremedia\Mapper\ApiContextMapperInterface
     */
    protected $apiContextMapper;

    /**
     * @var \SprykerEco\Yves\Coremedia\ApiResponse\ApiResponsePreparatorInterface
     */
    protected $apiResponsePreparator;

    /**
     * @param \SprykerEco\Client\Coremedia\CoremediaClientInterface $coreMediaClient
     * @param \SprykerEco\Yves\Coremedia\Mapper\ApiContextMapperInterface $apiContextMapper
     * @param \SprykerEco\Yves\Coremedia\ApiResponse\ApiResponsePreparatorInterface $apiResponsePreparator
     */
    public function __construct(
        CoremediaClientInterface $coreMediaClient,
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
        $coreMediaFragmentRequestTransfer = $this->apiContextMapper->mapCmsSlotContentRequestToCoremediaFragmentRequest(
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
            ->mapCoremediaApiResponseTransferToCmsSlotContentResponseTransfer($coreMediaApiResponseTransfer);
    }
}
