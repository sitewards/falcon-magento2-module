**Note:** This is a cumulative changelog that outlines all of the changes to all magento modules in Deity [src/Deity](./src/Deity) namespace.

Versions marked with a number and date (e.g. v0.1.0 (2018-10-05)) are already released and available via packagist. Versions without a date are not released yet.
## v3.1.0 (2019-03-13)
 - Introduced Paypal REST API
 - Updated guest ORDER REST API to return `masked_order_id` instead of `order_id`
 - Added `is_selected` field to filter options.
 - Selected filter are also returned with available filter options.
 - Fixed the issue with category filter.
## v3.0.0 (2019-02-14)
 - Removed `V1/contact` API
 - Removed `V1/info` API
 - Renamed customer order API from `/V1/carts/mine/deity-order` to `/V1/carts/mine/place-order`
 - Introduced API tests for customer order API
 - Renamed guest order API from `/V1/guest-carts/:cartId/deity-order` to `/V1/guest-carts/:cartId/place-order`
 - Introduced API tests for guest order API
 - Renamed customer payment API from `/V1/carts/mine/payment-information` to `/V1/carts/mine/save-payment-information-and-order`
 - Introduced API tests for customer payment
 - Renamed guest payment API from `/V1/guest-carts/:cartId/payment-information` to `/V1/guest-carts/:cartId/save-payment-information-and-order`
 - Introduced API tests for guest payment
 - Fixed the issue with guest order placement
 - Refactored Order Id Mask classes. Introduced interfaces, repository. Introduced API tests.
## v2.0.1
 - Added product images to checkout `totals` API
## v2.0
 - refactored BreadCrumbs API
 - Introduced new menu API
 - Introduced new category products API
 - Removed plugin for magento token API
 - Added UrlRewrite API
 - fixed the issues with swagger. Swagger page is functional.
 - cleaned up custom plugins and changes not relevant for Falcon product. ([#11](https://github.com/deity-io/falcon-magento2-development/pull/11)) ([#10](https://github.com/deity-io/falcon-magento2-development/pull/10))
 - fixed the issue with installing module on Magento EE edition.
### Deity_UrlRewriteApi v1.0.0
 - Existing interfaces extracted to dedicated module
 - changed the `/url` API specification, ambiguous fields, `cms`, `product`, `custom`, `category` removed.
 - new version returns [`entity_type`, `entity_id`, `canonical_url`]. See swagger for more details
 - introduced API tests
### Deity_Store v1.0.0
 - existing interface extracted to dedicated namespace
 - added `api_version` to existing API
 - introduced API tests ([#7](https://github.com/deity-io/falcon-magento2-development/pull/7))
 
## v1.0.2 (2018-10-05)

- Add endpoints for newsletter subscribe and unsubscribe
- Fix class name collisions in `Deity\MagentoApi\Helper\Breadcrumbs`

## v1.0.0

- Deity_MagentoApi initial release