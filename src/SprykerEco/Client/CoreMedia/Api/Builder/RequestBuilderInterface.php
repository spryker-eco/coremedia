<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Api\Builder;

use Psr\Http\Message\RequestInterface;

interface RequestBuilderInterface
{
    /**
     * @param string $requestMethod
     * @param string $requestUrl
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    public function buildPsrRequest(
        string $requestMethod,
        string $requestUrl
    ): RequestInterface;
}
