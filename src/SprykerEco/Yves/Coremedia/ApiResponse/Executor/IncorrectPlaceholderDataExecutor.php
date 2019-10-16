<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\ApiResponse\Executor;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use SprykerEco\Yves\Coremedia\CoremediaConfig;
use SprykerEco\Yves\Coremedia\Exception\InvalidPlaceholderDataException;

class IncorrectPlaceholderDataExecutor implements IncorrectPlaceholderDataExecutorInterface
{
    /**
     * @var \SprykerEco\Yves\Coremedia\CoremediaConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Yves\Coremedia\CoremediaConfig $coreMediaConfig
     */
    public function __construct(CoremediaConfig $coreMediaConfig)
    {
        $this->config = $coreMediaConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return void
     */
    public function executeIncorrectPlaceholderData(
        CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
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
