<?php /** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace Infrangible\PageElements\Plugin\Framework\View;

use Infrangible\Core\Helper\Stores;
use Magento\Cms\Block\Block;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Layout
{
    /** @var Stores */
    protected $storesHelper;

    public function __construct(Stores $storesHelper)
    {
        $this->storesHelper = $storesHelper;
    }

    public function aroundRenderElement(
        \Magento\Framework\View\Layout $subject,
        callable $proceed,
        string $name,
        bool $useCache = true
    ): ?string {
        $isContainer = $subject->isContainer($name);
        $isBlock = $subject->isBlock($name);

        $hideHeader = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/header/hide_header');

        if ($hideHeader && $isContainer && $name === 'header.container') {
            return '';
        }

        $replaceHeader = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/header/replace_header');

        if ($replaceHeader && $isContainer && $name === 'header.container') {
            $replacementBlockId =
                $this->storesHelper->getStoreConfig('infrangible_pageelements/header/replace_header_block');

            $replacementBlock = $subject->createBlock(Block::class);
            $replacementBlock->setData(
                'block_id',
                $replacementBlockId
            );

            return $replacementBlock->toHtml();
        }

        $hidePanel = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/header/hide_panel');

        if ($hidePanel && $isContainer && $name === 'header.panel') {
            return '';
        }

        $replaceNavigation =
            $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/navigation/replace_navigation');

        if ($replaceNavigation && $isBlock && $name === 'navigation.sections') {
            $replacementBlockId =
                $this->storesHelper->getStoreConfig('infrangible_pageelements/navigation/replace_navigation_block');

            $replacementBlock = $subject->createBlock(Block::class);
            $replacementBlock->setData(
                'block_id',
                $replacementBlockId
            );

            return $replacementBlock->toHtml();
        }

        $hideFooter = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/footer/hide_footer');

        if ($hideFooter && $isContainer && $name === 'footer-container') {
            return '';
        }

        $replaceFooter = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/footer/replace_footer');

        if ($replaceFooter && $isContainer && $name === 'footer-container') {
            $replacementBlockId =
                $this->storesHelper->getStoreConfig('infrangible_pageelements/footer/replace_footer_block');

            $replacementBlock = $subject->createBlock(Block::class);
            $replacementBlock->setData(
                'block_id',
                $replacementBlockId
            );

            return $replacementBlock->toHtml();
        }

        $hideFooterLinks = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/footer/hide_links');

        if ($hideFooterLinks && $name === 'footer_links' && $isBlock) {
            return '';
        }

        $newsletterActive = $this->storesHelper->getStoreConfigFlag('newsletter/general/active');

        if ($name === 'recaptcha-newsletter' && $isBlock && ! $newsletterActive) {
            return '';
        }

        $replaceCopyright =
            $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/copyright/replace_copyright');

        if ($replaceCopyright && $isBlock && $name === 'copyright') {
            $replacementBlockId =
                $this->storesHelper->getStoreConfig('infrangible_pageelements/copyright/replace_copyright_block');

            $replacementBlock = $subject->createBlock(Block::class);
            $replacementBlock->setData(
                'block_id',
                $replacementBlockId
            );

            return $replacementBlock->toHtml();
        }

        $hideCompare = $this->storesHelper->getStoreConfigFlag('infrangible_pageelements/product/hide_compare');

        if ($hideCompare && $isContainer && $name === 'compare-link-wrapper') {
            return '';
        }

        return $proceed(
            $name,
            $useCache
        );
    }
}
