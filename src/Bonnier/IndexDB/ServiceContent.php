<?php
namespace Bonnier\IndexDB;

use Bonnier\IndexDB\Content\ContentCollection;

class ServiceContent extends IndexDBBase {

    const TYPE = 'content';

    public function __construct($username, $secret) {
        parent::__construct($username, $secret, self::TYPE);
    }

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
        $collection = new ContentCollection($this->username, $this->secret, $this->type);
        $collection->setDevelopment($this->development);
        return $collection;
    }

    /**
     * Get single item by id
     * @param $id
     * @return ServiceIndexDB
     * @throws ServiceContent
     */
    public function getById($id) {
        return $this->api($id);
    }

    /**
     * @return ServiceContent
     * @throws \Bonnier\ServiceException
     */
    public function delete($id) {
        return $this->api($id, self::METHOD_DELETE);
    }

}