<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\PageElements\Helper;

use FeWeDev\Base\Variables;
use Infrangible\Core\Helper\Stores;

/**
 * The settings of the module in Stores > Configuration > Infrangible > Page Elements.
 */
class Data
{
    /** The section of the settings. */
    private const SECTION = 'infrangible_pageelements';

    /**
     * @param Stores $storesHelper
     * @param Variables $variables
     */
    public function __construct(protected Stores $storesHelper, protected Variables $variables)
    {
    }

    /**
     * Checks if a setting of the current store is switched on, for example "header/hide_logo".
     *
     * @param string $setting
     *
     * @return bool
     */
    public function isEnabled(string $setting): bool
    {
        return $this->storesHelper->getStoreConfigFlag(self::SECTION . '/' . $setting);
    }

    /**
     * Returns the ID of the CMS block of a setting of the current store, for example "header/replace_header_block".
     *
     * @param string $setting
     *
     * @return string
     */
    public function getBlockId(string $setting): string
    {
        return $this->variables->stringValueOrDefault(
            $this->storesHelper->getStoreConfigValue(self::SECTION . '/' . $setting)
        );
    }
}
