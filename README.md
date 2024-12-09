# This is my package filament-js-money-field

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tuxones/filament-js-money-field.svg?style=flat-square)](https://packagist.org/packages/tuxones/filament-js-money-field)
[![Total Downloads](https://img.shields.io/packagist/dt/tuxones/filament-js-money-field.svg?style=flat-square)](https://packagist.org/packages/tuxones/filament-js-money-field)


A Filament plugin for dynamic international currency masking using JavaScript Intl, supporting flexible currency and locale configuration via closures.

This plugin extends the functionality of a standard text field by adding a dynamic currency mask. All properties and behaviors of the standard field remain intact.

## Supported Column Types

The plugin supports `integer`, `decimal`, `double`, and `float`. However, it is **highly recommended** to use `integer` or `decimal` for better precision and consistency when handling currency values.

If the column is of type `integer`, the entered value is stored as an integer, including the decimal cents, without any currency symbols or formatting.


## Installation

You can install the package via composer:

```bash
composer require tuxones/filament-js-money-field
```

## Usage

### Form

```php
use Tuxones\JsMoneyField\Forms\Components\JSMoneyInput;

JSMoneyInput::make('consumption_limit')
    ->currency('USD') // ISO 4217 Currency Code, example: USD
    ->locale('en-US') // BCP 47 Locale Code, example: en-US
    
// OR

JSMoneyInput::make('consumption_limit')
    ->hidden(fn (Get $get) => !$get('country'))
    ->currency(fn (Get $get) => $get('country') ? Country::find($get('country'))->currency : 'USD') 
    ->locale(fn (Get $get) => $get('country') ? Country::find($get('country'))->locale : 'en-US')

```

### Table column

```php
use Tuxones\JsMoneyField\Tables\Columns\JSMoneyColumn;

JSMoneyColumn::make('consumption_limit')
    ->currency('USD') // ISO 4217 Currency Code, example: USD
    ->locale('en-US') // BCP 47 Locale Code, example: en-US
    
// OR

JSMoneyColumn::make('consumption_limit')
    ->currency(fn (Model $record) => $record->country ? $record->country->currency : 'USD')
    ->locale(fn (Model $record) => $record->country ? $record->country->locale : 'en-US')

```


### InfoList

```php
use Tuxones\JsMoneyField\Infolists\Components\JSMoneyEntry;

JSMoneyEntry::make('consumption_limit')
    ->currency('USD') // ISO 4217 Currency Code, example: USD
    ->locale('en-US') // BCP 47 Locale Code, example: en-US
    
// OR

JSMoneyEntry::make('consumption_limit')
    ->currency(fn (Model $record) => $record->country ? $record->country->currency : 'USD')
    ->locale(fn (Model $record) => $record->country ? $record->country->locale : 'en-US')

```


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
