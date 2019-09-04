<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Reader;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;

interface CategoryStorageReaderInterface
{
    /**
     * @param int $idCategoryStorageNode
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer|null
     */
    public function getCategoryNodeById(int $idCategoryStorageNode, string $locale): ?CategoryNodeStorageTransfer;
}
