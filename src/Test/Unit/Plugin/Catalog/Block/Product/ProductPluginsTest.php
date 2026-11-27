<?php /** @noinspection PhpDeprecationInspection product blocks of Magento's lists still extend AbstractProduct */

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\PageElements\Test\Unit\Plugin\Catalog\Block\Product;

use Infrangible\PageElements\Helper\Data;
use Infrangible\PageElements\Plugin\Catalog\Block\Product\ListProduct;
use Infrangible\PageElements\Plugin\Catalog\Block\Product\View;
use Infrangible\PageElements\Plugin\Catalog\ViewModel\Product\Checker\AddToCompareAvailability;
use Infrangible\PageElements\Plugin\CatalogWidget\Block\Product\ProductsList;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\ListProduct as ProductList;
use Magento\Catalog\Block\Product\View as ProductView;
use Magento\Catalog\Model\Product;
use Magento\Catalog\ViewModel\Product\Checker\AddToCompareAvailability as Checker;
use Magento\CatalogWidget\Block\Product\ProductsList as Widget;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\LayoutInterface;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

/**
 * Tests the plugins on products: quantity field, compare links and the blocks in front of prices and details.
 */
#[AllowMockObjectsWithoutExpectations]
class ProductPluginsTest extends TestCase
{
    /**
     * Returns the helper with the settings that are switched on.
     *
     * @param list<string> $enabled
     *
     * @return Data
     */
    private function createHelper(array $enabled): Data
    {
        $helper = $this->createStub(Data::class);
        $helper->method('isEnabled')->willReturnCallback(
            fn(string $setting): bool => in_array($setting, $enabled, true)
        );

        return $helper;
    }

    /**
     * The quantity field and the compare links are hidden by their settings; without them Magento decides.
     *
     * @return void
     */
    public function testSettings(): void
    {
        $hiding = $this->createHelper(['product/hide_qty', 'product/hide_compare']);
        $showing = $this->createHelper([]);

        $this->assertFalse((new View($hiding))->afterShouldRenderQuantity($this->createStub(ProductView::class), true));
        $this->assertTrue((new View($showing))->afterShouldRenderQuantity($this->createStub(ProductView::class), true));
        $this->assertFalse(
            (new View($showing))->afterShouldRenderQuantity($this->createStub(ProductView::class), false)
        );

        $checker = $this->createStub(Checker::class);
        $this->assertFalse((new AddToCompareAvailability($hiding))->afterIsAvailableForCompare($checker, true));
        $this->assertTrue((new AddToCompareAvailability($showing))->afterIsAvailableForCompare($checker, true));

        $widget = $this->createStub(Widget::class);
        $this->assertSame('', (new ProductsList($hiding))->afterGetAddToCompareUrl($widget, '/compare'));
        $this->assertSame('/compare', (new ProductsList($showing))->afterGetAddToCompareUrl($widget, '/compare'));
    }

    /**
     * The product blocks of the price container are rendered for the product in front of the price.
     *
     * @return void
     * @throws LocalizedException
     */
    public function testBlocksInFrontOfPrice(): void
    {
        $product = $this->createStub(Product::class);

        $block = $this->getMockBuilder(AbstractProduct::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['toHtml'])
            ->getMock();
        $block->method('toHtml')->willReturnCallback(
            fn(): string => $block->getData('product') === $product ? '<badge>' : '<wrong product>'
        );

        $layout = $this->createStub(LayoutInterface::class);
        $layout->method('getChildBlocks')->willReturnCallback(
            fn(string $name): array => $name === ListProduct::PRICE_CONTAINER ? [$block] : []
        );

        $productList = $this->createStub(ProductList::class);
        $productList->method('getLayout')->willReturn($layout);

        $plugin = new ListProduct();

        $this->assertSame('<badge><price>', $plugin->afterGetProductPrice($productList, '<price>', $product));
        $this->assertSame('<details>', $plugin->afterGetProductDetailsHtml($productList, '<details>', $product));
    }
}
