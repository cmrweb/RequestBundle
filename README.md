Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
composer require cmrweb/request-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
composer require cmrweb/request-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Cmrweb\RequestBundle\RequestBundle::class => ['all' => true],
];
```

### Config

.env

```env
###> cmrweb/request-bundle ###
API_ROOT_URL="https://.../"
API_KEY="MySuperSecureApiKey"
###< cmrweb/request-bundle ###
```

config/services.yaml

```yaml
parameters:
    # ...
    cmrweb.api.url: '%env(API_ROOT_URL)%'
    cmrweb.api.key: '%env(API_KEY)%'
services:
    # ...
    App\Service\MyRequestService:
            arguments:
                $url: '%cmrweb.api.url%'
```

### usage

```php
namespace App\Service;
 
use Cmrweb\RequestBundle\AbstractApiRequest;

class MyRequestService extends AbstractApiRequest
{ 
    private const string AUTOCOMPLETE = 'autocomplete';
    private const string SEARCH = 'search';

    private const array ROUTES = [
        self::AUTOCOMPLETE => 'geocodage/completion/',
        self::SEARCH => 'geocodage/search'
    ]; 
    // ...
    public function autocomplete(string $term): ?array
    {
        $request = $this->apiRequest(self::AUTOCOMPLETE, [
            'text' => $term,
            'type' => 'StreetAddress'
        ]);
        return $request['results'] ?? null;
    }
    // ...
    # call AbstractApiRequest method from apiRequest
    protected function apiRequest(string $request, ?array $context = null): array
    {
        return $this->get(self::ROUTES[$request], $context)->getData();
    }
```

apiRequest methods

```php
    public function post(string $route, ?array $options = []): static;
    public function get(string $route, ?array $context = null, ?array $options = []): static;
    public function getCurl(string $route, array $context, ?array $auth = null): static;
```

Get your API key from parameters

```php
protected function apiRequest(string $request, ?array $context = null): array
{
    return $this->get($type, $context, [
            'headers' => [
                'Content-Type' => 'application/json',
                'MY-API-KEY' => $this->param->get('cmrweb.api.key')
            ]
        ])->getData();
}
```