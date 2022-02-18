# Unified log, metric, and trace analytics for any stack

[![Latest Version on Packagist](https://img.shields.io/packagist/v/robmellett/laravel-logzio.svg?style=flat-square)](https://packagist.org/packages/robmellett/laravel-logzio)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/robmellett/laravel-logzio/run-tests?label=tests)](https://github.com/robmellett/laravel-logzio/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/robmellett/laravel-logzio/Check%20&%20fix%20styling?label=code%20style)](https://github.com/robmellett/laravel-logzio/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/robmellett/laravel-logzio.svg?style=flat-square)](https://packagist.org/packages/robmellett/laravel-logzio)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-logzio.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-logzio)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require robmellett/laravel-logzio
```

## Laravel Usage

In `config/logging.php file`, config your log driver with `logzio`.

```php
<?php

return [
    // ...
	'logzio' => [
	    'driver' => 'logzio',
	    'name'   => 'channel-name',
	    'token'  => 'logz-access-token',
	    'type'   => 'http-bulk',
	    'ssl'    => true,
	    'level'  => 'info',
	    'bubble' => true,
	    'region' => 'au', // leave empty for default region, or if you are on a trial
	],
	// ...
];
```
You can use the log facade in the following way.

```php
Log::channel('logzio')->info('Some message');
```
## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Rob Mellett](https://github.com/robmellett)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
