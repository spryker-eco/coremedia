<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Reader;

use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface;

class ProductAbstractStorageReader implements ProductAbstractStorageReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';

    /**
     * @var \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var array
     */
    protected static $productsAbstractDataCache = [];

    /**
     * @param \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface $productStorageClient
     */
    public function __construct(CoreMediaToProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param string $identifier
     * @param string $locale
     *
     * @return array|null
     */
    public function getProductAbstractData(string $identifier, string $locale): ?array
    {
        if (isset(static::$productsAbstractDataCache[$identifier][$locale])) {
            return static::$productsAbstractDataCache[$identifier][$locale];
        }

        static::$productsAbstractDataCache[$identifier][$locale] = $this->productStorageClient
            ->findProductAbstractStorageDataByMapping(
                static::PRODUCT_ABSTRACT_MAPPING_TYPE,
                $identifier,
                $locale
            );

        return static::$productsAbstractDataCache[$identifier][$locale];
    }
}
