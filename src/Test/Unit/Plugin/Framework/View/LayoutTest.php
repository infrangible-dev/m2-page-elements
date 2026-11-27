<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\PageElements\Test\Unit\Plugin\Framework\View;

use Infrangible\Core\Helper\Stores;
use Infrangible\PageElements\Helper\Data;
use Infrangible\PageElements\Plugin\Framework\View\Layout;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Block\BlockByIdentifier;
use Magento\Framework\View\Layout as ViewLayout;
use PHPUnit\Framework\TestCase;

/**
 * Tests hiding and replacing containers and blocks of the layout.
 */
class LayoutTest extends TestCase
{
    /** @var list<string> the identifiers of the rendered CMS blocks */
    private array $cmsBlocks = [];

    /**
     * Returns the plugin with the settings that are switched on and the newsletter switched off.
     *
     * @param list<string> $enabled
     *
     * @return Layout
     */
    private function createPlugin(array $enabled): Layout
    {
        $helper = $this->createStub(Data::class);
        $helper->method('isEnabled')->willReturnCallback(
            fn(string $setting): bool => in_array($setting, $enabled, true)
        );
        $helper->method('getBlockId')->willReturnCallback(
            fn(string $setting): string => $setting === 'footer/replace_footer_block' ? '7' : '8'
        );

        $storesHelper = $this->createStub(Stores::class);
        $storesHelper->method('getStoreConfigFlag')->willReturn(false);

        $blocks = [];

        foreach ([7 => 'footer-block', 8 => 'copyright-block'] as $id => $identifier) {
            $blocks[ $id ] = $this->createStub(BlockInterface::class);
            $blocks[ $id ]->method('getIdentifier')->willReturn($identifier);
        }

        $blockRepository = $this->createStub(BlockRepositoryInterface::class);
        $blockRepository->method('getById')->willReturnCallback(fn(mixed $id): BlockInterface => $blocks[ $id ]);

        return new Layout($helper, $storesHelper, $blockRepository);
    }

    /**
     * Returns a layout with the containers; all other elements are blocks.
     *
     * @param list<string> $containers
     *
     * @return ViewLayout
     */
    private function createLayout(array $containers): ViewLayout
    {
        $block = $this->createStub(BlockByIdentifier::class);
        $block->method('setData')->willReturnCallback(
            function (mixed $key, mixed $value) use ($block): BlockByIdentifier {
                $this->cmsBlocks[] = is_string($value) ? $value : '';

                return $block;
            }
        );
        $block->method('toHtml')->willReturn('<div>cms</div>');

        $layout = $this->createStub(ViewLayout::class);
        $layout->method('isContainer')->willReturnCallback(
            fn(string $name): bool => in_array($name, $containers, true)
        );
        $layout->method('isBlock')->willReturnCallback(fn(string $name): bool => ! in_array($name, $containers, true));
        $layout->method('createBlock')->willReturn($block);

        return $layout;
    }

    /**
     * Renders the element with the plugin; the element itself renders as "<element name>".
     *
     * @param Layout $plugin
     * @param ViewLayout $layout
     * @param string $name
     *
     * @return string
     */
    private function render(Layout $plugin, ViewLayout $layout, string $name): string
    {
        return $plugin->aroundRenderElement($layout, fn(string $name): string => "<$name>", $name);
    }

    /**
     * Hidden containers and blocks render nothing, others render themselves.
     *
     * @return void
     */
    public function testHide(): void
    {
        $plugin = $this->createPlugin(['header/hide_header', 'footer/hide_links']);
        $layout = $this->createLayout(['header.container', 'footer-container']);

        $this->assertSame('', $this->render($plugin, $layout, 'header.container'));
        $this->assertSame('', $this->render($plugin, $layout, 'footer_links'));
        $this->assertSame('<footer-container>', $this->render($plugin, $layout, 'footer-container'));
    }

    /**
     * Replaced elements render their CMS block; hiding wins over replacing.
     *
     * @return void
     */
    public function testReplace(): void
    {
        $plugin = $this->createPlugin(['footer/replace_footer', 'copyright/replace_copyright', 'header/hide_header',
            'header/replace_header']);
        $layout = $this->createLayout(['header.container', 'footer-container']);

        $this->assertSame('<div>cms</div>', $this->render($plugin, $layout, 'footer-container'));
        $this->assertSame('<div>cms</div>', $this->render($plugin, $layout, 'copyright'));
        $this->assertSame('', $this->render($plugin, $layout, 'header.container'));
        $this->assertSame(
            ['footer-block', 'copyright-block'],
            $this->cmsBlocks
        );
    }

    /**
     * The reCAPTCHA of the newsletter is not rendered if the newsletter is switched off.
     *
     * @return void
     */
    public function testNewsletterRecaptcha(): void
    {
        $this->assertSame(
            '',
            $this->render($this->createPlugin([]), $this->createLayout([]), 'recaptcha-newsletter')
        );
    }
}
