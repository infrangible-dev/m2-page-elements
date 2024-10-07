<?php

declare(strict_types=1);

namespace Infrangible\PageElements\Plugin\Framework\View\Result;

use Infrangible\Core\Helper\Stores;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\DesignInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Page
{
    /** @var Stores */
    protected $storeHelper;

    /** @var DesignInterface */
    protected $design;

    public function __construct(Stores $storeHelper, DesignInterface $design)
    {
        $this->storeHelper = $storeHelper;

        $this->design = $design;
    }

    public function beforeRenderResult(\Magento\Framework\View\Result\Page $subject, ResponseInterface $response): array
    {
        $themeCode = $this->design->getDesignTheme()->getCode();

        $subject->getConfig()->addBodyClass($themeCode);

        return [$response];
    }
}
