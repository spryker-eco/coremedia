<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Yves\CoreMedia\CoreMediaConfig;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CustomPageUrlPlaceholderPostProcessor extends AbstractPlaceholderPostProcessor
{
    protected const PLACEHOLDER_OBJECT_TYPE = 'page';
    protected const PLACEHOLDER_RENDER_TYPE = 'url';

    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @param \SprykerEco\Yves\CoreMedia\CoreMediaConfig $config
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        CoreMediaConfig $config,
        UrlGeneratorInterface $urlGenerator
    ) {
        parent::__construct($config);

        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return bool
     */
    public function isApplicable(CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer): bool
    {
        return $coreMediaPlaceholderTransfer->getObjectType() === static::PLACEHOLDER_OBJECT_TYPE &&
            $coreMediaPlaceholderTransfer->getRenderType() === static::PLACEHOLDER_RENDER_TYPE;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    protected function getPlaceholderReplacement(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        if (!$coreMediaPlaceholderTransfer->getExternalSeoSegment()) {
            return null;
        }

        $pageUrl = $this->urlGenerator->generate($coreMediaPlaceholderTransfer->getExternalSeoSegment());

        if ($pageUrl) {
            return $pageUrl;
        }

        return null;
    }
}
