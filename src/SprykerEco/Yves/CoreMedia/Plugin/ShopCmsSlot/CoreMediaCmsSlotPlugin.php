<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\Plugin\ShopCmsSlot;

use Generated\Shared\Transfer\CmsSlotDataTransfer;
use Generated\Shared\Transfer\CmsSlotRequestTransfer;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotPluginInterface;

/**
 * @method \SprykerEco\Client\CoreMedia\CoreMediaClient getClient()
 */
class CoreMediaCmsSlotPlugin extends AbstractPlugin implements CmsSlotPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotRequestTransfer $cmsSlotRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotDataTransfer
     */
    public function getSlotContent(CmsSlotRequestTransfer $cmsSlotRequestTransfer): CmsSlotDataTransfer
    {
        $cmsSlotDataTransfer = new CmsSlotDataTransfer();

        $coreMediaApiResponseTransfer = $this->getClient()->getDocumentFragment(
            $this->getCoreMediaFragmentRequestTransfer($cmsSlotRequestTransfer)
        );
        $cmsSlotDataTransfer->setFragmentData($coreMediaApiResponseTransfer->getData());

        return $cmsSlotDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotRequestTransfer $cmsSlotRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer
     */
    protected function getCoreMediaFragmentRequestTransfer(
        CmsSlotRequestTransfer $cmsSlotRequestTransfer
    ): CoreMediaFragmentRequestTransfer {
        $requestParameters = $cmsSlotRequestTransfer->getParams();

        $coreMediaFragmentRequestTransfer = new CoreMediaFragmentRequestTransfer();
        $coreMediaFragmentRequestTransfer->setExternalRef($cmsSlotRequestTransfer->getKey());

        /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
        $storeTransfer = $requestParameters['store'];
        $coreMediaFragmentRequestTransfer->setStore($storeTransfer->getName());
        $coreMediaFragmentRequestTransfer->setLocale($requestParameters['locale']);

        if (isset($requestParameters['productId'])) {
            $coreMediaFragmentRequestTransfer->setProductId($requestParameters['productId']);
        }

        if (!$coreMediaFragmentRequestTransfer->getProductId() && isset($requestParameters['categoryId'])) {
            $coreMediaFragmentRequestTransfer->setCategoryId($requestParameters['categoryId']);
        }

        $coreMediaFragmentRequestTransfer->setPageId($requestParameters['pageId']);
        $coreMediaFragmentRequestTransfer->setPlacement($requestParameters['placement']);
        $coreMediaFragmentRequestTransfer->setView($requestParameters['view']);

        return $coreMediaFragmentRequestTransfer;
    }
}
