<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\PageElements\Observer;

use Infrangible\PageElements\Helper\Data;
use Magento\Checkout\Block\Cart\Sidebar;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Store\Block\Switcher;
use Magento\Theme\Block\Html\Header\Logo;

/**
 * Hides blocks of the page whose setting is switched on, by removing their template before they are rendered.
 */
class ViewBlockAbstractToHtmlBefore implements ObserverInterface
{
    /**
     * The blocks that can be hidden: by name in the layout, the settings that hide them, each with the page handle it
     * is restricted to (null for every page), and the class the block must have.
     */
    private const BLOCKS = [
        'logo'                                => [['header/hide_logo', null, Logo::class]],
        'top.search'                          => [['header/hide_search', null, Template::class]],
        'minicart'                            => [['header/hide_minicart', null, Sidebar::class]],
        'navigation.sections'                 => [['navigation/hide_navigation', null, Template::class]],
        'store_switcher'                      => [['footer/hide_store_switcher', null, Switcher::class]],
        'copyright'                           => [['copyright/hide_copyright', null, Template::class]],
        'breadcrumbs'                         => [['breadcrumbs/hide_breadcrumbs', null, Template::class]],
        'page.main.title'                     => [
            ['page_title/hide_page_title', null, Template::class],
            ['category/hide_page_title', 'catalog_category_view', Template::class],
            ['catalog_search/hide_page_title', 'catalogsearch_result_index', Template::class],
            ['product/hide_page_title', 'catalog_product_view', Template::class]
        ],
        'catalog.leftnav'                     => [['category/hide_sidebar', null, Template::class]],
        'catalogsearch.leftnav'               => [['catalog_search/hide_sidebar', null, Template::class]],
        'catalog.compare.sidebar'             => [['product/hide_compare', null, Template::class]],
        'category.product.addto.compare'      => [['product/hide_compare', null, Template::class]],
        'catalogsearch.product.addto.compare' => [['product/hide_compare', null, Template::class]],
        'catalog.product.related'             => [['product/hide_related', null, Template::class]],
        'product.info.upsell'                 => [['product/hide_upsell', null, Template::class]],
        'product.info.sku'                    => [['product/hide_sku', null, Template::class]],
        'product.info.simple'                 => [['product/hide_stock', null, Template::class]],
        'product.info.configurable'           => [['product/hide_stock', null, Template::class]],
        'product.info.grouped.stock'          => [['product/hide_stock', null, Template::class]],
        'product.info.bundle'                 => [['product/hide_stock', null, Template::class]],
        'product.info.virtual'                => [['product/hide_stock', null, Template::class]],
        'product.info.downloadable'           => [['product/hide_stock', null, Template::class]],
        'checkout.cart.crosssell'             => [['cart/hide_crosssell', null, Template::class]]
    ];

    /**
     * @param Data $helper
     */
    public function __construct(protected Data $helper)
    {
    }

    /**
     * Removes the template of the block if one of its settings is switched on, so that it renders nothing.
     *
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $block = $observer->getEvent()->getData('block');

        if (! $block instanceof Template) {
            return;
        }

        foreach (self::BLOCKS[ $block->getNameInLayout() ] ?? [] as [$setting, $handle, $className]) {
            if ($block instanceof $className && $this->helper->isEnabled($setting) &&
                ($handle === null || $this->hasHandle($block, $handle))) {

                $block->setTemplate('');

                return;
            }
        }
    }

    /**
     * Checks if the page of the block has the layout handle, for example "catalog_product_view".
     *
     * @param Template $block
     * @param string $handle
     *
     * @return bool
     */
    private function hasHandle(Template $block, string $handle): bool
    {
        try {
            return in_array($handle, $block->getLayout()->getUpdate()->getHandles(), true);
        } catch (LocalizedException) {
            return false;
        }
    }
}
