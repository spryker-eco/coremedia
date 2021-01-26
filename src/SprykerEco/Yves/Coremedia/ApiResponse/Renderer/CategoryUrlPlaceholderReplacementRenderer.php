<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\ApiResponse\Renderer;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;
use SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToCategoryStorageClientInterface;
use SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToStoreClientInterface;

class CategoryUrlPlaceholderReplacementRenderer implements PlaceholderReplacementRendererInterface
{
    protected const PLACEHOLDER_OBJECT_TYPE = 'category';
    protected const PLACEHOLDER_RENDER_TYPE = 'url';

    /**
     * @var \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToCategoryStorageClientInterface
     */
    protected $categoryStorageClient;

    /**
     * @var \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToCategoryStorageClientInterface $categoryStorageClient
     * @param \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToStoreClientInterface $storeClient
     */
    public function __construct(
        CoremediaToCategoryStorageClientInterface $categoryStorageClient,
        CoremediaToStoreClientInterface $storeClient
    ) {
        $this->categoryStorageClient = $categoryStorageClient;
        $this->storeClient = $storeClient;
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
     * @return string|null
     */
    public function getPlaceholderReplacement(
        CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        if (!$coreMediaPlaceholderTransfer->getCategoryId()) {
            return null;
        }

        $categoryNodeStorageTransfer = $this->categoryStorageClient->getCategoryNodeById(
            (int)$coreMediaPlaceholderTransfer->getCategoryId(),
            $locale,
            $this->storeClient->getCurrentStore()->getName()
        );

        return $categoryNodeStorageTransfer->getUrl();
    }

    /**
     * @return string|null
     */
    public function getFallbackPlaceholderReplacement(): ?string
    {
        return '';
    }
}
