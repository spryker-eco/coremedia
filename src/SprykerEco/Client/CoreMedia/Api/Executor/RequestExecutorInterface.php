<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Api\Executor;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Psr\Http\Message\RequestInterface;
use SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfigurationInterface;

interface RequestExecutorInterface
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfigurationInterface $urlConfiguration
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function execute(
        RequestInterface $request,
        UrlConfigurationInterface $urlConfiguration
    ): CoreMediaApiResponseTransfer;
}
