<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Api;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;
use SprykerEco\Client\CoreMedia\Api\Builder\RequestBuilderInterface;
use SprykerEco\Client\CoreMedia\Api\Builder\UrlBuilderInterface;
use SprykerEco\Client\CoreMedia\Api\Executor\RequestExecutorInterface;

class ApiClient implements ApiClientInterface
{
    protected const REQUEST_GET_METHOD = 'GET';

    /**
     * @var \SprykerEco\Client\CoreMedia\Api\Builder\RequestBuilderInterface
     */
    protected $requestBuilder;

    /**
     * @var \SprykerEco\Client\CoreMedia\Api\Executor\RequestExecutorInterface
     */
    protected $requestExecutor;

    /**
     * @var \SprykerEco\Client\CoreMedia\Api\Builder\UrlBuilderInterface
     */
    protected $urlBuilder;

    /**
     * @param \SprykerEco\Client\CoreMedia\Api\Builder\RequestBuilderInterface $requestBuilder
     * @param \SprykerEco\Client\CoreMedia\Api\Executor\RequestExecutorInterface $requestExecutor
     * @param \SprykerEco\Client\CoreMedia\Api\Builder\UrlBuilderInterface $urlBuilder
     */
    public function __construct(
        RequestBuilderInterface $requestBuilder,
        RequestExecutorInterface $requestExecutor,
        UrlBuilderInterface $urlBuilder
    ) {
        $this->requestBuilder = $requestBuilder;
        $this->requestExecutor = $requestExecutor;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function getDocumentFragment(
        CoreMediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): CoreMediaApiResponseTransfer {
        $request = $this->requestBuilder->buildRequest(
            static::REQUEST_GET_METHOD,
            $this->urlBuilder->buildDocumentFragmentApiUrl($coreMediaFragmentRequestTransfer)
        );

        return $this->requestExecutor->execute($request);
    }
}
