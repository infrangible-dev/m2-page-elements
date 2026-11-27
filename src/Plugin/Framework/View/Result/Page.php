<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\PageElements\Plugin\Framework\View\Result;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\DesignInterface;
use Magento\Framework\View\Result\Page as ResultPage;

/**
 * Adds the code of the theme as class to the body of every page, for example "Magento/luma".
 */
class Page
{
    /**
     * @param DesignInterface $design
     */
    public function __construct(protected DesignInterface $design)
    {
    }

    /**
     * Adds the class of the theme before the page is rendered.
     *
     * @param ResultPage $subject
     * @param ResponseInterface $response
     *
     * @return array
     * @phpstan-return array{ResponseInterface}
     */
    public function beforeRenderResult(ResultPage $subject, ResponseInterface $response): array
    {
        $subject->getConfig()->addBodyClass($this->design->getDesignTheme()->getCode());

        return [$response];
    }
}
