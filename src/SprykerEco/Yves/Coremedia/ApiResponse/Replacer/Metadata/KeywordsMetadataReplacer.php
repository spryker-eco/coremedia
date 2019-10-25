<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\ApiResponse\Replacer\Metadata;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;
use SprykerEco\Yves\Coremedia\CoremediaConfig;

class KeywordsMetadataReplacer implements MetadataReplacerInterface
{
    /**
     * @var \SprykerEco\Yves\Coremedia\CoremediaConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Yves\Coremedia\CoremediaConfig $config
     */
    public function __construct(CoremediaConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return string
     */
    public function replaceMetaTag(CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer): string
    {
        if ($coreMediaPlaceholderTransfer->getKeywords() === null) {
            return '';
        }

        return sprintf(
            $this->config->getMetaTagFormat(),
            CoremediaPlaceholderTransfer::KEYWORDS,
            htmlentities($coreMediaPlaceholderTransfer->getKeywords())
        );
    }
}
