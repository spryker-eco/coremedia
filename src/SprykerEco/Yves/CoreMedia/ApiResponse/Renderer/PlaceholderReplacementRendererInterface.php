<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse\Renderer;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;

interface PlaceholderReplacementRendererInterface
{
    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return bool
     */
    public function isApplicable(CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    public function getPlaceholderReplacement(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string;

    /**
     * @return string|null
     */
    public function getFallbackPlaceholderReplacement(): ?string;
}
