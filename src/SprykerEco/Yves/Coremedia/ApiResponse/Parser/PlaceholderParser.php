<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\ApiResponse\Parser;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;
use SprykerEco\Shared\Coremedia\CoremediaConfig as SharedCoremediaConfig;
use SprykerEco\Yves\Coremedia\CoremediaConfig;
use SprykerEco\Yves\Coremedia\Dependency\Service\CoremediaToUtilEncodingServiceInterface;

class PlaceholderParser implements PlaceholderParserInterface
{
    /**
     * @var \SprykerEco\Yves\Coremedia\Dependency\Service\CoremediaToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \SprykerEco\Yves\Coremedia\CoremediaConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Yves\Coremedia\Dependency\Service\CoremediaToUtilEncodingServiceInterface $utilEncodingService
     * @param \SprykerEco\Yves\Coremedia\CoremediaConfig $config
     */
    public function __construct(
        CoremediaToUtilEncodingServiceInterface $utilEncodingService,
        CoremediaConfig $config
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->config = $config;
    }

    /**
     * @param string $content
     *
     * @return \Generated\Shared\Transfer\CoremediaPlaceholderTransfer[]
     */
    public function parse(string $content): array
    {
        preg_match_all(
            $this->config->getPlaceholderPattern(),
            $content,
            $matches
        );

        $placeholders = [];

        if (!$matches[SharedCoremediaConfig::PREG_MATCH_PLACEHOLDER_KEY]) {
            return [];
        }

        $placeholdersData = array_unique($matches[SharedCoremediaConfig::PREG_MATCH_PLACEHOLDER_KEY]);

        foreach ($placeholdersData as $placeholderKey => $placeholderData) {
            $decodedPlaceholderData = $this->decodePlaceholderData($placeholderData);

            if (!$decodedPlaceholderData) {
                continue;
            }

            $coreMediaPlaceholderTransfer = (new CoremediaPlaceholderTransfer())
                ->fromArray($decodedPlaceholderData, true)
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
            $this->htmlEntityDecodePlaceholderData($placeholderData),
            true
        );
    }

    /**
     * @param string $placeholderData
     *
     * @return string
     */
    protected function htmlEntityDecodePlaceholderData(string $placeholderData): string
    {
        return html_entity_decode($placeholderData, ENT_QUOTES, 'UTF-8');
    }
}
