<?php
namespace Bonnier\IndexSearch;

use Bonnier\IndexSearch\Content\ContentCollection;
use Bonnier\RestItem;

class ServiceContent extends RestItem implements IServiceEventListener {

    const TYPE = 'content';

    public function __construct($username, $secret) {
        $this->service = new ServiceBase($username, $secret, self::TYPE);
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

    public function onCreateItem() {
        return new RestItem($this->service);
    }

    /**
     * Get queryable service result
     * @return ContentCollection
     */
    public function getCollection() {
        return new ContentCollection($this->service);
    }

    /**
     * Get single item by id
     *
     * @param string $id
     * @throws \Bonnier\ServiceException
     * @return \Bonnier\RestItem
     */
    public function getById($id) {
        return $this->api($id);
    }

    /**
     * Delete item by id
     *
     * @param string $id
     * @throws \Bonnier\ServiceException
     * @return \Bonnier\RestItem
     */
    public function delete($id) {
        return $this->api($id, self::METHOD_DELETE);
    }

    public function setDevelopment($bool) {
        $this->service->setDevelopment($bool);
        return $this;
    }

}