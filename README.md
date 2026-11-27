# Infrangible Page Elements

Hides standard elements of the Magento storefront, or replaces them with a CMS block, from the configuration: for
example a store view without compare function, a campaign store without navigation, a footer from a CMS block, or
product pages without SKU and stock status.

## Settings

Stores > Configuration > Infrangible Solutions > Page Elements, per website and store view. Every setting is "No" by
default.

- **Header:** hide the header or replace it with a CMS block; hide the panel with the welcome message and the account
  links, the logo, the search field or the minicart.
- **Navigation:** hide the main navigation or replace it with a CMS block.
- **Footer:** hide the footer or replace it with a CMS block; hide the footer links or the store switcher.
- **Copyright:** hide the copyright line or replace it with a CMS block.
- **Breadcrumbs** and **Page Title:** hide them on all pages.
- **Category** and **Catalog Search:** hide the page title and the sidebar with the layered navigation of category and
  search result pages.
- **Product:** hide the page title, the compare links (also in product lists and the product list widget), related
  products, up-sells, the SKU, the stock status and the quantity field.
- **Cart:** hide the cross-sells.

The settings act on the elements of Magento's layout by their names, for example `header.container` or
`product.info.sku`; themes that rename these elements are not affected by all settings.

## For themes and modules

- The body of every page has the code of the theme as class, for example `Magento/luma`.
- Product lists render the blocks of the containers `category.products.list.product.price` and
  `category.products.list.product.details` in front of the price and the details of every product, with the product
  set on each block.
- Product pages of simple products have the container `product.info.overview.extra` after the short description.

## Requirements

- Magento Open Source or Adobe Commerce 2.4.6 or newer
- PHP 8.1 or newer

## Installation

```bash
composer require infrangible/m2-page-elements
bin/magento module:enable Infrangible_PageElements
bin/magento setup:upgrade
```

In production mode, also run `bin/magento setup:di:compile` and `bin/magento setup:static-content:deploy`.

## License

Infrangible Page Elements is licensed under the MIT License - see the LICENSE file for details.
