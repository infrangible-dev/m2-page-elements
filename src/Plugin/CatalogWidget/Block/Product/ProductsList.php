<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\PageElements\Plugin\CatalogWidget\Block\Product;

use Infrangible\PageElements\Helper\Data;
use Magento\CatalogWidget\Block\Product\ProductsList as Widget;

/**
 * Hides the links to compare products in the product list widget.
 */
class ProductsList
{
    /**
     * @param Data $helper
     */
    public function __construct(protected Data $helper)
    {
    }

    /**
     * The widget gets no URL to compare products if the setting "Hide Compare" is switched on, so it shows no link.
     *
     * @param Widget $subject
     * @param string $result
     *
     * @return string
     *
     * @noinspection PhpUnusedParameterInspection the signature of the plugin is given
     */
    public function afterGetAddToCompareUrl(Widget $subject, string $result): string
    {
        return $this->helper->isEnabled('product/hide_compare') ? '' : $result;
    }
}
