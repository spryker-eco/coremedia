<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;

class ApiResponse implements ApiResponseInterface
{
    /**
     * @var \SprykerEco\Yves\CoreMedia\ApiResponse\Resolver\ApiResponseResolverInterface[]
     */
    protected $apiResponseResolvers;

    /**
     * @param \SprykerEco\Yves\CoreMedia\ApiResponse\Resolver\ApiResponseResolverInterface[] $apiResponseResolvers
     */
    public function __construct(array $apiResponseResolvers)
    {
        $this->apiResponseResolvers = $apiResponseResolvers;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function prepare(
        CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer,
        string $locale
    ): CoreMediaApiResponseTransfer {
        foreach ($this->apiResponseResolvers as $apiResponseResolver) {
            $coreMediaApiResponseTransfer = $apiResponseResolver->resolve($coreMediaApiResponseTransfer, $locale);
        }

        return $coreMediaApiResponseTransfer;
    }
}
