# bonnier-php-sdk TRAPP examples
Service for talking with the Bonnier translation service (TRAPP)

PHP SDK for communicating with the TRAPP webservice.

## Examples 
------------
All related ```TRAPP``` classes extends from the ```\Bonnier\RestItem``` class - which contains a ```RestBase``` related ```service``` property, that contains the basic functionality for communicating with webservices using the index-search authentication.

#### Service classes

| Service class      | Description   |
| ------------- | ------------- |
| ```ServiceLanguage``` | Service class for getting translations |
| ```ServiceState``` | Service class for getting available states |
| ```ServiceLanguage``` | Service class for gettings available languages |

#### Get single

This examples retrieves a single translation with the id ```FDE455B92EEBC96F72F2447D6AD17C40```

```php
$translation = new \Bonnier\Trapp\ServiceTranslation($username, $secret);
$translation = $service->getById('FDE455B92EEBC96F72F2447D6AD17C40');
```

#### Update single`

This examples updates a translation with the id ```FDE455B92EEBC96F72F2447D6AD17C40``` with the new values defined in the properties.

```php
$translation = new \Bonnier\Trapp\ServiceTranslation($username, $secret);
$translation = $service->getById('FDE455B92EEBC96F72F2447D6AD17C40');

// Create new field
$field = new \Bonnier\Trapp\Translation\TranslationField('Title', 'Dette er en titel');

// Add this field to a group
$field->setGroup('Titles');

// Create new revision
$revision = new \Bonnier\Trapp\Translation\TranslationRevision();

// Add the field that we created earlier
$revision->addField($field);

$translation->addRevision($revision);

// Add language for the item to be translated into (english, norwegian).
$translation->addLanguage('en_gb');
$translation->addLanguage('nb_no');
$translation->update();
```

#### Save single

This example will create a new instance of ```ServiceContent``` and save it to TRAPP.

```php
$translation = new \Bonnier\Trapp\ServiceTranslation($username, $secret);

// Add deadline (current time plus 10 days)
$deadline = new DateTime();
$deadline->add(new DateInterval('P10D'));

$translation->setDeadline($deadline);
$translation->setTitle('Min titel');
$translation->setLocale('da_dk');

// Create new field
$field = new \Bonnier\Trapp\Translation\TranslationField('Title', 'Dette er en titel');

// Add this field to a group
$field->setGroup('Titles');

// Create new revision
$revision = new \Bonnier\Trapp\Translation\TranslationRevision();

// Add the field that we created earlier
$revision->addField($field);

$translation->addRevision($revision);

// Add language for the item to be translated into (english, norwegian).
$translation->addLanguage('en_gb');
$translation->addLanguage('nb_no');

// Save the translation
$translation->save();
```

#### Get list, add query and apply filters
Get the results sets, query everything matching "hello", filter title by "myFilter" and content by "secondFilter". api() makes the final call to the webservice.

```php
$service = new \Bonnier\Trapp\ServiceTranslation($username, $secret);

$results = $service->getCollection() // Get the queryable TranslationCollection object
->locale('da_dk') // Filter everything with locale da_dk
->app(13) // Filter everything with app_id 13
->skip(5) // Skip 5
->limit(5) // Retrieve 5 results
->execute(); // Call the service and get the results, similar to calling api()
```

#### Add custom parameters, headers, timeout - etc.

Switch between production and staging environment by using:

```php
$service = new \Bonnier\Trapp\ServiceTranslation($username, $secret);
$service->setDevelopment(TRUE);
```

If you want to implement a parameter that is not currently available, this can be done by adding it to the request-object.

**NOTE:** Please use this with caution as this is something that should be implemented into the SDK itself if the functionality is missing.

```php
$content = new \Bonnier\Trapp\ServiceTranslation($username, $secret);
$content->getService()->getHttpRequest()->addPostData($key, $value);
$content->getService()->getHttpRequest()->addHeader($value);
$content->getService()->getHttpRequest()->setTimeout(1000) // Set timeout in ms
```

#### Debugging the output

The service will throw an ```ServiceException``` on error.

This class contains some methods which will help debug the error.

```
try {
	// Something here made the service explode
} catch(ServiceException $e) {
	$url = $e->getHttpResponse()->getUrl(); // Returns the full URL that the service has called.
	$info = $e->getHttpResponse()->getInfo(); // Returns information about the response, http/code, length etc, parameters, headers etc.
	$httpCode = $e->getHttpResponse()->getStatusCode(); // Returns the http status code
	$rawResponse = $e->getHttpResponse()->getResponse(); // Returns the raw response
	$handle = $e->getHttpResponse()->getHandle(); // Returns curl handle
}
```

You also get the HttpResponse object when retrieving something from the service.

```php
$service = new \Bonnier\Trapp\ServiceTranslation($username, $secret);
$single = $service->getById('FDE455B92EEBC96F72F2447D6AD17C40');

$httpResponse = $single->getResponse(); // Returns HttpResponse object (simular as the one above)
```

#### Extending the functionality to customize your needs

If you want to return your own set of classes or add functionality to one of the service classes, this can be done using the example below:

```php
class CustomCollection extends \Bonnier\Trapp\Translation\TranslationCollection {
	public function customFilter() {
		$this->service->getHttpRequest()->addPostData('q', $query);
		return $this;
	}
}

class ServiceCustom extends \Bonnier\Trapp\ServiceTranslation {
    /**
     * This event is fired when a collection is returned from the service
     *
     * @return CustomCollection
     */
    public function onCreateCollection() {
        return new CustomCollection($this->service);
    }

    /**
     * This event is fired when a single item is returned from the service
     *
     * @return self
     */
    public function onCreateItem() {
        return new self($this->service->getUsername(), $this->service->getSecret());
    }
    
    public function getCustomItem($id) {
        return $this->api('/custom/item/' . $id);
    }
}
```

**Usage:**

```php
$custom = new ServiceCustom($username, $secret);
$item = $custom->getCustomCollection(); // This will return a new instance of ServiceCustom class


$results = $custom->getCollection()->customFilter()->execute(); // This will return new instance of ServiceCollection class
```