<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\ApiResponse\Resolver;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Client\CoreMedia\ApiResponse\Parser\PlaceholderParserInterface;
use SprykerEco\Client\CoreMedia\ApiResponse\Replacer\PlaceholderReplacerInterface;

class PlaceholderResolver implements ApiResponseResolverInterface
{
    /**
     * @var \SprykerEco\Client\CoreMedia\ApiResponse\Parser\PlaceholderParserInterface
     */
    protected $placeholderParser;

    /**
     * @var array|\SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface[]
     */
    protected $placeholderPostProcessors;

    /**
     * @var \SprykerEco\Client\CoreMedia\ApiResponse\Replacer\PlaceholderReplacerInterface
     */
    protected $placeholderReplacer;

    /**
     * @param \SprykerEco\Client\CoreMedia\ApiResponse\Parser\PlaceholderParserInterface $placeholderParser
     * @param array $placeholderPostProcessors
     * @param \SprykerEco\Client\CoreMedia\ApiResponse\Replacer\PlaceholderReplacerInterface $placeholderReplacer
     */
    public function __construct(
        PlaceholderParserInterface $placeholderParser,
        array $placeholderPostProcessors,
        PlaceholderReplacerInterface $placeholderReplacer
    ) {
        $this->placeholderParser = $placeholderParser;
        $this->placeholderPostProcessors = $placeholderPostProcessors;
        $this->placeholderReplacer = $placeholderReplacer;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function resolve(
        CoreMediaApiResponseTransfer $coreMediaApiResponseTransfer,
        string $locale
    ): CoreMediaApiResponseTransfer {
        if (!$coreMediaApiResponseTransfer->getData()) {
            return $coreMediaApiResponseTransfer;
        }

        $coreMediaPlaceholderTransfers = $this->placeholderParser->parse($coreMediaApiResponseTransfer->getData());

        if (!$coreMediaPlaceholderTransfers) {
            return $coreMediaApiResponseTransfer;
        }

        foreach ($coreMediaPlaceholderTransfers as $coreMediaPlaceholderTransfer) {
            $coreMediaPlaceholderTransfer = $this->executeCoreMediaPlaceholderPostProcessor(
                $coreMediaPlaceholderTransfer,
                $locale
            );
            $coreMediaApiResponseTransfer->setData(
                $this->placeholderReplacer->replace(
                    $coreMediaApiResponseTransfer->getData(),
                    $coreMediaPlaceholderTransfer
                )
            );
        }

        return $coreMediaApiResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer
     */
    protected function executeCoreMediaPlaceholderPostProcessor(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): CoreMediaPlaceholderTransfer {
        foreach ($this->placeholderPostProcessors as $placeholderPostProcessor) {
            if ($placeholderPostProcessor->isApplicable($coreMediaPlaceholderTransfer)) {
                return $placeholderPostProcessor->addReplacement($coreMediaPlaceholderTransfer, $locale);
            }
        }

        return $coreMediaPlaceholderTransfer;
    }
}
