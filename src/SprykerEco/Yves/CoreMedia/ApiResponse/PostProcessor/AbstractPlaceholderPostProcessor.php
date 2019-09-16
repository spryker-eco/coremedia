<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use SprykerEco\Yves\CoreMedia\CoreMediaConfig;
use SprykerEco\Yves\CoreMedia\Exception\InvalidPlaceholderDataException;

abstract class AbstractPlaceholderPostProcessor implements PlaceholderPostProcessorInterface
{
    /**
     * @var \SprykerEco\Yves\CoreMedia\CoreMediaConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Yves\CoreMedia\CoreMediaConfig $config
     */
    public function __construct(CoreMediaConfig $config)
    {
        $this->config = $config;
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
        $placeholderReplacement = $this->getPlaceholderReplacement(
            $coreMediaPlaceholderTransfer,
            $locale
        );

        if (!$placeholderReplacement) {
            $this->handleIncorrectPlaceholderData($coreMediaPlaceholderTransfer, $locale);

            return $this->setFallbackPlaceholderReplacement($coreMediaPlaceholderTransfer);
        }

        $coreMediaPlaceholderTransfer->setPlaceholderReplacement($placeholderReplacement);

        return $coreMediaPlaceholderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    abstract protected function getPlaceholderReplacement(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string;

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer
     */
    protected function setFallbackPlaceholderReplacement(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
    ): CoreMediaPlaceholderTransfer {
        return $coreMediaPlaceholderTransfer->setPlaceholderReplacement('');
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return void
     */
    protected function handleIncorrectPlaceholderData(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): void {
        if ($this->config->isDebugModeEnabled()) {
            $dataException = new InvalidPlaceholderDataException(
                sprintf(
                    "Cannot obtain placeholder replacement for:\n[Placeholder]: %s\n[Locale]: %s",
                    $coreMediaPlaceholderTransfer->getPlaceholderBody(),
                    $locale
                )
            );

            ErrorLogger::getInstance()->log($dataException);
        }
    }
}
