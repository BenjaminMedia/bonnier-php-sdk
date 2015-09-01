<?php
namespace Bonnier\IndexSearch;

use Bonnier\IndexSearch\Content\ContentCollection;
use Bonnier\RestItem;

class ServiceContent extends RestItem {

    const TYPE = 'content';

    /**
     * This is required in order to get autocompletion to work for this element.
     * @var ServiceBase
     */
    protected $service;

    public function __construct($username, $secret) {
        parent::__construct(new ServiceBase($username, $secret, self::TYPE));
    }

    /**
     * Returns result-collection specific for this service.
     *
     * @return ContentCollection
     */
    public function onCreateCollection() {
        return new ContentCollection($this->service);
    }

    /**
     * Get queryable service result
     * @return ContentCollection
     */
    public function getCollection() {
        return $this->onCreateCollection();
    }

    public function setDevelopment($bool) {
        $this->service->setDevelopment($bool);
        return $this;
    }

    public function getService() {
        return $this->service;
    }
}