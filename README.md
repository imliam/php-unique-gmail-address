# Unique Gmail Address

[![Latest Version on Packagist](https://img.shields.io/packagist/v/imliam/php-unique-gmail-address.svg?style=flat-square)](https://packagist.org/packages/imliam/php-unique-gmail-address)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/imliam/php-unique-gmail-address/Tests?label=tests)](https://github.com/imliam/php-unique-gmail-address/actions?query=workflow%3ATests)
[![Total Downloads](https://img.shields.io/packagist/dt/imliam/php-unique-gmail-address.svg?style=flat-square)](https://packagist.org/packages/imliam/php-unique-gmail-address)

A package to ensure that a Gmail address is unique.

The Gmail platform offers some features that other email platforms don't. There can be an infinite number of different Gmail addresses that point to a single Gmail inbox/account, which can make it very easy for malicious users to abuse with no effort.

To learn more about how a user can make a infinite addresses for one Gmail inbox, [check out this article](https://liamhammett.com/make-infinite-gmail-addresses-for-one-inbox-nqoVprjX).

This package makes it possible to **detect duplicate addresses for one Gmail account** and to ensure they're unique.

## Installation

You can install the package via composer:

```bash
composer require imliam/php-unique-gmail-address
```

## Usage

The primary usage of this package revolves around the supplied `UniqueGmailAddress` class, which can be passed an email address to compare:

``` php
$email = new UniqueGmailAddress('example@gmail.com');
```

The `isGmailAddress` method will check if the given address belongs to a Gmail account:

``` php
$one = new UniqueGmailAddress('example@gmail.com');
$one->isGmailAddress(); // true

$two = new UniqueGmailAddress('example@google.com');
$two->isGmailAddress(); // true

$three = new UniqueGmailAddress('example@example.com');
$three->isGmailAddress(); // false
```

The `normalizeAddress` method will take an email address and normalize it to the simplest variation:

```php
$email = new UniqueGmailAddress('ex.am.ple+helloworld@googlemail.com');
$email->normalizeAddress(); // example@gmail.com
```

The `getRegex` and `getRegexWithDelimeters` methods will return a regular expression that can be used to compare the original email address to another one, using a function like `preg_match`:

```php
$email = new UniqueGmailAddress('example@gmail.com');
$email->getRegex(); // ^e(\.?)+x(\.?)+a(\.?)+m(\.?)+p(\.?)+l(\.?)+e(\+.*)?\@(gmail|googlemail).com$
```

The `matches` method will immediately match the regular expression against another value, returning `true` if both email addresses belong to the same Gmail account:

```php
$email = new UniqueGmailAddress('example@gmail.com');
$email->matches('ex.am.ple+helloworld@googlemail.com'); // true
```

## Laravel Rule

One of the most common use cases for wanting to check duplicate Gmail accounts is to prevent multiple users signing up to your service with the same email address.

To handle this case, the package also provides a [Laravel validation rule class](https://laravel.com/docs/master/validation) that can be used to check the database if a matching email address is already present:

```php
$request->validate([
    'email' => [new UniqueGmailAddressRule()],
]);
```

By default, it will check the `email` column in the `users` table, but these can be overriden if needed when using the rule:

```php
$request->validate([
    'email' => [new UniqueGmailAddressRule('contacts', 'email_address')],
]);
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email liam@liamhammett.com instead of using the issue tracker.

## Credits

- [Liam Hammett](https://github.com/ImLiam)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
