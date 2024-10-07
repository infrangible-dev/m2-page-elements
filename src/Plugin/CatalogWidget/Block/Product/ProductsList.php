<?php

declare(strict_types=1);

namespace Infrangible\PageElements\Plugin\CatalogWidget\Block\Product;

use Infrangible\Core\Helper\Stores;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class ProductsList
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
    public function aroundGetAddToCompareUrl(
        \Magento\CatalogWidget\Block\Product\ProductsList $subject,
        callable $proceed
    ): string {
        if ($this->storesHelper->getStoreConfigFlag('infrangible_pageelements/product/hide_compare')) {
            return '';
        }

        return $proceed();
    }
}
