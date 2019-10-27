# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/arnissolle/php-mfa.svg?style=flat-square)](https://packagist.org/packages/arnissolle/php-mfa)
[![Build Status](https://img.shields.io/travis/arnissolle/php-mfa/master.svg?style=flat-square)](https://travis-ci.org/arnissolle/php-mfa)
[![Quality Score](https://img.shields.io/scrutinizer/g/arnissolle/php-mfa.svg?style=flat-square)](https://scrutinizer-ci.com/g/arnissolle/php-mfa)
[![Total Downloads](https://img.shields.io/packagist/dt/arnissolle/php-mfa.svg?style=flat-square)](https://packagist.org/packages/arnissolle/php-mfa)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require arnissolle/php-mfa
```

## Usage

``` php

use Arnissolle\MFA\OTP\Auth;
use Arnissolle\MFA\OTP\Code;
use Arnissolle\MFA\OTP\Secret;

// Create new secret
$secret = Secret::create();

// Get the OTP auth URI
$authUri = Auth::uri($secret, 'jdoe@domain.tld', function(Auth $auth) {
    $auth->issuer = 'Company Name';
});

// Get the QR Code
// Then scan it with app like Google Authenticator
$qrCodeUrl = Auth::qrCodeUrl($authUri);

// Get code (or use third party app)
$code = Code::get($secret);

// Verify code (bool)
$verify = Code::verify($secret, $code);
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email pierre@arnissolle.com instead of using the issue tracker.

## Credits

- [Pierre Arnissolle](https://github.com/arnissolle)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).
