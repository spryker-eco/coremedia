<?php

namespace SprykerEco\Client\CoreMedia\Preparator\Resolver;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;

interface CoreMediaApiResponseResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function resolve(CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer): CoreMediaApiResponseTransfer;
}
