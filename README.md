# Laravel asana webhook integration

![CI/CD](https://github.com/giagara/laravel-asana-webhook/actions/workflows/main.yml/badge.svg?branch=main)
![Packagist](https://img.shields.io/packagist/v/giagara/laravel-asana-webhook.svg)


This package let you integrate easily with Asana Webhooks.

## Installation

Install the package via composer:

```bash
composer require giagara/laravel-asana-webhook
```

Publish config file with

```bash
php artisan vendor:publish --tags=asana-webhook
```

## Usage

First of all you need to configure the personal access token to communicate with Asana.
You can get it here: [https://developers.asana.com/docs/personal-access-token](https://developers.asana.com/docs/personal-access-token)

Add it in your .env file

```bash
ASANA_PERSONAL_ACCESS_TOKEN=your_token
```

Configure the routing by adding items to the `route` array in config file.

```php
'routes' => [
    'webhook' => AnInvokableClass::class
],
```

Alternatively the configuration can support name and middlewares
```php
'routes' => [
    'webhook' => [
        'class' => AnInvokableClass::class,
        'name' => 'webhook-1',
        'middleware' => [CustomMiddleware::class],
    ]
]
```

NOTE: the invokable class must implements `AsanaActionInterface`.
NOTE: the route is forced to use `api/` prefix so, in this case, the final path will be `api/webhook`.

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

## Available commands

### Webhook list

```bash
php artisan asana:list-webhook
```

NOTE: For this feature create `ASANA_WORKSPACE_ID` field in .env file.

get workspace id by calling [this url](https://app.asana.com/api/1.0/workspaces)


### Webhook creation

To create a new webhook in Asana you can use the `CreateWebhookCommand` by calling:

```bash
php artisan asana:create-webhook
```

It will ask for resource id (see Asana documentation) and target url (that should be already reachable by Asana).

You can pass the parameters in command directly with:

```bash
php artisan asana:create-webhook --resource=1234 --target=https://example.com/api/webhook
```


### Delete Webhook

```bash
php artisan asana:delete-webhook {webhook_gid}
```


### TODO

- [x] Create webhook list command
- [x] Delete a webhook via command
- [x] Add middleware to route configs
- [x] Add name to route config
- [ ] Trigger via route name

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
