<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\Plugin\ShopCmsSlot;

use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;
use Generated\Shared\Transfer\CmsSlotDataTransfer;
use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface;

/**
 * @method \SprykerEco\Client\CoreMedia\CoreMediaClient getClient()
 * @method \SprykerEco\Yves\CoreMedia\CoreMediaFactory getFactory()
 */
class CoreMediaCmsSlotContentPlugin extends AbstractPlugin implements CmsSlotContentPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotDataTransfer
     */
    public function getSlotContent(CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer): CmsSlotDataTransfer
    {
        $cmsSlotDataTransfer = new CmsSlotDataTransfer();

        $coreMediaFragmentRequestTransfer = $this->getCoreMediaFragmentRequestTransfer($cmsSlotContentRequestTransfer);

        $coreMediaApiResponseTransfer = $this->getClient()->getDocumentFragment($coreMediaFragmentRequestTransfer);
        $coreMediaApiResponseTransfer = $this->getFactory()->createApiResponse()->prepare(
            $coreMediaApiResponseTransfer,
            $coreMediaFragmentRequestTransfer->getLocale()
        );

        return $cmsSlotDataTransfer->setContent(
            $this->getContentFromCoreMediaApiResponseTransfer($coreMediaApiResponseTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
     *
     * @return string
     */
    protected function getContentFromCoreMediaApiResponseTransfer(
        CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
    ): string {
        if (!$coreMediaApiResponseTransfer->getIsSuccessful()) {
            return '';
        }

        return $coreMediaApiResponseTransfer->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer
     */
    protected function getCoreMediaFragmentRequestTransfer(
        CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
    ): CoreMediaFragmentRequestTransfer {
        return $this->getFactory()
            ->createRequestMapper()
            ->mapCmsSlotContentRequestToCoreMediaFragmentRequest($cmsSlotContentRequestTransfer);
    }
}
