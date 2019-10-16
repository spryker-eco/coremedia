<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Coremedia\Api;

use Generated\Shared\Transfer\CoremediaApiResponseTransfer;
use Generated\Shared\Transfer\CoremediaFragmentRequestTransfer;
use SprykerEco\Client\Coremedia\Api\Builder\RequestBuilderInterface;
use SprykerEco\Client\Coremedia\Api\Builder\UrlBuilderInterface;
use SprykerEco\Client\Coremedia\Api\Executor\RequestExecutorInterface;

class ApiClient implements ApiClientInterface
{
    protected const REQUEST_GET_METHOD = 'GET';

    /**
     * @var \SprykerEco\Client\Coremedia\Api\Builder\RequestBuilderInterface
     */
    protected $requestBuilder;

    /**
     * @var \SprykerEco\Client\Coremedia\Api\Executor\RequestExecutorInterface
     */
    protected $requestExecutor;

    /**
     * @var \SprykerEco\Client\Coremedia\Api\Builder\UrlBuilderInterface
     */
    protected $urlBuilder;

    /**
     * @param \SprykerEco\Client\Coremedia\Api\Builder\RequestBuilderInterface $requestBuilder
     * @param \SprykerEco\Client\Coremedia\Api\Executor\RequestExecutorInterface $requestExecutor
     * @param \SprykerEco\Client\Coremedia\Api\Builder\UrlBuilderInterface $urlBuilder
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
     * @param \Generated\Shared\Transfer\CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    public function getDocumentFragment(
        CoremediaFragmentRequestTransfer $coreMediaFragmentRequestTransfer
    ): CoremediaApiResponseTransfer {
        $request = $this->requestBuilder->buildRequest(
            static::REQUEST_GET_METHOD,
            $this->urlBuilder->buildDocumentFragmentApiUrl($coreMediaFragmentRequestTransfer)
        );

        return $this->requestExecutor->execute($request);
    }
}
