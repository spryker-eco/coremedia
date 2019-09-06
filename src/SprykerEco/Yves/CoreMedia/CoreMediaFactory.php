<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerEco\Yves\CoreMedia\Mapper\RequestMapper;
use SprykerEco\Yves\CoreMedia\Mapper\RequestMapperInterface;

class CoreMediaFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Yves\CoreMedia\Mapper\RequestMapperInterface
     */
    public function createRequestMapper(): RequestMapperInterface
    {
        return new RequestMapper();
    }
}
