<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\Reader\Product;

use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface;

class ProductConcreteStorageReader implements ProductConcreteStorageReaderInterface
{
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';

    /**
     * @var \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var array
     */
    protected static $productsConcreteDataCache = [];

    /**
     * @param \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface $productStorageClient
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
    public function getProductConcreteData(string $identifier, string $locale): ?array
    {
        if (isset(static::$productsConcreteDataCache[$identifier][$locale])) {
            return static::$productsConcreteDataCache[$identifier][$locale];
        }

        static::$productsConcreteDataCache[$identifier][$locale] = $this->productStorageClient
            ->findProductConcreteStorageDataByMapping(
                static::PRODUCT_CONCRETE_MAPPING_TYPE,
                $identifier,
                $locale
            );

        return static::$productsConcreteDataCache[$identifier][$locale];
    }
}
