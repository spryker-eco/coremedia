<?php
/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\CoreMedia\Preparator\Parser;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Client\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface;

class CoreMediaPlaceholderParser implements CoreMediaPlaceholderParserInterface
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

        if ($results[static::PREG_MATCH_PLACEHOLDER_KEY]) {
            foreach ($results[static::PREG_MATCH_PLACEHOLDER_KEY] as $placeholder) {
                $placeholderData = $this->utilEncodingService->decodeJson($placeholder, static::JSON_DECODE_ASSOC);

                $coreMediaPlaceholderTransfer = (new CoreMediaPlaceholderTransfer())
                    ->fromArray($placeholderData, true)
                    ->setPlaceholderBody($placeholder);
            }
        }

        return [];
    }
}