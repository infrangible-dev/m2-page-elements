# Changelog

## 2.0.0

### Breaking changes

- Requires Magento 2.4.6 or newer and PHP 8.1 or newer. Older versions of Magento and PHP 7.1 to 8.0 are no longer
  supported; use version 1.x for these.
- Requires `infrangible/m2-core` 5.0, `infrangible/m2-foundation` 3.0 and `fewedev/base` 2.4.3.
- The plugins for the quantity field, the compare links and the product lists are `after` plugins instead of `around`
  plugins, the classes have native types and use constructor property promotion.

### Changed

- The settings are only read for the elements they act on, instead of all settings for every block of a page.
- The replacements are rendered with Magento's `BlockByIdentifier` instead of the deprecated `Magento\Cms\Block\Block`;
  the setting still stores the ID of the CMS block.
- The settings "Hide Crosssell", "Hide Upsell" and "Hide Related" are labeled "Hide Cross-Sells", "Hide Up-Sells" and
  "Hide Related Products"; their values are kept.

### Added

- Translations into 24 languages.
- Unit tests.
