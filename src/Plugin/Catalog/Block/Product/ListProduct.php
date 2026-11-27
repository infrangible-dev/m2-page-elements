<?php /** @noinspection PhpDeprecationInspection product blocks of Magento's lists still extend AbstractProduct */

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\PageElements\Plugin\Catalog\Block\Product;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\ListProduct as ProductList;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;

/**
 * Lets the layout add blocks in front of the price and the details of every product of product lists: the blocks of
 * the containers "category.products.list.product.price" and "category.products.list.product.details".
 */
class ListProduct
{
    /** The container whose blocks are rendered in front of the price of every product. */
    public const PRICE_CONTAINER = 'category.products.list.product.price';

    /** The container whose blocks are rendered in front of the details of every product. */
    public const DETAILS_CONTAINER = 'category.products.list.product.details';

    /**
     * Renders the blocks of the price container for the product in front of its price.
     *
     * @param ProductList $subject
     * @param string $result
     * @param Product $product
     *
     * @return string
     * @throws LocalizedException if the list has no layout
     */
    public function afterGetProductPrice(ProductList $subject, string $result, Product $product): string
    {
        return $this->renderBlocks($subject, self::PRICE_CONTAINER, $product) . $result;
    }

    /**
     * Renders the blocks of the details container for the product in front of its details.
     *
     * @param ProductList $subject
     * @param string $result
     * @param Product $product
     *
     * @return string
     * @throws LocalizedException if the list has no layout
     */
    public function afterGetProductDetailsHtml(ProductList $subject, string $result, Product $product): string
    {
        return $this->renderBlocks($subject, self::DETAILS_CONTAINER, $product) . $result;
    }

    /**
     * Renders the product blocks of the container for the product.
     *
     * @param ProductList $productList
     * @param string $container
     * @param Product $product
     *
     * @return string
     * @throws LocalizedException if the list has no layout
     */
    private function renderBlocks(ProductList $productList, string $container, Product $product): string
    {
        $html = '';

        foreach ($productList->getLayout()->getChildBlocks($container) as $block) {
            if ($block instanceof AbstractProduct) {
                $block->setData('product', $product);

                $html .= $block->toHtml();
            }
        }

        return $html;
    }
}
