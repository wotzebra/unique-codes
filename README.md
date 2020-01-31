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

#### setPrime($number)

This prime number is used to obfuscate a number between 1 and the max prime number.

#### setMaxPrime($number)

The max prime determines the maximum amount of unique codes you can generate. If you provide `101`, then you can generate codes from 1 to 100.
This prime number must be bigger than the prime number you provide to the `setPrime` method.

#### setCharacters($string)

The character list contains all the characters that can be used to build a unique code.

#### setLength($number)

The length of each unique code.

#### setPrefix($string)

The prefix of each unique code.

#### setSuffix($string)

The suffix of each unique code.

#### setDelimiter($string, $number)

The code can be split in different pieces and glued together using the specified delimiter.

## How does it work?

The code generation consists of 2 steps:
- Obfuscating sequential numbers
- Encoding the obfuscated number

If you encode sequential numbers, you will still see that the encoded strings are sequential. To remove the sequential nature, we use 'modular multiplicative inverse'.

You define the upper limit of your range. This determines how max number you can obfuscate. Then every number is mapped to a unique obfuscated number between 1 and the upper limit. You multiply the input number with a random prime number, and you determine the remainder of the division of your multiplied input number by the upper limit of the range.

```
$obfuscatedNumber = ($inputNumber * $primeNumber) % $maxPrimeNumber
```

In the next step, the obfuscated number is encoded to string.

```
$string = '';
$characters = 'LQJCKZM4WDPT69S7XRGANY23VBH58F1';

for ($i = 0; $i < $this->length; $i++) {
    $digit = $number % strlen($characters);

    $string .= $characters[$digit];
    $characters = strtr($characters, [$characters[$digit] => '']);

    $number = $number / strlen($characters);
}

return $string;
```

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
