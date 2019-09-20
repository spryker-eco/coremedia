<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse\Parser;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Shared\CoreMedia\CoreMediaConfig as SharedCoreMediaConfig;
use SprykerEco\Yves\CoreMedia\CoreMediaConfig;
use SprykerEco\Yves\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface;

class PlaceholderParser implements PlaceholderParserInterface
{
    /**
     * @var \SprykerEco\Yves\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \SprykerEco\Yves\CoreMedia\CoreMediaConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Yves\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface $utilEncodingService
     * @param \SprykerEco\Yves\CoreMedia\CoreMediaConfig $config
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

            if (!$placeholderData) {
                continue;
            }

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
     * @return array|null
     */
    protected function decodePlaceholderData(string $placeholderData): ?array
    {
        return $this->utilEncodingService->decodeJson(
            html_entity_decode($placeholderData, ENT_QUOTES, 'UTF-8'),
            true
        );
    }
}
