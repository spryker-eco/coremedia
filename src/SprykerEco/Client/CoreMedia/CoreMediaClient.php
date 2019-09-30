<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerEco\Client\CoreMedia\CoreMediaFactory getFactory()
 */
class CoreMediaClient extends AbstractClient implements CoreMediaClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function getDocumentFragment(
        CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): CoreMediaApiResponseTransfer {
        return $this->getFactory()
            ->createApiClient()
            ->getDocumentFragment($coreMediaFragmentRequestTransfer);
    }
}
