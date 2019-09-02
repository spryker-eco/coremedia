<?php

namespace SprykerEco\Client\CoreMedia\Preparator\Resolver;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use SprykerEco\Client\CoreMedia\Preparator\Parser\CoreMediaPlaceholderParserInterface;

class CoreMediaPlaceholderResolver implements CoreMediaApiResponseResolverInterface
{
    protected $coreMediaPlaceholderParser;

    public function __construct(
        CoreMediaPlaceholderParserInterface $coreMediaPlaceholderParser
    ) {
        $this->coreMediaPlaceholderParser = $coreMediaPlaceholderParser;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function resolve(CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer): CoreMediaApiResponseTransfer
    {
        $coreMediaPlaceholderTransfers = $this->coreMediaPlaceholderParser->parse($coreMediaApiResponseTransfer->getData());

        if (!$coreMediaPlaceholderTransfers) {
            return $coreMediaApiResponseTransfer;
        }

        return $coreMediaApiResponseTransfer;
    }
}
