<?php

declare(strict_types=1);

namespace Infrangible\PageElements\Plugin\Catalog\ViewModel\Product\Checker;

use Infrangible\Core\Helper\Stores;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class AddToCompareAvailability
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
    public function aroundIsAvailableForCompare(
        \Magento\Catalog\ViewModel\Product\Checker\AddToCompareAvailability $subject,
        callable $proceed,
        ProductInterface $product
    ): bool {
        if ($this->storesHelper->getStoreConfigFlag('infrangible_pageelements/product/hide_compare')) {
            return false;
        }

        return $proceed($product);
    }
}
