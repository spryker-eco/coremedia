<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\CoreMedia\Api\Executor;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Psr\Http\Message\RequestInterface;

interface RequestExecutorInterface
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function execute(RequestInterface $request): CoreMediaApiResponseTransfer;
}
