<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Preparator\Parser;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Client\CoreMedia\CoreMediaConfig;
use SprykerEco\Client\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface;
use SprykerEco\Shared\CoreMedia\CoreMediaConfig as SharedCoreMediaConfig;

class PlaceholderParser implements PlaceholderParserInterface
{
    /**
     * @var \SprykerEco\Client\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \SprykerEco\Client\CoreMedia\CoreMediaConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Client\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface $utilEncodingService
     * @param \SprykerEco\Client\CoreMedia\CoreMediaConfig $config
     */
    public function __construct(
        CoreMediaToUtilEncodingServiceInterface $utilEncodingService,
        CoreMediaConfig $config
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->config = $config;
    }

    /**
     * @param string $content
     *
     * @return \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer[]
     */
    public function parse(string $content): array
    {
        preg_match_all(
            $this->config->getPlaceholderPattern(),
            $content,
            $matches
        );

        $placeholders = [];

        if (!$matches[SharedCoreMediaConfig::PREG_MATCH_PLACEHOLDER_KEY]) {
            return $placeholders;
        }

        $placeholdersData = array_unique($matches[SharedCoreMediaConfig::PREG_MATCH_PLACEHOLDER_KEY]);

        foreach ($placeholdersData as $placeholderKey => $placeholderData) {
            $placeholderData = $this->decodePlaceholderData($placeholderData);

            $coreMediaPlaceholderTransfer = (new CoreMediaPlaceholderTransfer())
                ->fromArray($placeholderData, true)
                ->setPlaceholderBody($matches[0][$placeholderKey]);

            $placeholders[] = $coreMediaPlaceholderTransfer;
        }

        return $placeholders;
    }

    /**
     * @param string $placeholderData
     *
     * @return array
     */
    protected function decodePlaceholderData(string $placeholderData): array
    {
        return $this->utilEncodingService->decodeJson(
            html_entity_decode($placeholderData, ENT_QUOTES, 'UTF-8'),
            true
        );
    }
}
