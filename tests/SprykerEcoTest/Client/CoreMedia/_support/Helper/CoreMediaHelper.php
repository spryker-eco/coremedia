<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Client\CoreMedia\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CategoryNodeStorageBuilder;
use Generated\Shared\DataBuilder\CoreMediaApiResponseBuilder;
use Generated\Shared\DataBuilder\CoreMediaFragmentRequestBuilder;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer;

class CoreMediaHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CoreMediaFragmentRequestTransfer
     */
    public function getCoreMediaFragmentRequestTransfer(array $seedData = []): CoreMediaFragmentRequestTransfer
    {
        return (new CoreMediaFragmentRequestBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function getCoreMediaApiResponseTransfer(array $seedData = []): CoreMediaApiResponseTransfer
    {
        return (new CoreMediaApiResponseBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeStorageTransfer(array $seedData = []): CategoryNodeStorageTransfer
    {
        return (new CategoryNodeStorageBuilder($seedData))->build();
    }

    /**
     * @return string
     */
    public function getCorrectCoreMediaApiResponseData(): string
    {
        return '<a href="&lt;!--CM {&quot;productId&quot;:&quot;012&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;">Test product abstract</a> '
        . '<a href="&lt;!--CM {&quot;productId&quot;:&quot;055_65789012&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;">Test product concrete</a> '
        . '<a href="&lt;!--CM {&quot;categoryId&quot;:&quot;12345&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;category&quot;} CM--&gt;">Test category</a>';
    }

    /**
     * @return string
     */
    public function getIncorrectCoreMediaApiResponseData(): string
    {
        return '<a href="&lt;!--CM {&quot;productId&quot;:&quot;073&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;">Test product abstract</a> '
        . '<a href="&lt;!--CM {&quot;productId&quot;:&quot;056_1234567&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;product&quot;} CM--&gt;">Test product concrete</a> '
        . '<a href="&lt;!--CM {&quot;categoryId&quot;:&quot;56789&quot;,&quot;renderType&quot;:&quot;url&quot;,&quot;objectType&quot;:&quot;category&quot;} CM--&gt;">Test category</a>';
    }
}
