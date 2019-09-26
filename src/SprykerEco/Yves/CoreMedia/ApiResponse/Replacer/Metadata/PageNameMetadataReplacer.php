<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\Metadata;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Yves\CoreMedia\CoreMediaConfig;

class PageNameMetadataReplacer implements MetadataReplacerInterface
{
    /**
     * @var \SprykerEco\Yves\CoreMedia\CoreMediaConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Yves\CoreMedia\CoreMediaConfig $config
     */
    public function __construct(CoreMediaConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return string
     */
    public function replaceMetaTag(CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer): string
    {
        if ($coreMediaPlaceholderTransfer->getPageName() === null) {
            return '';
        }

        return sprintf(
            $this->config->getMetaTagFormat(),
            CoreMediaPlaceholderTransfer::PAGE_NAME,
            htmlentities($coreMediaPlaceholderTransfer->getPageName())
        );
    }
}
