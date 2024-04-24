# Filament Questionnaire Builder

[![Latest Version on Packagist](https://img.shields.io/packagist/v/preferredmanagement/filament-questionnaire-builder.svg?style=flat-square)](https://packagist.org/packages/preferredmanagement/filament-questionnaire-builder)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/preferredmanagement/filament-questionnaire-builder/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/preferredmanagement/filament-questionnaire-builder/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/preferredmanagement/filament-questionnaire-builder/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/preferredmanagement/filament-questionnaire-builder/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/preferredmanagement/filament-questionnaire-builder.svg?style=flat-square)](https://packagist.org/packages/preferredmanagement/filament-questionnaire-builder)


This package is designed to make it easy to build question sets and questionnaires in your Filament App.

## Installation

You can install the package via composer:

```bash
composer require preferredmanagement/filament-questionnaire-builder
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-questionnaire-builder-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-questionnaire-builder-config"
```

This is the contents of the published config file:

```php

];
```

## Usage

To use this plugin, add it to your PanelServiceProvider

```php
//  app/Providers/Filament/AdminPanelServiceProvider.php

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ...
            ->plugins([
                FilamentQuestionnaireBuilderPlugin::make()
            ]);
    }
}
```

## Tenancy

This package was build to also be able to function with the Filament concept of multi tenancy.

To enable multi tenancy mode, add the following to your .env file

```ini
FQB_TENANT_MODEL=\App\Models\Team
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

- [James Sessford](https://github.com/jamessessford)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
