<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse\Executor;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use SprykerEco\Yves\CoreMedia\CoreMediaConfig;
use SprykerEco\Yves\CoreMedia\Exception\InvalidPlaceholderDataException;

class IncorrectPlaceholderDataExecutor implements IncorrectPlaceholderDataExecutorInterface
{
    /**
     * @var \SprykerEco\Yves\CoreMedia\CoreMediaConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Yves\CoreMedia\CoreMediaConfig $coreMediaConfig
     */
    public function __construct(CoreMediaConfig $coreMediaConfig)
    {
        $this->config = $coreMediaConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return void
     */
    public function executeIncorrectPlaceholderData(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): void {
        if (!$this->config->isDebugModeEnabled()) {
            return;
        }

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
