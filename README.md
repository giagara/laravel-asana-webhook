# Laravel asana webhook integration

This package let you integrate easily with Asana Webhooks.

## Installation

You can install the package via composer:

```bash
composer require giagara/asana-webhook
```

Publish config file with

```bash
php artisan vendor:publish --tags=asana-webhook
```

## Usage

First of all you need to configure the personal access token to communicate with Asana.
You can get it here: [https://developers.asana.com/docs/personal-access-token](https://developers.asana.com/docs/personal-access-token)

Then add it in your .env file

```bash
ASANA_PERSONAL_ACCESS_TOKEN=your_token
```

Then you need to configure the routing by adding items to the `route` array in config file.

```php
'routes' => [
    'webhook' => AnInvokableClass::class
],
```

NOTE: the invokable class must implements `AsanaActionInterface`.

Example: 
```php
use Giagara\AsanaWebhook\Contracts\AsanaActionInterface;

class FakeInvokableClass implements AsanaActionInterface
{
    public function __invoke(array $payload) : void
    {

        // do something

    }

}
```

After this the route api/webhook will be mapped to your invokable class.

## Create a new webhook in Asana

To create a new webhook in Asana you can use the `CreateWebhookCommand` by calling:

```bash
php artisan make:asana-webhook
```

It will ask for resource id (see Asana documentation) and target url (that should be already reachable by Asana).

You can pass the parameters in command directly with:

```bash
php artisan make:asana-webhook --resource=1234 --target=https://example.com/api/webhook
```


### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email giagara@yahoo.it instead of using the issue tracker.

## Credits

-   [Garavaglia Giacomo](https://github.com/giagara)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
