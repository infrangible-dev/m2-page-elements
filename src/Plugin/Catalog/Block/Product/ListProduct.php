<?php /** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace Infrangible\PageElements\Plugin\Catalog\Block\Product;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class ListProduct
{
    /**
     * @throws LocalizedException
     */
    public function aroundGetProductPrice(
        \Magento\Catalog\Block\Product\ListProduct $subject,
        callable $proceed,
        Product $product
    ): string {
        $layout = $subject->getLayout();

        $childBlocks = $layout->getChildBlocks('category.products.list.product.price');

        $result = '';

        foreach ($childBlocks as $childBlock) {
            if ($childBlock instanceof AbstractProduct) {
                $childBlock->setDataUsingMethod(
                    'product',
                    $product
                );

                $result .= $childBlock->toHtml();
            }
        }

        return sprintf(
            '%s%s',
            $result,
            $proceed($product)
        );
    }

    /**
     * @throws LocalizedException
     */
    public function aroundGetProductDetailsHtml(
        \Magento\Catalog\Block\Product\ListProduct $subject,
        callable $proceed,
        Product $product
    ): string {
        $layout = $subject->getLayout();

        $childBlocks = $layout->getChildBlocks('category.products.list.product.details');

        $result = '';

        foreach ($childBlocks as $childBlock) {
            if ($childBlock instanceof AbstractProduct) {
                $childBlock->setDataUsingMethod(
                    'product',
                    $product
                );

                $result .= $childBlock->toHtml();
            }
        }

        return sprintf(
            '%s%s',
            $result,
            $proceed($product)
        );
    }
}
