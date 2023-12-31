# IntegerNet_RegenerateCouponUses Magento Module
<div align="center">

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
![Supported Magento Versions][ico-compatibility]

</div>

---

Regenerate coupon uses via command line so coupons cannot be used twice.

This can be used  if the Message Queue consumer `sales.rule.update.coupon.usage` wasn't running successfully and coupon usages haven't been recorded.
Make sure that the consumer runs again after this command has been executed.

## Installation

1. Install it into your Magento 2 project with composer:
    ```
    composer require integer-net/magento2-regenerate-coupon-uses
    ```

2. Enable module
    ```
    bin/magento setup:upgrade
    ```

## Usage

Execute on the command line:

    bin/magento coupon:usage:regenerate

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email avs@integer-net.de instead of using the issue tracker.

## Credits

- [Andreas von Studnitz][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/integer-net/magento2-regenerate-coupon-uses.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-maintainability]: https://img.shields.io/codeclimate/maintainability/integer-net/magento2-regenerate-coupon-uses?style=flat-square
[ico-compatibility]: https://img.shields.io/badge/magento-2.4-brightgreen.svg?logo=magento&longCache=true&style=flat-square

[link-packagist]: https://packagist.org/packages/integer-net/magento2-regenerate-coupon-uses
[link-author]: https://github.com/avstudnitz
[link-contributors]: ../../contributors
