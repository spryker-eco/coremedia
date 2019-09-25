<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;

class ApiResponsePreparator implements ApiResponsePreparatorInterface
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
