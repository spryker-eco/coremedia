<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Coremedia;

use Generated\Shared\Transfer\CoremediaApiResponseTransfer;
use Generated\Shared\Transfer\CoremediaFragmentRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerEco\Client\Coremedia\CoremediaFactory getFactory()
 */
class CoremediaClient extends AbstractClient implements CoremediaClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    public function getDocumentFragment(
        CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): CoremediaApiResponseTransfer {
        return $this->getFactory()
            ->createApiClient()
            ->getDocumentFragment($coreMediaFragmentRequestTransfer);
    }
}
