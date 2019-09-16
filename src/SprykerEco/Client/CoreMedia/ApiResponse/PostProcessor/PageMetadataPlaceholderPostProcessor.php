<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;

class PageMetadataPlaceholderPostProcessor extends AbstractPlaceholderPostProcessor
{
    protected const PLACEHOLDER_OBJECT_TYPE = 'page';
    protected const PLACEHOLDER_RENDER_TYPE = 'metadata';

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
     * @return string
     */
    protected function getPlaceholderReplacement(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): string {
        $metadata = '';

        if ($coreMediaPlaceholderTransfer->getTitle() !== null) {
            $metadata .= $this->createMetaTag(CoreMediaPlaceholderTransfer::TITLE, $coreMediaPlaceholderTransfer->getTitle());
        }

        if ($coreMediaPlaceholderTransfer->getDescription() !== null) {
            $metadata .= $this->createMetaTag(CoreMediaPlaceholderTransfer::DESCRIPTION, $coreMediaPlaceholderTransfer->getDescription());
        }

        if ($coreMediaPlaceholderTransfer->getKeywords() !== null) {
            $metadata .= $this->createMetaTag(CoreMediaPlaceholderTransfer::KEYWORDS, $coreMediaPlaceholderTransfer->getKeywords());
        }

        if ($coreMediaPlaceholderTransfer->getPageName() !== null) {
            $metadata .= $this->createMetaTag(CoreMediaPlaceholderTransfer::PAGE_NAME, $coreMediaPlaceholderTransfer->getPageName());
        }

        return $metadata;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer
     */
    protected function setFallbackPlaceholderReplacement(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
    ): CoreMediaPlaceholderTransfer {
        return $coreMediaPlaceholderTransfer->setPlaceholderReplacement(null);
    }

    /**
     * @param string $metaKey
     * @param string $metaValue
     *
     * @return string
     */
    protected function createMetaTag(string $metaKey, string $metaValue = ''): string
    {
        return sprintf($this->config->getMetaTagFormat(), $metaKey, $metaValue);
    }
}
