<?php
/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\CoreMedia\Preparator\Parser;

interface CoreMediaPlaceholderParserInterface
{
    /**
     * @param string $content
     *
     * @return \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer[]
     */
    public function parse(string $content): array;
}
