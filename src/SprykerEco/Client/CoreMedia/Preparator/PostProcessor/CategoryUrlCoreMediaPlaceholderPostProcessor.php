<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Preparator\PostProcessor;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Client\CoreMedia\Reader\CategoryStorageReaderInterface;

class CategoryUrlCoreMediaPlaceholderPostProcessor implements CoreMediaPlaceholderPostProcessorInterface
{
    protected const PLACEHOLDER_OBJECT_TYPE = 'category';
    protected const PLACEHOLDER_RENDER_TYPE = 'url';

    /**
     * @var \SprykerEco\Client\CoreMedia\Reader\CategoryStorageReaderInterface
     */
    protected $categoryStorageReader;

    /**
     * @param \SprykerEco\Client\CoreMedia\Reader\CategoryStorageReaderInterface $categoryStorageReader
     */
    public function __construct(CategoryStorageReaderInterface $categoryStorageReader)
    {
        $this->categoryStorageReader = $categoryStorageReader;
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
     * @return \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer
     */
    public function addReplacement(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): CoreMediaPlaceholderTransfer {
        $categoryUrl = $this->getCategoryUrlByCoreMediaPlaceholderTransfer(
            $coreMediaPlaceholderTransfer,
            $locale
        );

        if (!$categoryUrl) {
            return $coreMediaPlaceholderTransfer;
        }

        $coreMediaPlaceholderTransfer->setPlaceholderReplacement($categoryUrl);

        return $coreMediaPlaceholderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    protected function getCategoryUrlByCoreMediaPlaceholderTransfer(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        if (!$coreMediaPlaceholderTransfer->getCategoryId()) {
            return null;
        }

        $categoryNodeStorageTransfer = $this->categoryStorageReader->getCategoryNodeById(
            (int)$coreMediaPlaceholderTransfer->getCategoryId(),
            $locale
        );

        return $categoryNodeStorageTransfer->getUrl();
    }
}
