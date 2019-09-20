<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Yves\CoreMedia\CoreMediaConfig;
use SprykerEco\Yves\CoreMedia\Reader\Category\CategoryStorageReaderInterface;

class CategoryUrlPlaceholderPostProcessor extends AbstractPlaceholderPostProcessor
{
    protected const PLACEHOLDER_OBJECT_TYPE = 'category';
    protected const PLACEHOLDER_RENDER_TYPE = 'url';

    /**
     * @var \SprykerEco\Yves\CoreMedia\Reader\Category\CategoryStorageReaderInterface
     */
    protected $categoryStorageReader;

    /**
     * @param \SprykerEco\Yves\CoreMedia\CoreMediaConfig $config
     * @param \SprykerEco\Yves\CoreMedia\Reader\Category\CategoryStorageReaderInterface $categoryStorageReader
     */
    public function __construct(
        CoreMediaConfig $config,
        CategoryStorageReaderInterface $categoryStorageReader
    ) {
        parent::__construct($config);

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
     * @return string|null
     */
    protected function getPlaceholderReplacement(
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
