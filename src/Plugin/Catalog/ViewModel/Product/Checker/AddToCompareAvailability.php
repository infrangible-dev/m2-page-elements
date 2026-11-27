<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\PageElements\Plugin\Catalog\ViewModel\Product\Checker;

use Infrangible\PageElements\Helper\Data;
use Magento\Catalog\ViewModel\Product\Checker\AddToCompareAvailability as Checker;

/**
 * Hides the links to compare products.
 */
class AddToCompareAvailability
{
    /**
     * @param Data $helper
     */
    public function __construct(protected Data $helper)
    {
    }

    /**
     * No product can be compared if the setting "Hide Compare" is switched on.
     *
     * @param Checker $subject
     * @param bool $result
     *
     * @return bool
     *
     * @noinspection PhpUnusedParameterInspection the signature of the plugin is given
     */
    public function afterIsAvailableForCompare(Checker $subject, bool $result): bool
    {
        return $result && ! $this->helper->isEnabled('product/hide_compare');
    }
}
