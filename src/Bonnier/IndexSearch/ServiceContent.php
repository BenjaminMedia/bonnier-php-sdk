<?php
namespace Bonnier\IndexSearch;

use Bonnier\IndexSearch\Content\ContentCollection;
use Bonnier\IRestEventListener;
use Bonnier\RestBase;
use Bonnier\RestItem;
use Bonnier\ServiceException;

class ServiceContent extends RestItem {

    const TYPE = 'content';

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
    public function onCreateItem(){
        $self = new self($this->service->getUsername(), $this->service->getSecret());
        $self->setService($this->service);
        return $self;
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
     * @return self|false
     */
    public function getById($id) {
        if(is_null($id)) {
            throw new ServiceException('Invalid argument for parameter $id');
        }
        return $this->api($id);
    }

    protected function getPostData() {
        $row = $this->row;
        if(is_array($this->row->meta)) {
            $row->meta = json_encode($row->meta);
        }
        return $row;
    }

    /**
     * Update item
     *
     * @throws \Bonnier\ServiceException
     * @return static
     */
    public function update() {
        if($this->id === null) {
            throw new ServiceException('Failed to update. Missing required argument "id".');
        }

        $this->row = $this->api($this->id, RestBase::METHOD_PUT, (array)$this->getPostData())->getRow();
        return $this;
    }

    /**
     * Save item
     *
     * @throws \Bonnier\ServiceException
     * @return static
     */
    public function save() {
        $this->row = $this->api(null, RestBase::METHOD_POST, (array)$this->getPostData())->getRow();
        return $this;
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