<?php
namespace Bonnier\IndexSearch;

use Bonnier\RestCollection;
use Bonnier\RestItem;
use Bonnier\ServiceResult;

class ServiceApplication extends RestItem implements IServiceEventListener {

    const TYPE = 'application';

    /**
     * This is required in order to get autocompletion to work for this element.
     * @var ServiceBase
     */
    protected $service;

    public function __construct($username, $secret) {
        parent::__construct($this->service);

        $this->service->setServiceEventListener($this);
    }

    public function onCreateCollection() {
        return new RestCollection($this->service);
    }

    public function onCreateItem() {
        return new self($this->service->getUsername(), $this->service->getSecret());
    }

    /**
     * Get queryable collection
     *
     * @throws \Bonnier\ServiceException
     * @return \Bonnier\RestCollection
     */
    public function getCollection() {
        return $this->onCreateCollection();
    }

    /**
     * Get item by id
     *
     * @param int $id
     * @return \Bonnier\RestItem
     * @throws \Bonnier\ServiceException
     */
    public function getById($id) {
        return $this->api($id);
    }

    public function setDevelopment($bool) {
        $this->service->setDevelopment($bool);
        return $this;
    }
}