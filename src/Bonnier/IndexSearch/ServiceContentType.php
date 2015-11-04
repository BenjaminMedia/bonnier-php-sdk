<?php
namespace Bonnier\IndexSearch;

use Pecee\Http\Rest\RestItem;
use Bonnier\ServiceException;

class ServiceContentType extends RestItem {

    const TYPE = 'contenttype';

    public function __construct($username, $secret) {
        parent::__construct(new ServiceBase($username, $secret, self::TYPE));
        $this->service->setServiceEventListener($this);
    }

    public function skip($skip) {
        $this->service->getHttpRequest()->addPostData('skip', $skip);
        return $this;
    }

    public function limit($limit) {
        $this->service->getHttpRequest()->addPostData('limit', $limit);
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

    /**
     * @return self
     */
    public function onCreateItem(){
        $self = new self($this->service->getUsername(), $this->service->getSecret());
        $self->setService($this->service);
        return $self;
    }

    /**
     * Get single item by id.
     *
     * @param $id
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

    /**
     * @return ServiceBase
     */
    public function getService() {
        return parent::getService();
    }

}