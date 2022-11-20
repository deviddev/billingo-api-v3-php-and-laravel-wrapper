# Billingo API V3 Laravel and PHP Wrapper

This is a simple Laravel (PHP) wrapper for Billingo (billingo.hu) API V3 SwaggerHUB PHP SDK.

Compatible with: Laravel 6.x (LTS), Laravel 8.x and 9.x or PHP 7.4<=

**You can use the wrapper easily with all type of PHP projects (not just Laravel) from 1.0.0 version without changing except downloadInvoice method.**

# Installation

You can install the package via composer or just download it:

```bash
composer require deviddev/billingo-api-v3-wrapper
```

# Usage

### Laravel

Publish config file:

`php artisan vendor:publish --provider=Deviddev\BillingoApiV3Wrapper\BillingoApiV3WrapperServiceProvider --tag="config"`

**First set up your Billingo API V3 key in ./config/billingo-api-v3-wrapper.php config file.**

Import wrapper with facade:

```php
use BillingoApiV3Wrapper as BillingoApi;
```

### PHP

Import wrapper:

```php
use Deviddev\BillingoApiV3Wrapper\BillingoApiV3Wrapper as BillingoApi;
```

You must add your api key to BillingoApi object constructor:

```php
$billingoApi = new BillingoApi('YOUR_API_KEY_HERE');
```

# Available methods

Make an api instance (eg.: BankAccount, Currency, Document, DocumentBlock, DocumentExport, Organization, Partner, Product, Util):

```php
api(string $apiName);
```

Add some data to model:

```php
make(array $data);
```

Make a model instance (eg: Address, BankAccount, Currency, Document, etc... - see in ./vendor/deviddev/billingo-api-v3-php-sdk/lib/model):

```php
model(string $modelName, array $data = null);
```

(if you don't want use **make()** method simply add necessary data as second parameter)

With http info (get http info (headers, response code, etc...)):

```php
withHttpInfo();
```

Get invoice, partner, product(call api class method with model instance):

```php
get(int $id);
```

Delete partner, product (call api class method with model instance):

```php
delete(int $id);
```

Delete payment (call api class method with model instance):

```php
deletePayment(int $id);
```

Create model (call api class method with model instance):

```php
create();
```

Update model (call api class method with model instance and model id):

```php
update(int $id);
```

Cancel invoice:

```php
cancelInvoice(int $invoiceId);
```

Create invoice from proforma invoice:

```php
createInvoiceFromProforma(int $invoiceId);
```

Check tax number:

```php
checkTaxNumber(string $taxNumber);
```

List model (call api class method with model instance):

```php
list(array $conditions);
```

\*\*\* All conditions is optional!

Download invoice to server:

```php
downloadInvoice(int $invoiceId, string $path = null, string $extension = null);
```

Send invoice in email:

```php
sendInvoice(int $invoiceId);
```

Get invoice public url response:

```php
getPublicUrl(int $id);
```

Get Billingo API response:

```php
getResponse();
```

Get Billingo API response id (eg.: partner id, invoice id, etc.):

```php
getId();
```

### Method chaining:

All pulic methods are chainable, except **getResponse()** and **getId()** methods.
If you don't add some data to `model(string $modelName, array $data = null)` method second `array $data = null` parameter, you **MUST** use `make(array $data)` method **BEFORE** `model()` method, see in examples.
The `withHttpInfo()` method **MUST** be called **IMMEDIATELY AFTER** the `api()`, `make()` or `model()` methods.

# Examples

### Create partner example:

Partner array:

```php
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

#### Laravel

Create partner and get response:

```php
BillingoApi::api('Partner')->model('PartnerUpsert', $partner)->create()->getResponse();
```

OR

Create partner with make and get response:

```php
BillingoApi::api('Partner')->make($partner)->model('PartnerUpsert')->create()->getResponse();
```

OR

Create partner and get partner id:

```php
BillingoApi::api('Partner')->model('PartnerUpsert', $partner)->create()->getId();
```

OR

Create partner with make and get partner id:

```php
BillingoApi::api('Partner')->make($partner)->model('PartnerUpsert')->create()->getId();
```

#### PHP

Create partner and get response:

```php
$billingoApi->api('Partner')->model('PartnerUpsert', $partner)->create()->getResponse();
```

OR

Create partner with make and get response:

```php
$billingoApi->api('Partner')->make($partner)->model('PartnerUpsert')->create()->getResponse();
```

OR

Create partner and get partner id:

```php
$billingoApi->api('Partner')->model('PartnerUpsert', $partner)->create()->getId();
```

OR

Create partner with make and get partner id:

```php
$billingoApi->api('Partner')->make($partner)->model('PartnerUpsert')->create()->getId();
```

### Update partner example:

Partner array:

```php
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

#### Laravel

Update partner and get response:

```php
BillingoApi::api('Partner')->model('Partner', $partner)->update('BILLINGO_PARTNER_ID')->getResponse();
```

OR

Update partner with make and get response:

```php
BillingoApi::api('Partner')->make($partner)->model('Partner')->update('BILLINGO_PARTNER_ID')->getResponse();
```

OR

Update partner and get partner id:

```php
BillingoApi::api('Partner')->model('Partner', $partner)->update('BILLINGO_PARTNER_ID')->getId();
```

OR

Update partner with make and get partner id:

```php
BillingoApi::api('Partner')->make($partner)->model('Partner')->update('BILLINGO_PARTNER_ID')->getId();
```

#### PHP

Update partner and get response:

```php
$billingoApi->api('Partner')->model('Partner', $partner)->update('BILLINGO_PARTNER_ID')->getResponse();
```

OR

Update partner with make and get response:

```php
$billingoApi->api('Partner')->make($partner)->model('Partner')->update('BILLINGO_PARTNER_ID')->getResponse();
```

OR

Update partner and get partner id:

```php
$billingoApi->api('Partner')->model('Partner', $partner)->update('BILLINGO_PARTNER_ID')->getId();
```

OR

Update partner with make and get partner id:

```php
$billingoApi->api('Partner')->make($partner)->model('Partner')->update('BILLINGO_PARTNER_ID')->getId();
```

### Create invoice example:

Invoice array:

```php
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

#### Laravel

Create invoice and get response:

```php
BillingoApi::api('Document')->model('DocumentInsert', $invoice)->create()->getResponse();
```

OR

Create invoice with make and get response:

```php
BillingoApi::api('Document')->make($invoice)->model('DocumentInsert')->create()->getResponse();
```

OR

Create invoice and get invoice id:

```php
BillingoApi::api('Document')->model('DocumentInsert', $invoice)->create()->getId();
```

OR

Create invoice with make and get invoice id:

```php
BillingoApi::api('Document')->make($invoice)->model('DocumentInsert')->create()->getId();
```

#### PHP

Create invoice and get response:

```php
$billingo->api('Document')->model('DocumentInsert', $invoice)->create()->getResponse();
```

OR

Create invoice with make and get response:

```php
$billingo->api('Document')->make($invoice)->model('DocumentInsert')->create()->getResponse();
```

OR

Create invoice and get invoice id:

```php
$billingo->api('Document')->model('DocumentInsert', $invoice)->create()->getId();
```

OR

Create invoice with make and get invoice id:

```php
$billingo->api('Document')->make($invoice)->model('DocumentInsert')->create()->getId();
```

### List invoices, partners, blocks, etc example:

#### Laravel

List invoices:

```php
BillingoApi::api('Document')->list([
    'page' => 1,
    'page' => 25,
    'block_id' => 42432,
    'partner_id' => 13123123,
    'payment_method' => 'cash',
    'payment_status' => 'paid',
    'start_date'] => '2020-05-10',
    'end_date' => '2020-05-15',
    'start_number' => '1',
    'end_number' => '10',
    'start_year' => 2020,
    'end_year'] => 2020
])->getResponse();
```

List partners:

```php
BillingoApi::api('Partner')->list([
    'page' => 1,
    'per_page' => 5
])->getResponse();
```

List blocks:

```php
BillingoApi::api('DocumentBlock')->list([
    'page' => 1,
    'per_page' => 5
])->getResponse();
```

List banks accounts:

```php
BillingoApi::api('BankAccount')->list([
    'page' => 1,
    'per_page' => 5
])->getResponse();
```

List products:

```php
BillingoApi::api('Products')->list([
    'page' => 1,
    'per_page' => 5
])->getResponse();
```

#### PHP

List invoices:

```php
$billingoApi->api('Document')->list([
    'page' => 1,
    'page' => 25,
    'block_id' => 42432,
    'partner_id' => 13123123,
    'payment_method' => 'cash',
    'payment_status' => 'paid',
    'start_date'] => '2020-05-10',
    'end_date' => '2020-05-15',
    'start_number' => '1',
    'end_number' => '10',
    'start_year' => 2020,
    'end_year'] => 2020
])->getResponse();
```

List partners:

```php
$billingoApi->api('Partner')->list([
    'page' => 1,
    'per_page' => 5
])->getResponse();
```

List blocks:

```php
$billingoApi->api('DocumentBlock')->list([
    'page' => 1,
    'per_page' => 5
])->getResponse();
```

List banks accounts:

```php
$billingoApi->api('BankAccount')->list([
    'page' => 1,
    'per_page' => 5
])->getResponse();
```

List products:

```php
$billingoApi->api('Products')->list([
    'page' => 1,
    'per_page' => 5
])->getResponse();
```

### Download invoice example:

#### Laravel

Default path is: `./storage/app/invoices`

Default extension is: `.pdf`

File name is invoice id.

Return the path in the response, eg.:

```php
path: "invoices/11246867.pdf"
```

Download invoice:

```php
BillingoApi::api('Document')->downloadInvoice(INVOICE_ID)->getResponse();
```

OR

Download to specified path and extension:

```php
BillingoApi::api('Document')->downloadInvoice(INVOICE_ID, 'PATH', 'EXTENSION')->getResponse();
```

#### PHP

Come in version 1.1.

### Send invoice in e-mail example:

Return the e-mails array where to send the invoce, eg.:

```php
emails: [
    "kiss@kft.hu"
]
```

#### Laravel

Send invoice:

```php
BillingoApi::api('Document')->sendInvoice(INVOICE_ID)->getResponse();
```

#### PHP

Send invoice:

```php
$billingoApi->api('Document')->sendInvoice(INVOICE_ID)->getResponse();
```

### Get invoice public url example:

Return the public url array, eg.:

```php
[
    public_url: "https://api.billingo.hu/document-access/K3drE0Gvb2eRwQNYlypfasdOlJADB4Y"
]
```

#### Laravel

Get invoice public url:

```php
BillingoApi::api('Document')->getPublicUrl(INVOICE_ID)->getResponse();
```

#### PHP

Get invoice public url:

```php
$billingoApi->api('Document')->getPublicUrl(INVOICE_ID)->getResponse();
```

### Cancel invoice example:

#### Laravel

Cancel invoice:

```php
BillingoApi::api('Document')->cancelInvoice(INVOICE_ID)->getResponse();
```

#### PHP

Cancel invoice:

```php
$billingoApi->api('Document')->cancelInvoice(INVOICE_ID)->getResponse();
```

### Create invoice from proforma invoice example:

#### Laravel

Create invoice from proforma invoice:

```php
BillingoApi::api('Document')->createInvoiceFromProforma(INVOICE_ID)->getResponse();
```

#### PHP

Create invoice from proforma invoice:

```php
$billingoApi->api('Document')->createInvoiceFromProforma(INVOICE_ID)->getResponse();
```

### Check tax number example:

First set up your NAV connection in your billingo account, because it always return "Invalid tax number!"

#### Laravel

Check tax number:

```php
BillingoApi::api('Util')->checkTaxNumber('tax_number')->getResponse();
```

#### PHP

Check tax number:

```php
$billingoApi->api('Util')->checkTaxNumber('tax_number')->getResponse();
```

### Get invoice, product, partner example:

#### Laravel

Get invoice:

```php
BillingoApi::api('Document')->get(INVOICE_ID)->getResponse();
```

Get partner:

```php
BillingoApi::api('Partner')->get(PARTNER_ID)->getResponse();
```

Get product:

```php
BillingoApi::api('Product')->get(PRODUCT_ID)->getResponse();
```

#### PHP

Get invoice:

```php
$billingoApi->api('Document')->get(INVOICE_ID)->getResponse();
```

Get partner:

```php
$billingoApi->api('Partner')->get(PARTNER_ID)->getResponse();
```

Get product:

```php
$billingoApi->api('Product')->get(PRODUCT_ID)->getResponse();
```

### Delete product, partner example:

#### Laravel

Delete partner:

```php
BillingoApi::api('Partner')->delete(PARTNER_ID)->getResponse();
```

Delete product:

```php
BillingoApi::api('Product')->get(PRODUCT_ID)->getResponse();
```

#### PHP

Delete partner:

```php
$billingoApi->api('Partner')->delete(PARTNER_ID)->getResponse();
```

Delete product:

```php
$billingoApi->api('Product')->get(PRODUCT_ID)->getResponse();
```

### Delete payment example:

#### Laravel

Get partner:

```php
BillingoApi::api('Partner')->deletePayment(PAYMENT_ID)->getResponse();
```

#### PHP

Get partner:

```php
$billingoApi->api('Partner')->deletePayment(PAYMENT_ID)->getResponse();
```

### With http info example:

#### Laravel

With http info:

```php
BillingoApi::api('Product')->withHttpInfo()->list(['page' => 1, 'per_page' => 5])->getResponse();
```

#### PHP

With http info:

```php
$billingoApi->api('Product')->withHttpInfo()->list(['page' => 1, 'per_page' => 5])->getResponse();
```

## Testing

First set up your Billingo API V3 Key in `config/config.php` file.

Linux, MAC OS

```
$ ./vendor/bin/phpunit
```

OR

```
$ composer test
```

Windows

```
$ vendor\bin\phpunit
```

OR

```
$ composer test-win
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email david.molnar.mega@gmail.com instead of using the issue tracker.

## Credits

-   [David Molnar](https://github.com/deviddev)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
