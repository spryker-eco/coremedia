<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Preparator\Resolver;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Client\CoreMedia\Preparator\Parser\CoreMediaPlaceholderParserInterface;

class CoreMediaPlaceholderResolver implements CoreMediaApiResponseResolverInterface
{
    /**
     * @var \SprykerEco\Client\CoreMedia\Preparator\Parser\CoreMediaPlaceholderParserInterface
     */
    protected $coreMediaPlaceholderParser;

    /**
     * @var array|\SprykerEco\Client\CoreMedia\Preparator\PostProcessor\CoreMediaPlaceholderPostProcessorInterface[]
     */
    protected $coreMediaPlaceholderPostProcessors;

    /**
     * @param \SprykerEco\Client\CoreMedia\Preparator\Parser\CoreMediaPlaceholderParserInterface $coreMediaPlaceholderParser
     * @param \SprykerEco\Client\CoreMedia\Preparator\PostProcessor\CoreMediaPlaceholderPostProcessorInterface[] $coreMediaPlaceholderPostProcessors
     */
    public function __construct(
        CoreMediaPlaceholderParserInterface $coreMediaPlaceholderParser,
        array $coreMediaPlaceholderPostProcessors
    ) {
        $this->coreMediaPlaceholderParser = $coreMediaPlaceholderParser;
        $this->coreMediaPlaceholderPostProcessors = $coreMediaPlaceholderPostProcessors;
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

        foreach ($coreMediaPlaceholderTransfers as $coreMediaPlaceholderTransfer) {
            $coreMediaPlaceholderTransfer = $this->executeCoreMediaPlaceholderPostProcessor(
                $coreMediaPlaceholderTransfer
            );
            $coreMediaApiResponseTransfer = $this->replacePlaceholderBodyWithReplacement(
                $coreMediaApiResponseTransfer,
                $coreMediaPlaceholderTransfer
            );
        }

        return $coreMediaApiResponseTransfer;
    }

    protected function replacePlaceholderBodyWithReplacement(
        CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer,
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
    ): CoreMediaApiResponseTransfer {
        if (!$coreMediaPlaceholderTransfer->getPlaceholderReplacement()) {
            return $coreMediaApiResponseTransfer;
        }

        // replace

        return $coreMediaApiResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer
     */
    protected function executeCoreMediaPlaceholderPostProcessor(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
    ): CoreMediaPlaceholderTransfer {
        foreach ($this->coreMediaPlaceholderPostProcessors as $coreMediaPlaceholderPostProcessor) {
            if ($coreMediaPlaceholderPostProcessor->isApplicable($coreMediaPlaceholderTransfer)) {
                return $coreMediaPlaceholderPostProcessor->addReplacement($coreMediaPlaceholderTransfer);
            }
        }

        return $coreMediaPlaceholderTransfer;
    }
}
