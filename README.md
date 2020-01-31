# Unique Codes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nextapps-be/unique-codes.svg?style=flat-square)](https://packagist.org/packages/nextapps-be/unique-codes)
[![Build Status](https://img.shields.io/travis/nextapps-be/unique-codes/master.svg?style=flat-square)](https://travis-ci.org/nextapps-be/unique-codes)
[![Quality Score](https://img.shields.io/scrutinizer/g/nextapps-be/unique-codes.svg?style=flat-square)](https://scrutinizer-ci.com/g/nextapps-be/unique-codes)
[![Total Downloads](https://img.shields.io/packagist/dt/nextapps-be/unique-codes.svg?style=flat-square)](https://packagist.org/packages/nextapps-be/unique-codes)

This package generates unique, random-looking codes, which you can use for vouchers, coupons, ...

``` php
use NextApps\UniqueCodes\UniqueCodes;

// Generate 100 unique codes
$codes = (new UniqueCodes())
    ->setPrime(184259)
    ->setMaxPrime(7230323)
    ->setCharacters('LQJCKZM4WDPT69S7XRGANY23VBH58F1')
    ->setLength(6)
    ->generate(1, 100);

// Result: LQJCKZ, HYW4LQ, Y9GXLQ,...
```

## Installation

You can install the package via composer:

```bash
composer require nextapps/unique-codes
```

## Usage

Certain setters are required to generate unique codes:
* `setPrime()`
* `setMaxPrime()`
* `setCharacters()`
* `setLength()`

### setPrime($number)

This prime number is used to obfuscate a number between 1 and the max prime number.

### setMaxPrime($number)

The max prime determines the maximum amount of unique codes you can generate. If you provide `101`, then you can generate codes from 1 to 100.
This prime number must be bigger than the prime number you provide to the `setPrime` method.

### setCharacters($string)

The character list contains all the characters that can be used to build a unique code.

### setLength($number)

The length of each unique code.

### setPrefix($string)

The prefix of each unique code.

### setSuffix($string)

The suffix of each unique code.

### setDelimiter($string, $number)

The code can be split in different pieces and glued together using the specified delimiter.

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Günther Debrauwer](https://github.com/gdebrauwer)
- [Evert Arnould](https://github.com/earnould)
- [All Contributors](../../contributors)

This package is heavily inspired by 2 articles written by Jim Mischel:
- [How to generate unique “random-looking” keys
](https://web.archive.org/web/20170730030023/http://blog.mischel.com/2017/06/20/how-to-generate-random-looking-keys/)
- [How not to generate unique codes](https://web.archive.org/web/20170823111437/http://blog.mischel.com/2017/05/30/how-not-to-generate-unique-codes/)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
