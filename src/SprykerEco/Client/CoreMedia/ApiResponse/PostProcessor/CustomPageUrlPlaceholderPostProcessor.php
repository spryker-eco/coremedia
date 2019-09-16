<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Client\CoreMedia\CoreMediaConfig;

class CustomPageUrlPlaceholderPostProcessor extends AbstractPlaceholderPostProcessor
{
    protected const PLACEHOLDER_OBJECT_TYPE = 'page';
    protected const PLACEHOLDER_RENDER_TYPE = 'url';

    /**
     * @param \SprykerEco\Client\CoreMedia\CoreMediaConfig $config
     */
    public function __construct(
        CoreMediaConfig $config
    ) {
        parent::__construct($config);
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return bool
     */
    public function isApplicable(CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer): bool
    {
        return $coreMediaPlaceholderTransfer->getObjectType() === static::PLACEHOLDER_OBJECT_TYPE &&
            $coreMediaPlaceholderTransfer->getRenderType() === static::PLACEHOLDER_RENDER_TYPE;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    protected function getPlaceholderReplacement(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        return $this->findPageUrl($coreMediaPlaceholderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return string|null
     */
    protected function findPageUrl(CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer): ?string
    {
        if (!$coreMediaPlaceholderTransfer->getExternalSeoSegment()) {
            return null;
        }

        return $coreMediaPlaceholderTransfer->getExternalSeoSegment();
    }
}
