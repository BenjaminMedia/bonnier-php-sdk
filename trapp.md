# bonnier-php-sdk TRAPP examples
Service for talking with the Bonnier translation service (TRAPP)

PHP SDK for communicating with the TRAPP webservice.

## Examples 
------------
All related ```TRAPP``` classes extends from the ```\Bonnier\RestItem``` class - which contains a ```RestBase``` related ```service``` property, that contains the basic functionality for communicating with webservices using the index-search authentication.

#### Service classes

| Service class      | Description   |
| ------------- | ------------- |
| ```ServiceTranslation``` | Service class for getting translations |
| ```ServiceState``` | Service class for getting available states |
| ```ServiceLanguage``` | Service class for gettings available languages |
| ```ServiceRevision``` | Service class for returning available revisions (Not intended for external use) |

#### Get single

This examples retrieves a single translation with the id ```55a8cb09214f48032700421f```

```php
$translation = new \Bonnier\Trapp\ServiceTranslation($username, $secret);
$translation = $service->getById('55a8cb09214f48032700421f');
```
#### Create Single
```php
$service = new \Bonnier\Trapp\ServiceTranslation($this->apiUser, $this->apiKey);
        $service->getService()->setServiceUrl($this->serviceUrl);
        $service->setDevelopment(true);

        // Set attributes
        $service->setAppCode('fordelszonen');
        $service->setBrandCode('kom');
        $service->setLocale('da_dk');
        $service->setTitle('This is a title');
        $service->addTranslatation('en_gb');
        $service->setDeadline(new DateTime('tomorrow'));
        $service->setComment('This is a comment');
        $service->setState('state-waiting'); // Note: Not required - this defaults to 'state-waiting' if not provided.
        
        // Create a field
        // Build field array
        $newFieldArray = array(
            'label' => 'This is the name of my paragraph',
            'value' => 'This is the content of my paragraph',
            'display_format' => 'text', // Can be 'text' or 'image'
            'group' => 'Group1', // Used for grouping multiple fields - not required
        );
        // Create field object
        $fieldObject =  \Bonnier\Trapp\Translation\TranslationField::fromArray($newFieldArray);
        // Add field
        $service->addField($fieldObject);
        
        $savedTranlation = $service->save(); // Both the return and $service object is now a new translation
```

#### Update single

This examples updates a translation with the id ```55a8cb09214f48032700421f``` with the new values defined in the properties.

```php
$service = new \Bonnier\Trapp\ServiceTranslation($this->apiUser, $this->apiKey);
        $service->getService()->setServiceUrl($this->serviceUrl);
        $service->setDevelopment(true);
        
        $service->getById('55a8cb09214f48032700421f');

        // Set attributes
        $service->setTitle('This is a updated title');
        $service->addTranslatation('sv_se');
        $service->setDeadline(new DateTime('today'));
        $service->setComment('This is a updated comment');
        
        // Update an existing field on an existing translation
        $fields = $service->getFields();
        $fields[0]->setValue('This is an updated value');
        // Set the new array of updated fields.
        $service->setFields($fields); // Note setFields() overrides existing fields.
        
        // Create a new field on the existing translation
        // Build field array
        $newFieldArray = array(
            'label' => 'This is the name of a new paragraph',
            'value' => 'This is the content of a new paragraph',
            'display_format' => 'text', // Can be 'text' or 'image'
            'group' => 'Group1', // Used for grouping multiple fields - not required
        );
        // Create field object
        $fieldObject =  \Bonnier\Trapp\Translation\TranslationField::fromArray($newFieldArray);
        // Add field
        $service->addField($fieldObject);
        
        $savedTranlation = $service->update(); // Both the return and $service object is now the new version of the translation
```

#### Delete item

This is a basic example of deleting an item.

```php
$service = new \Bonnier\Trapp\ServiceTranslation($username, $secret);

$translation = $service->getById('2131231231');
$translation->delete();

if($translation->deleted) {
    // Item successfully deleted
}
```

#### Get list, add query and apply filters
Get the results sets, query everything matching locale ```da_dk``` with application id ```13``` - then skip 5 results and take 5.
```execute()``` performs the api-call and delivers the results.

```php
$service = new \Bonnier\Trapp\ServiceTranslation($username, $secret);

$results = $service->getCollection() // Get the queryable TranslationCollection object
->locale('da_dk') // Filter everything with locale da_dk
->app(13) // Filter everything with app_id 13
->skip(5) // Skip 5
->limit(5) // Retrieve 5 results
->execute(); // Call the service and get the results, similar to calling api()
```

#### Creating object from callback

If you want a nice ```ServiceTranslation``` object upon receiving the callback from Trapp, you can use the ```fromCallback``` method on the ```ServiceTranslation``` class.

Example:

```php
    // Parse Trapp response
    $callbackResponse = $_POST;

    $object = \Bonnier\Trapp\ServiceTranslation::fromCallback($username, $secret, $callbackResponse);
```

#### Add custom parameters, headers, timeout - etc.

Switch between production and staging environment by using:

```php
$service = new \Bonnier\Trapp\ServiceTranslation($username, $secret);
$service->setDevelopment(true);
```

If you want to implement a parameter that is not currently available, this can be done by adding it to the request-object.

**NOTE:** Please use this with caution as this is something that should be implemented into the SDK itself if the functionality is missing.

```php
$service = new \Bonnier\Trapp\ServiceTranslation($username, $secret);
$service->getService()->getHttpRequest()->addPostData($key, $value);
$service->getService()->getHttpRequest()->addHeader($value);
$service->getService()->getHttpRequest()->setTimeout(1000) // Set timeout in ms
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
$item = $custom->getCustomItem(); // This will return a new instance of ServiceCustom class


$results = $custom->getCollection()->customFilter()->execute(); // This will return new instance of ServiceCollection class
```