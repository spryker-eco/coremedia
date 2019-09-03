<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Preparator;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;

class CoreMediaApiResponsePreparator implements CoreMediaApiResponsePreparatorInterface
{
    /**
     * @var \SprykerEco\Client\CoreMedia\Preparator\Resolver\CoreMediaApiResponseResolverInterface[]
     */
    protected $coreMediaApiResponseResolvers;

    /**
     * @param \SprykerEco\Client\CoreMedia\Preparator\Resolver\CoreMediaApiResponseResolverInterface[] $coreMediaApiResponseResolvers
     */
    public function __construct(array $coreMediaApiResponseResolvers)
    {
        $this->coreMediaApiResponseResolvers = $coreMediaApiResponseResolvers;
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
        foreach ($this->coreMediaApiResponseResolvers as $coreMediaApiResponseResolver) {
            $coreMediaApiResponseTransfer = $coreMediaApiResponseResolver->resolve($coreMediaApiResponseTransfer, $locale);
        }

        return $coreMediaApiResponseTransfer;
    }
}
