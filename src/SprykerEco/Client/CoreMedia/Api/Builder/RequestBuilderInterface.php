<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
    public function buildRequest(
        string $requestMethod,
        string $requestUrl
    ): RequestInterface;
}
