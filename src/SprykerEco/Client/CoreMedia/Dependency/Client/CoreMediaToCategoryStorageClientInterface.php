<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Dependency\Client;

interface CoreMediaToCategoryStorageClientInterface
{
    /**
     * @param int $idCategoryStorageNode
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById($idCategoryStorageNode, $localeName);
}
