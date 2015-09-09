<?php
namespace Bonnier\IndexSearch;

use Bonnier\RestCollection;
use Bonnier\RestItem;
use Bonnier\ServiceException;
use Bonnier\ServiceResult;

class ServiceApplication extends RestItem {

    const TYPE = 'application';

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
     * @return self
     */
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
     * @throws \Bonnier\ServiceException
     * @return self
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
}