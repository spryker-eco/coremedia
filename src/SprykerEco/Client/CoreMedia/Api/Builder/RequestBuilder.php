<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\CoreMedia\Api\Builder;

use GuzzleHttp\Psr7\Request as Psr7Request;
use Psr\Http\Message\RequestInterface;

class RequestBuilder implements RequestBuilderInterface
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
    ): RequestInterface {
        return new Psr7Request(
            $requestMethod,
            $requestUrl
        );
    }
}
