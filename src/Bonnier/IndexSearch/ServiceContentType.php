<?php
namespace Bonnier\IndexSearch;

use Bonnier\RestCollection;
use Bonnier\RestItem;

class ServiceContentType extends RestItem implements IServiceEventListener {

    const TYPE = 'contenttype';

    /**
     * This is required in order to get autocompletion to work for this element.
     * @var ServiceBase
     */
    protected $service;

    public function __construct($username, $secret) {
        $this->service = new ServiceBase($username, $secret, self::TYPE);
    }

    public function skip($skip) {
        $this->service->getRequest()->addPostData('skip', $skip);
        return $this;
    }

    public function limit($limit) {
        $this->service->getRequest()->addPostData('limit', $limit);
        return $this;
    }

    /**
     * Get queryable service result.
     *
     * @return \Bonnier\RESTCollection
     */
    public function getCollection() {
        return $this->onCreateCollection();
    }

    public function onCreateCollection() {
        return new RestCollection($this->service);
    }

    public function onCreateItem() {
        return new self($this->service->getUsername(), $this->service->getSecret());
    }

    /**
     * Get single item by id.
     *
     * @param $id
     * @throws \Bonnier\ServiceException
     * @return \Bonnier\RESTItem
     */
    public function getById($id) {
        return $this->api($id);
    }

    public function setDevelopment($bool) {
        $this->service->setDevelopment($bool);
        return $this;
    }

}