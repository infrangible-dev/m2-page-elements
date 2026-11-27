<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\PageElements\Plugin\Catalog\Block\Product;

use Infrangible\PageElements\Helper\Data;
use Magento\Catalog\Block\Product\View as ProductView;

/**
 * Hides the quantity field of the product page.
 */
class View
{
    /**
     * @param Data $helper
     */
    public function __construct(protected Data $helper)
    {
    }

    /**
     * The quantity field is not rendered if the setting "Hide Qty" is switched on.
     *
     * @param ProductView $subject
     * @param bool $result
     *
     * @return bool
     *
     * @noinspection PhpUnusedParameterInspection the signature of the plugin is given
     */
    public function afterShouldRenderQuantity(ProductView $subject, bool $result): bool
    {
        return $result && ! $this->helper->isEnabled('product/hide_qty');
    }
}
