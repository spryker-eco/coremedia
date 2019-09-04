<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Reader;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface;

class CategoryStorageReader implements CategoryStorageReaderInterface
{
    /**
     * @var \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface
     */
    protected $categoryStorageClient;

    /**
     * @var \Generated\Shared\Transfer\CategoryNodeStorageTransfer[][]
     */
    protected static $categoryDataCache = [];

    /**
     * @param \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface $categoryStorageClient
     */
    public function __construct(CoreMediaToCategoryStorageClientInterface $categoryStorageClient)
    {
        $this->categoryStorageClient = $categoryStorageClient;
    }

    /**
     * @param int $idCategoryNode
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer|null
     */
    public function getCategoryNodeById(int $idCategoryNode, string $locale): ?CategoryNodeStorageTransfer
    {
        if (isset(static::$categoryDataCache[$idCategoryNode][$locale])) {
            return static::$categoryDataCache[$idCategoryNode][$locale];
        }

        static::$categoryDataCache[$idCategoryNode][$locale] = $this->categoryStorageClient->getCategoryNodeById(
            $idCategoryNode,
            $locale
        );

        return static::$categoryDataCache[$idCategoryNode][$locale];
    }
}
