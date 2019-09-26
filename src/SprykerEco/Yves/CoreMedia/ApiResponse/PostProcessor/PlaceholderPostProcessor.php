<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Yves\CoreMedia\ApiResponse\Executor\IncorrectPlaceholderDataExecutorInterface;
use SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface;

class PlaceholderPostProcessor implements PlaceholderPostProcessorInterface
{
    /**
     * @var \SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface[]
     */
    protected $placeholderReplacementRenderers;

    /**
     * @var \SprykerEco\Yves\CoreMedia\ApiResponse\Executor\IncorrectPlaceholderDataExecutorInterface
     */
    protected $incorrectPlaceholderDataExecutor;

    /**
     * @param \SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface[] $placeholderReplacementRenderers
     * @param \SprykerEco\Yves\CoreMedia\ApiResponse\Executor\IncorrectPlaceholderDataExecutorInterface $incorrectPlaceholderDataExecutor
     */
    public function __construct(
        array $placeholderReplacementRenderers,
        IncorrectPlaceholderDataExecutorInterface $incorrectPlaceholderDataExecutor
    ) {
        $this->placeholderReplacementRenderers = $placeholderReplacementRenderers;
        $this->incorrectPlaceholderDataExecutor = $incorrectPlaceholderDataExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer
     */
    public function addReplacement(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): CoreMediaPlaceholderTransfer {
        $placeholderReplacementRenderer = $this->resolvePlaceholderReplacementRenderer($coreMediaPlaceholderTransfer);

        if (!$placeholderReplacementRenderer) {
            return $coreMediaPlaceholderTransfer;
        }

        $placeholderReplacement = $placeholderReplacementRenderer->getPlaceholderReplacement(
            $coreMediaPlaceholderTransfer,
            $locale
        );

        if (!$placeholderReplacement) {
            $this->incorrectPlaceholderDataExecutor->executeIncorrectPlaceholderData($coreMediaPlaceholderTransfer, $locale);

            return $this->setFallbackPlaceholderReplacement($placeholderReplacementRenderer, $coreMediaPlaceholderTransfer);
        }

        $coreMediaPlaceholderTransfer->setPlaceholderReplacement($placeholderReplacement);

        return $coreMediaPlaceholderTransfer;
    }

    /**
     * @param \SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface $placeholderReplacementRenderer
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer
     */
    protected function setFallbackPlaceholderReplacement(
        PlaceholderReplacementRendererInterface $placeholderReplacementRenderer,
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
    ): CoreMediaPlaceholderTransfer {
        return $coreMediaPlaceholderTransfer->setPlaceholderReplacement(
            $placeholderReplacementRenderer->getFallbackPlaceholderReplacement()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface|null
     */
    protected function resolvePlaceholderReplacementRenderer(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
    ): ?PlaceholderReplacementRendererInterface {
        foreach ($this->placeholderReplacementRenderers as $placeholderReplacementRenderer) {
            if ($placeholderReplacementRenderer->isApplicable($coreMediaPlaceholderTransfer)) {
                return $placeholderReplacementRenderer;
            }
        }

        return null;
    }
}
