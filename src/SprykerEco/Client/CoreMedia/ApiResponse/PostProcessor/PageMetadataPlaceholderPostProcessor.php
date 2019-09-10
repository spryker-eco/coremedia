<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;

class PageMetadataPlaceholderPostProcessor extends AbstractPlaceholderPostProcessor
{
    protected const PLACEHOLDE_OBJECT_TYPE = 'page';
    protected const PLACEHOLDE_RENDER_TYPE = 'metadata';

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return bool
     */
    public function isApplicable(CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer): bool
    {
        return $coreMediaPlaceholderTransfer->getObjectType() === static::PLACEHOLDE_OBJECT_TYPE &&
            $coreMediaPlaceholderTransfer->getRenderType() === static::PLACEHOLDE_RENDER_TYPE;
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
        $metadata = null;

        if ($coreMediaPlaceholderTransfer->getTitle()) {
            $metadata .= $this->createMetatag(CoreMediaPlaceholderTransfer::TITLE, $coreMediaPlaceholderTransfer->getTitle());
        }

        if ($coreMediaPlaceholderTransfer->getDescription()) {
            $metadata .= $this->createMetatag(CoreMediaPlaceholderTransfer::DESCRIPTION, $coreMediaPlaceholderTransfer->getDescription());
        }

        if ($coreMediaPlaceholderTransfer->getKeywords()) {
            $metadata .= $this->createMetatag(CoreMediaPlaceholderTransfer::KEYWORDS, $coreMediaPlaceholderTransfer->getKeywords());
        }

        return $metadata;
    }

    /**
     * @param string $metaKey
     * @param string $metaValue
     *
     * @return string
     */
    protected function createMetatag(string $metaKey, string $metaValue): string
    {
        return sprintf($this->config->getMetaTagPattern(), $metaKey, $metaValue);
    }
}
