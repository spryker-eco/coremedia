<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\Dependency\Client;

class CoremediaToCategoryStorageClientBridge implements CoremediaToCategoryStorageClientInterface
{
    /**
     * @var \Spryker\Client\CategoryStorage\CategoryStorageClientInterface
     */
    protected $categoryClient;

    /**
     * @param \Spryker\Client\CategoryStorage\CategoryStorageClientInterface $categoryClient
     */
    public function __construct($categoryClient)
    {
        $this->categoryClient = $categoryClient;
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById($idCategoryNode, $localeName)
    {
        return $this->categoryClient->getCategoryNodeById($idCategoryNode, $localeName);
    }
}
