<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\PageElements\Plugin\Framework\View;

use Infrangible\Core\Helper\Stores;
use Infrangible\PageElements\Helper\Data;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Block\BlockByIdentifier;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Layout as ViewLayout;

/**
 * Hides containers and blocks of the page or replaces them with a CMS block, as the settings say.
 */
class Layout
{
    /** The containers that can be hidden: by name in the layout, the setting that hides them. */
    private const HIDDEN_CONTAINERS = [
        'header.container'     => 'header/hide_header',
        'header.panel'         => 'header/hide_panel',
        'footer-container'     => 'footer/hide_footer',
        'compare-link-wrapper' => 'product/hide_compare'
    ];

    /** The blocks that can be hidden: by name in the layout, the setting that hides them. */
    private const HIDDEN_BLOCKS = [
        'footer_links' => 'footer/hide_links'
    ];

    /** The containers that can be replaced: by name in the layout, the setting and the setting of the CMS block. */
    private const REPLACED_CONTAINERS = [
        'header.container' => ['header/replace_header', 'header/replace_header_block'],
        'footer-container' => ['footer/replace_footer', 'footer/replace_footer_block']
    ];

    /** The blocks that can be replaced: by name in the layout, the setting and the setting of the CMS block. */
    private const REPLACED_BLOCKS = [
        'navigation.sections' => ['navigation/replace_navigation', 'navigation/replace_navigation_block'],
        'copyright'           => ['copyright/replace_copyright', 'copyright/replace_copyright_block']
    ];

    /** The reCAPTCHA of the newsletter form, which is not needed if the newsletter is switched off. */
    private const NEWSLETTER_RECAPTCHA = 'recaptcha-newsletter';

    /**
     * @param Data $helper
     * @param Stores $storesHelper
     * @param BlockRepositoryInterface $blockRepository
     */
    public function __construct(
        protected Data $helper,
        protected Stores $storesHelper,
        protected BlockRepositoryInterface $blockRepository
    ) {
    }

    /**
     * Renders nothing for hidden elements and the CMS block for replaced elements, else the element itself.
     *
     * @param ViewLayout $subject
     * @param callable $proceed
     * @param string $name
     * @param bool $useCache
     *
     * @return string
     */
    public function aroundRenderElement(
        ViewLayout $subject,
        callable $proceed,
        string $name,
        bool $useCache = true
    ): string {
        $isContainer = $subject->isContainer($name);
        $isBlock = $subject->isBlock($name);

        $hidingSetting = $isContainer ? self::HIDDEN_CONTAINERS[ $name ] ?? null :
            ($isBlock ? self::HIDDEN_BLOCKS[ $name ] ?? null : null);

        if ($hidingSetting !== null && $this->helper->isEnabled($hidingSetting)) {
            return '';
        }

        $replacement = $isContainer ? self::REPLACED_CONTAINERS[ $name ] ?? null :
            ($isBlock ? self::REPLACED_BLOCKS[ $name ] ?? null : null);

        if ($replacement !== null && $this->helper->isEnabled($replacement[ 0 ])) {
            return $this->renderCmsBlock($subject, $this->helper->getBlockId($replacement[ 1 ]));
        }

        if ($isBlock && $name === self::NEWSLETTER_RECAPTCHA &&
            ! $this->storesHelper->getStoreConfigFlag('newsletter/general/active')) {

            return '';
        }

        $result = $proceed($name, $useCache);

        return is_string($result) ? $result : '';
    }

    /**
     * Renders the CMS block with the ID (the settings store the ID); nothing if there is no such block.
     *
     * Magento's block by identifier gives the page cache the tags of the block, so that pages with it are refreshed
     * when the block is changed.
     *
     * @param ViewLayout $layout
     * @param string $blockId
     *
     * @return string
     */
    private function renderCmsBlock(ViewLayout $layout, string $blockId): string
    {
        if ($blockId === '' || ! ctype_digit($blockId)) {
            return '';
        }

        try {
            $identifier = $this->blockRepository->getById($blockId)->getIdentifier();
        } catch (LocalizedException) {
            return '';
        }

        $block = $layout->createBlock(BlockByIdentifier::class);
        $block->setData('identifier', $identifier);

        return $block->toHtml();
    }
}
