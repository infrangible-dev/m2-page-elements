<?php

declare(strict_types=1);

namespace Infrangible\PageElements\Observer;

use Infrangible\Core\Helper\Stores;
use Magento\Checkout\Block\Cart\Sidebar;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Magento\Store\Block\Switcher;
use Magento\Theme\Block\Html\Header\Logo;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class ViewBlockAbstractToHtmlBefore implements ObserverInterface
{
    /** @var Stores */
    protected $storesHelper;

    public function __construct(Stores $storesHelper)
    {
        $this->storesHelper = $storesHelper;
    }

    /**
     * @throws LocalizedException
     */
    public function execute(Observer $observer): void
    {
        /** @var AbstractBlock $block */
        $block = $observer->getEvent()->getData('block');

        $layout = $block->getLayout();
        $nameInLayout = $block->getNameInLayout();

        $hideLogo = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/header/hide_logo');

        if ($hideLogo && $nameInLayout === 'logo' && $block instanceof Logo) {
            $block->setTemplate(null);
        }

        $hideSearch = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/header/hide_search');

        if ($hideSearch && $nameInLayout === 'top.search' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        $hideMinicart = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/header/hide_minicart');

        if ($hideMinicart && $nameInLayout === 'minicart' && $block instanceof Sidebar) {
            $block->setTemplate(null);
        }

        $hideNavigation =
            $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/navigation/hide_navigation');

        if ($hideNavigation && $nameInLayout === 'navigation.sections' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        $hideStoreSwitcher =
            $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/footer/hide_store_switcher');

        if ($hideStoreSwitcher && $nameInLayout === 'store_switcher' && $block instanceof Switcher) {
            $block->setTemplate(null);
        }

        $hideCopyright = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/copyright/hide_copyright');

        if ($hideCopyright && $nameInLayout === 'copyright' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        $hidePageTitle = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/page_title/hide_page_title');

        if ($hidePageTitle && $nameInLayout === 'page.main.title' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        $hideBreadcrumbs =
            $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/breadcrumbs/hide_breadcrumbs');

        if ($hideBreadcrumbs && $nameInLayout === 'breadcrumbs' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        $hideCategoryPageTitle =
            $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/category/hide_page_title');

        if ($hideCategoryPageTitle && in_array(
                'catalog_category_view',
                $layout->getUpdate()->getHandles()
            ) && $nameInLayout === 'page.main.title' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        $hideCategorySidebar =
            $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/category/hide_sidebar');

        if ($hideCategorySidebar && $nameInLayout === 'catalog.leftnav' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        $hideSearchPageTitle =
            $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/catalog_search/hide_page_title');

        if ($hideSearchPageTitle && in_array(
                'catalogsearch_result_index',
                $layout->getUpdate()->getHandles()
            ) && $nameInLayout === 'page.main.title' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        $hideSearchSidebar =
            $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/catalog_search/hide_sidebar');

        if ($hideSearchSidebar && $nameInLayout === 'catalogsearch.leftnav' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        $hideProductPageTitle =
            $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/product/hide_page_title');

        if ($hideProductPageTitle && in_array(
                'catalog_product_view',
                $layout->getUpdate()->getHandles()
            ) && $nameInLayout === 'page.main.title' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        $hideCompare = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/product/hide_compare');

        if ($hideCompare && $nameInLayout === 'catalog.compare.sidebar' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        if ($hideCompare && $nameInLayout === 'category.product.addto.compare' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        if ($hideCompare && $nameInLayout === 'catalogsearch.product.addto.compare' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        $hideRelated = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/product/hide_related');

        if ($hideRelated && $nameInLayout === 'catalog.product.related' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        $hideUpsell = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/product/hide_upsell');

        if ($hideUpsell && $nameInLayout === 'product.info.upsell' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        $hideSku = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/product/hide_sku');

        if ($hideSku && $nameInLayout === 'product.info.sku' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        $hideStock = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/product/hide_stock');

        if ($hideStock && $nameInLayout === 'product.info.simple' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        if ($hideStock && $nameInLayout === 'product.info.configurable' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        if ($hideStock && $nameInLayout === 'product.info.grouped.stock' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        if ($hideStock && $nameInLayout === 'product.info.bundle' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        if ($hideStock && $nameInLayout === 'product.info.virtual' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        if ($hideStock && $nameInLayout === 'product.info.downloadable' && $block instanceof Template) {
            $block->setTemplate(null);
        }

        $hideCrosssell = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/cart/hide_crosssell');

        if ($hideCrosssell && $nameInLayout === 'checkout.cart.crosssell' && $block instanceof Template) {
            $block->setTemplate(null);
        }
    }
}
