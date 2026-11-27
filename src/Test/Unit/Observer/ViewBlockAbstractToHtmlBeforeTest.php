<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\PageElements\Test\Unit\Observer;

use Infrangible\PageElements\Helper\Data;
use Infrangible\PageElements\Observer\ViewBlockAbstractToHtmlBefore;
use Magento\Framework\DataObject;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use Magento\Framework\View\Model\Layout\Merge;
use Magento\Theme\Block\Html\Header\Logo;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

/**
 * Tests hiding blocks by removing their template.
 */
#[AllowMockObjectsWithoutExpectations]
class ViewBlockAbstractToHtmlBeforeTest extends TestCase
{
    /**
     * Returns the observer with the settings that are switched on.
     *
     * @param list<string> $enabled
     *
     * @return ViewBlockAbstractToHtmlBefore
     */
    private function createObserver(array $enabled): ViewBlockAbstractToHtmlBefore
    {
        $helper = $this->createStub(Data::class);
        $helper->method('isEnabled')->willReturnCallback(
            fn(string $setting): bool => in_array($setting, $enabled, true)
        );

        return new ViewBlockAbstractToHtmlBefore($helper);
    }

    /**
     * Returns a block with the name and a template, on a page with the handles; a logo block or a template block.
     *
     * @param string $name
     * @param list<string> $handles
     * @param bool $logo
     *
     * @return Template
     */
    private function createBlock(string $name, array $handles = ['default'], bool $logo = false): Template
    {
        $update = $this->createStub(Merge::class);
        $update->method('getHandles')->willReturn($handles);

        $layout = $this->createStub(LayoutInterface::class);
        $layout->method('getUpdate')->willReturn($update);

        $block = $logo ?
            $this->getMockBuilder(Logo::class)
                ->disableOriginalConstructor()
                ->onlyMethods(['getNameInLayout', 'getLayout'])
                ->getMock() :
            $this->getMockBuilder(Template::class)
                ->disableOriginalConstructor()
                ->onlyMethods(['getNameInLayout', 'getLayout'])
                ->getMock();
        $block->method('getNameInLayout')->willReturn($name);
        $block->method('getLayout')->willReturn($layout);
        $block->setTemplate('template.phtml');

        return $block;
    }

    /**
     * Runs the observer for the block.
     *
     * @param ViewBlockAbstractToHtmlBefore $observer
     * @param Template $block
     *
     * @return void
     */
    private function execute(ViewBlockAbstractToHtmlBefore $observer, Template $block): void
    {
        $observer->execute(new Observer(['event' => new Event(['block' => $block])]));
    }

    /**
     * A block whose setting is on loses its template; other blocks and blocks of other classes keep theirs.
     *
     * @return void
     */
    public function testHidesBlocks(): void
    {
        $observer = $this->createObserver(['header/hide_logo', 'product/hide_sku']);

        $logo = $this->createBlock('logo', ['default'], true);
        $sku = $this->createBlock('product.info.sku');
        $search = $this->createBlock('top.search');
        $otherLogo = $this->createBlock('logo');

        foreach ([$logo, $sku, $search, $otherLogo] as $block) {
            $this->execute($observer, $block);
        }

        $this->assertSame('', $logo->getTemplate());
        $this->assertSame('', $sku->getTemplate());
        $this->assertSame('template.phtml', $search->getTemplate());
        $this->assertSame('template.phtml', $otherLogo->getTemplate());
    }

    /**
     * The page title settings of categories, the search and products only hide the title on their pages.
     *
     * @return void
     */
    public function testPageTitleOfPageType(): void
    {
        $observer = $this->createObserver(['category/hide_page_title']);

        $categoryTitle = $this->createBlock('page.main.title', ['default', 'catalog_category_view']);
        $productTitle = $this->createBlock('page.main.title', ['default', 'catalog_product_view']);

        $this->execute($observer, $categoryTitle);
        $this->execute($observer, $productTitle);

        $this->assertSame('', $categoryTitle->getTemplate());
        $this->assertSame('template.phtml', $productTitle->getTemplate());
    }

    /**
     * Events without a block of a template are ignored.
     *
     * @return void
     */
    public function testOtherBlocks(): void
    {
        $observer = $this->createObserver(['header/hide_logo']);

        $observer->execute(new Observer(['event' => new Event(['block' => new DataObject()])]));

        $this->addToAssertionCount(1);
    }
}
