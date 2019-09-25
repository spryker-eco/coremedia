<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
            return [];
        }

        $placeholdersData = array_unique($matches[SharedCoreMediaConfig::PREG_MATCH_PLACEHOLDER_KEY]);

        foreach ($placeholdersData as $placeholderKey => $placeholderData) {
            $decodedPlaceholderData = $this->decodePlaceholderData($placeholderData);

            if (!$decodedPlaceholderData) {
                continue;
            }

            $coreMediaPlaceholderTransfer = (new CoreMediaPlaceholderTransfer())
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
