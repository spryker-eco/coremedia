<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\ApiResponse;

use Generated\Shared\Transfer\CoremediaApiResponseTransfer;

class ApiResponsePreparator implements ApiResponsePreparatorInterface
{
    /**
     * @var \SprykerEco\Yves\Coremedia\ApiResponse\Resolver\ApiResponseResolverInterface[]
     */
    protected $apiResponseResolvers;

    /**
     * @param \SprykerEco\Yves\Coremedia\ApiResponse\Resolver\ApiResponseResolverInterface[] $apiResponseResolvers
     */
    public function __construct(array $apiResponseResolvers)
    {
        $this->apiResponseResolvers = $apiResponseResolvers;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaApiResponseTransfer $coreMediaApiResponseTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    public function prepare(
        CoremediaApiResponseTransfer $coreMediaApiResponseTransfer,
        string $locale
    ): CoremediaApiResponseTransfer {
        foreach ($this->apiResponseResolvers as $apiResponseResolver) {
            $coreMediaApiResponseTransfer = $apiResponseResolver->resolve($coreMediaApiResponseTransfer, $locale);
        }

        return $coreMediaApiResponseTransfer;
    }
}
