<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\ApiResponse\Parser;

interface PlaceholderParserInterface
{
    /**
     * @param string $content
     *
     * @return \Generated\Shared\Transfer\CoremediaPlaceholderTransfer[]
     */
    public function parse(string $content): array;
}
