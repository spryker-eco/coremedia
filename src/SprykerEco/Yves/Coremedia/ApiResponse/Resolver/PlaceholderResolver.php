<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\ApiResponse\Resolver;

use Generated\Shared\Transfer\CoremediaApiResponseTransfer;
use SprykerEco\Yves\Coremedia\ApiResponse\Parser\PlaceholderParserInterface;
use SprykerEco\Yves\Coremedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface;
use SprykerEco\Yves\Coremedia\ApiResponse\Replacer\PlaceholderReplacerInterface;

class PlaceholderResolver implements ApiResponseResolverInterface
{
    /**
     * @var \SprykerEco\Yves\Coremedia\ApiResponse\Parser\PlaceholderParserInterface
     */
    protected $placeholderParser;

    /**
     * @var \SprykerEco\Yves\Coremedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface
     */
    protected $placeholderPostProcessor;

    /**
     * @var \SprykerEco\Yves\Coremedia\ApiResponse\Replacer\PlaceholderReplacerInterface
     */
    protected $placeholderReplacer;

    /**
     * @param \SprykerEco\Yves\Coremedia\ApiResponse\Parser\PlaceholderParserInterface $placeholderParser
     * @param \SprykerEco\Yves\Coremedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface $placeholderPostProcessor
     * @param \SprykerEco\Yves\Coremedia\ApiResponse\Replacer\PlaceholderReplacerInterface $placeholderReplacer
     */
    public function __construct(
        PlaceholderParserInterface $placeholderParser,
        PlaceholderPostProcessorInterface $placeholderPostProcessor,
        PlaceholderReplacerInterface $placeholderReplacer
    ) {
        $this->placeholderParser = $placeholderParser;
        $this->placeholderPostProcessor = $placeholderPostProcessor;
        $this->placeholderReplacer = $placeholderReplacer;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaApiResponseTransfer $coreMediaApiResponseTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    public function resolve(
        CoremediaApiResponseTransfer $coreMediaApiResponseTransfer,
        string $locale
    ): CoremediaApiResponseTransfer {
        $coreMediaPlaceholderTransfers = $this->placeholderParser->parse($coreMediaApiResponseTransfer->getData());

        if (!$coreMediaPlaceholderTransfers) {
            return $coreMediaApiResponseTransfer;
        }

        foreach ($coreMediaPlaceholderTransfers as $coreMediaPlaceholderTransfer) {
            $coreMediaPlaceholderTransfer = $this->placeholderPostProcessor->addReplacement(
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
}
