<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Dependency\Guzzle;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface CoreMediaToGuzzleInterface
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param array $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send(RequestInterface $request, array $options = []): ResponseInterface;
}
