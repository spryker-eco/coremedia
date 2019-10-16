<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\ApiResponse\Renderer;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;

class PageMetadataPlaceholderReplacementRenderer implements PlaceholderReplacementRendererInterface
{
    protected const PLACEHOLDER_OBJECT_TYPE = 'page';
    protected const PLACEHOLDER_RENDER_TYPE = 'metadata';

    /**
     * @var \SprykerEco\Yves\Coremedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface[]
     */
    protected $metadataReplacers = [];

    /**
     * @param \SprykerEco\Yves\Coremedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface[] $metadataReplacers
     */
    public function __construct(array $metadataReplacers)
    {
        $this->metadataReplacers = $metadataReplacers;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return bool
     */
    public function isApplicable(CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer): bool
    {
        return $coreMediaPlaceholderTransfer->getObjectType() === static::PLACEHOLDER_OBJECT_TYPE &&
            $coreMediaPlaceholderTransfer->getRenderType() === static::PLACEHOLDER_RENDER_TYPE;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string
     */
    public function getPlaceholderReplacement(
        CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): string {
        $metadata = '';

        foreach ($this->metadataReplacers as $metadataReplacer) {
            $metadata .= $metadataReplacer->replaceMetaTag($coreMediaPlaceholderTransfer);
        }

        return $metadata;
    }

    /**
     * @return string|null
     */
    public function getFallbackPlaceholderReplacement(): ?string
    {
        return null;
    }
}
