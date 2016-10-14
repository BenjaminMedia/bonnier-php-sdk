# bonnier-php-sdk IndexSearch examples
PHP SDK for communicating with the Bonnier search db webservice.

## Examples
------------
These examples are pretty rough, but should give you a basic understanding on how to use the ìndex-search service.

### Service classes

| Service class             	| Description                                                       |
| -------------           		| -------------                                                     |
| ```ServiceAppBrandCombinations```	| Service for handling app and brand code checking and listing  |


### ServiceAppBrandCombinations

#### Get Brand Codes

This examples retrieves a list of all brand codes, typically to be used for filtering

```php
$service = new \Bonnier\IndexSearch\V1\ServiceAppBrandCode($username, $secret);
$brandCodeList = $serviceBrandCode->getBrandCodes();
```

#### Get App Codes

This examples retrieves a list of all app codes, typically to be used for filtering

```php
$service = new \Bonnier\IndexSearch\V1\ServiceAppBrandCode($username, $secret);
$appCodeList = $serviceAppCode->getAppCodes();
```

#### Get list of available app code & brand code combinations

This example retrieves all available app code and brand code combinations that you have access to.

```php
$service = new Bonnier\IndexSearch\V1\ServiceAppBrandCode($username, $secret);
$combinationList = $service->getList();
```

#### Check access to a given app code & brand code combination

This example returns true or false based on access to a given app code & brand code combination

```php
$service = new \Bonnier\IndexSearch\V1\ServiceAppBrandCode($username, $secret);
$isValid =  $service->checkCombination("app_code", "brand_code");
```
