<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Dependency\Guzzle;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CoreMediaToGuzzleBridge implements CoreMediaToGuzzleInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @param \GuzzleHttp\Client $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param array $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        return $this->client->send($request, $options);
    }
}
