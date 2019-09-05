<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Preparator\Parser;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Client\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface;

class PlaceholderParser implements PlaceholderParserInterface
{
    protected const PREG_MATCH_PLACEHOLDER_KEY = 'placeholder';
    protected const JSON_DECODE_ASSOC = true;

    /**
     * @var \SprykerEco\Client\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \SprykerEco\Client\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(CoreMediaToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $content
     *
     * @return \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer[]
     */
    public function parse(string $content): array
    {
        preg_match_all(
            '/(?:(?:&lt;|<)!--CM\s*)(?P<' . static::PREG_MATCH_PLACEHOLDER_KEY . '>(?:(?!CM--(&gt;|>)).|\s)*)(?:\s*\CM--(?:&gt;|>))/i',
            $content,
            $results
        );

        $coreMediaPlaceholders = [];

        if (!$results[static::PREG_MATCH_PLACEHOLDER_KEY]) {
            return $coreMediaPlaceholders;
        }

        $placeholdersData = array_unique($results[static::PREG_MATCH_PLACEHOLDER_KEY]);

        foreach ($placeholdersData as $placeholderKey => $placeholderData) {
            $placeholderData = $this->utilEncodingService->decodeJson(
                html_entity_decode($placeholderData, ENT_QUOTES, 'UTF-8'),
                static::JSON_DECODE_ASSOC
            );

            $coreMediaPlaceholderTransfer = (new CoreMediaPlaceholderTransfer())
                ->fromArray($placeholderData, true)
                ->setPlaceholderBody($results[0][$placeholderKey]);

            $coreMediaPlaceholders[] = $coreMediaPlaceholderTransfer;
        }

        return $coreMediaPlaceholders;
    }
}
