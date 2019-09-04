<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Reader;

interface ProductConcreteStorageReaderInterface
{
    /**
     * @param string $identifier
     * @param string $locale
     *
     * @return array|null
     */
    public function getProductConcreteData(string $identifier, string $locale): ?array;
}
