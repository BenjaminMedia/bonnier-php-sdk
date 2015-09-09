<?php
namespace Bonnier\IndexSearch;

use Bonnier\IndexSearch\Content\ContentCollection;
use Bonnier\IRestEventListener;
use Bonnier\RestItem;
use Bonnier\ServiceException;

class ServiceContent extends RestItem {

    const TYPE = 'content';

    /**
     * This is required in order to get autocompletion to work for this element.
     * @var ServiceBase
     */
    protected $service;

    public function __construct($username, $secret) {
        parent::__construct(new ServiceBase($username, $secret, self::TYPE));
        $this->service->setServiceEventListener($this);
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
     * @return self
     */
    public function onCreateItem() {
        return new self($this->service->getUsername(), $this->service->getSecret());
    }

    /**
     * Get queryable service result
     * @return ContentCollection
     */
    public function getCollection() {
        return $this->onCreateCollection();
    }

    /**
     * Get single item by id.
     *
     * @param $id
     * @throws \Bonnier\ServiceException
     * @return false|self
     */
    public function getById($id) {
        if(is_null($id)) {
            throw new ServiceException('Invalid argument for parameter $id');
        }
        return $this->api($id);
    }

    public function setDevelopment($bool) {
        $this->service->setDevelopment($bool);
        return $this;
    }

    public function getService() {
        return $this->service;
    }
}