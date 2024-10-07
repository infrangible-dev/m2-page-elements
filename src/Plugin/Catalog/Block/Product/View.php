<?php

declare(strict_types=1);

namespace Infrangible\PageElements\Plugin\Catalog\Block\Product;

use Infrangible\Core\Helper\Stores;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class View
{
    /** @var Stores */
    protected $storesHelper;

    public function __construct(Stores $storesHelper)
    {
        $this->storesHelper = $storesHelper;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function aroundShouldRenderQuantity(\Magento\Catalog\Block\Product\View $subject, callable $proceed): bool
    {
        if ($this->storesHelper->getStoreConfigFlag('infrangible_pageelements/product/hide_qty')) {
            return false;
        }

        return $proceed();
    }
}
