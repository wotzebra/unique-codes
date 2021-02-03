# Unique Codes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nextapps/unique-codes.svg?style=flat-square)](https://packagist.org/packages/nextapps/unique-codes)
[![GitHub 'Run Tests' Workflow Status](https://img.shields.io/github/workflow/status/nextapps-be/unique-codes/run-tests?label=tests&style=flat-square&logo=github)](https://github.com/nextapps-be/unique-codes/actions?query=workflow%3Arun-tests)
[![Total Downloads](https://img.shields.io/packagist/dt/nextapps/unique-codes.svg?style=flat-square)](https://packagist.org/packages/nextapps/unique-codes)

This package generates unique, random-looking codes. These codes can be used for vouchers, coupons, ...
You can now generate thousands or millions of codes without having to check if a code has already been generated in the past.

``` php
use NextApps\UniqueCodes\UniqueCodes;

// Generate 100 unique codes for numbers 1 to 100
$codes = (new UniqueCodes())
    ->setObfuscatingPrime(9006077)
    ->setMaxPrime(7230323)
    ->setCharacters('LQJCKZMWDPTSXRGANYVBHF')
    ->setLength(6)
    ->generate(1, 100);

// Result: LWXNHJ (1), LACSVK (2), QLNMNM (3), ... , LYMJHL (100), QJVBVJ (101), LQXGQC (102), ... , LJQ5DJ (7230320), LC17CS (7230321), LZ8J8H (7230322)
```

## Installation

You can install the package via composer:

```bash
composer require nextapps/unique-codes
```
> Do not use v1 of this package, as it contains bugs. If you currently use v1, you should upgrade to v2 (Read the upgrading guide!).

## Usage

### Generate() method

To generate unique codes, you provide a start and end number. It will generate codes using the numbers in that range. Each number will always generate the same unique, random-looking code. This means you should ensure you never use the same numbers again. This could be achieved by saving which number ranges you already used or by always using the next auto-incrementing id in your codes database table.

If a lot of codes need to be generated at the same time, it can cause a lot of memory usage. In order to prevent this, a [Generator](https://www.php.net/manual/en/class.generator.php) is returned by default. If you want an array instead, you can set the third parameter of the `generate` method to `true`. If you want to generate one code based on one number, you only have to set the first parameter of the `generate` method.

```php
// Returns generator to create codes for numbers 1 to 100.
->generate(1, 100);

// Returns array with created codes for numbers 1 to 100.
->generate(1, 100, true);

// Returns string with created code for number 100.
->generate(100);
```

### Setters

Certain setters are required to generate unique codes:
* `setObfuscatingPrime()`
* `setMaxPrime()`
* `setCharacters()`
* `setLength()`

#### setObfuscatingPrime($number)

This prime number is used to obfuscate a number between 1 and the max prime number. This prime number must be bigger than your max prime number (which you provide to the `setMaxPrime` method).

#### setMaxPrime($number)

The max prime determines the maximum amount of unique codes you can generate. If you provide `101`, then you can generate codes from 1 to 100.
This prime number must be smaller than the prime number you provide to the `setObfuscatingPrime` method.

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

#### Obfuscating sequential numbers

If you encode sequential numbers, you will still see that the encoded strings are sequential. To remove the sequential nature, we use 'modular multiplicative inverse'.

You define the upper limit of your range. This determines the maximum number you can obfuscate. Then every number is mapped to a unique obfuscated number between 1 and the upper limit. You multiply the input number with a random (larger) prime number, and you determine the remainder of the division of your multiplied input number by the upper limit of the range.

```
$obfuscatedNumber = ($inputNumber * $obfuscatingPrimeNumber) % $maxPrimeNumber
```

#### Encoding the obfuscated number

In the next step, the obfuscated number is encoded to string. This is just a base conversion using division and modulo.

## Testing

``` bash
composer test
```

## Linting

```bash
composer lint
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
