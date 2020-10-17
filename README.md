# Billingo API V3 Laravel Wrapper

This is a simple Laravel wrapper for Billingo (billingo.hu) API V3 SwaggerHUB PHP SDK.

Compatible with: Laravel 6.x (LTS), Laravel 7.x and Laravel 8.x

## Installation

You can install the package via composer:

```bash
composer require deviddev/billingo-api-v3-wrapper
```

## Usage

Publish config file:

`
php artisan vendor:publish --provider=Deviddev\BillingoApiV3Wrapper\BillingoApiV3WrapperServiceProvider
`

**First set up your Billingo API V3 key to ./config/billingo-api-v3-wrapper.php config file.**

Import wrapper:
``` php
use BillingoApiV3Wrapper as BillingoApi;
```

Make an api instance (eg.: BankAccount, Currency, Document, DocumentBlock, DocumentExport, Organization, Partner, Product, Util):
``` php
BillingoApiV3Wrapper::api(string $apiName);
```

Add some data to model:
``` php
BillingoApiV3Wrapper::make(array $data);
```

Make a model instance (eg: Address, BankAccount, Currency, Document, etc... - see in ./vendor/deviddev/billingo-api-v3-php-sdk/lib/model):
``` php
BillingoApiV3Wrapper::model(string $modelName, array $data = null);
```
(if you don't want use **make()** method simply add necessary data as second parameter)

Create model (call api class method with model instance):
``` php
BillingoApiV3Wrapper::create();
```

Update model (call api class method with model instance and model id):
``` php
BillingoApiV3Wrapper::update(int $id);
```

Download invoice to server:
``` php
BillingoApiV3Wrapper::downloadInvoice(int $invoiceId, string $path = null, string $extension = null);
```

Send invoice in email:
``` php
BillingoApiV3Wrapper::sendInvoice(int $invoiceId);
```

Get Billingo API response:
``` php
BillingoApiV3Wrapper::getResponse();
```

##### Method chaining:
All pulic methods are chainable, except **getResponse()** method.
If you don't add some data to ``` model(string $modelName, array $data = null) ``` method second ``` array $data = null ``` parameter, you must use ``` make(array $data) ``` method before ``` model() ``` method, see in examples.

### Examples

**Create partner example:**

Partner array:
``` php
$partner = [
    'name' => 'Test Company',
    'address' => [
        'country_code' => 'HU',
        'post_code' => '1010',
        'city' => 'Budapest',
        'address' => 'Nagy Lajos 12.',
    ],
    'emails' => ['test@company.hu'],
    'taxcode' => '',
];
```

Create partner:
``` php
BillingoApi::api('Partner')->model('PartnerUpsert', $partner)->create()->getResponse();
```

OR

Create partner with make:
``` php
BillingoApi::api('Partner')->make($partner)->model('PartnerUpsert')->create()->getResponse();
```

**Update partner example:**

Partner array:
``` php
$partner = [
    'name' => 'Test Company updated',
    'address' => [
        'country_code' => 'HU',
        'post_code' => '1010',
        'city' => 'Budapest',
        'address' => 'Nagy Lajos 12.',
    ],
    'emails' => ['test@company.hu'],
    'taxcode' => '',
];
```

Update partner:
``` php
BillingoApi::api('Partner')->model('Partner', $partner)->update('BILLINGO_PARTNER_ID')->getResponse();
```

OR

Update partner with make:
``` php
BillingoApi::api('Partner')->make($partner)->model('Partner')->update('BILLINGO_PARTNER_ID')->getResponse();
```

**Create invoice example:**

Invoice array:
``` php
$invoice = [
    'partner_id' => BILLINGO_PARTNER_ID, // REQUIRED int
    'block_id' => YOUR_BILLINGO_BLOCK_ID, // REQUIRED int
    'bank_account_id' => YOUR_BILLINGO_BANK_ACCOUNT_ID, // int
    'type' => 'invoice', // REQUIRED
    'fulfillment_date' => Carbon::now('Europe/Budapest')->format('Y-m-d'), // REQUIRED, set up other time zone if it's necessaray
    'due_date' => Carbon::now('Europe/Budapest')->format('Y-m-d'), // REQUIRED, set up other time zone if it's necessaray
    'payment_method' => 'online_bankcard', // REQUIRED, see other types in billingo documentation
    'language' => 'hu', // REQUIRED, see others in billingo documentation
    'currency' => 'HUF', // REQUIRED, see others in billingo documentation
    'conversion_rate' => 1, // see others in billingo documentation
    'electronic' => false, // see others in billingo documentation
    'paid' => false, // see others in billingo documentation
    'items' =>  [
        [
            'name' => 'Laptop', // REQUIRED
            'unit_price' => '100000', // REQUIRED
            'unit_price_type' => 'gross', // REQUIRED
            'quantity' => 2, // REQUIRED int
            'unit' => 'db', // REQUIRED
            'vat' => '27%', // REQUIRED
            'comment' => 'some comment here...',
        ],
    ],
    'comment' => 'some comment here...',
    'settings' => [
        'mediated_servicíe' => false,
        'without_financial_fulfillment' => false,
        'online_payment' => '',
        'round' => 'five',
        'place_id' => 0,
    ],
];
```

Create invoice:
``` php
BillingoApi::api('Document')->model('DocumentInsert', $invoice)->create()->getResponse();
```

OR

Create invoice with make:
``` php
BillingoApi::api('Document')->make($invoice)->model('DocumentInsert')->create()->getResponse();
```

**Download invoice example:**

Default path is: ``` ./storage/app/invoices ```
Default extension is: ``` .pdf ```
File name is invoice id.

Download invoice:
``` php
BillingoApi::api('Document')->downloadInvoice(INVOICE_ID)->getResponse();
```

OR

Download to specified path and extension:
``` php
BillingoApi::api('Document')->downloadInvoice(INVOICE_ID, 'PATH', 'EXTENSION')->getResponse();
```

**Send invoice in e-mail example:**

Send invoice:
``` php
BillingoApi::api('Document')->sendInvoice(INVOICE_ID)->getResponse();
```

### Testing

Linux, MAC OS
```
$ ./vendor/bin/phpunit
```

Windows
```
$ vendor\bin\phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email david.molnar.mega@gmail.com instead of using the issue tracker.

## Credits

- [David Molnar](https://github.com/deviddev)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
