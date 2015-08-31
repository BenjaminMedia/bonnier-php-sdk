<?php
namespace Bonnier\IndexDB;

use Bonnier\IndexDB\Content\ContentCollection;

class ServiceContent extends IndexSearchBase {

    const TYPE = 'content';

    public function __construct($username, $secret) {
        parent::__construct($username, $secret, self::TYPE);
    }

    /**
     * Returns result-collection specific for this service.
     *
     * @return ContentCollection
     */
    protected function onCreateResult() {
        $collection = new ContentCollection($this->username, $this->secret, $this->type);
        $collection->setDevelopment($this->development);
        return $collection;
    }

    /**
     * Get queryable service result
     * @return ContentCollection
     */
    public function getCollection() {
        return $this->onCreateResult();
    }

    /**
     * Get single item by id
     * @param $id
     * @throws \Bonnier\ServiceException
     * @return IndexServiceItem
     */
    public function getById($id) {
        return $this->api($id);
    }

    /**
     * @throws \Bonnier\ServiceException
     * @return IndexServiceItem
     */
    public function delete($id) {
        return $this->api($id, self::METHOD_DELETE);
    }

}