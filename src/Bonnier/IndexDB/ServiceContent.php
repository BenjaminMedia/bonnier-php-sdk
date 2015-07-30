<?php
namespace Bonnier\IndexDB;

use Bonnier\IndexDB\Content\ContentCollection;

class ServiceContent extends IndexDBBase {

    const TYPE = 'content';

    public function __construct($username, $secret) {
        parent::__construct($username, $secret, self::TYPE);
    }

    protected function onCreateResult() {
        return new ContentCollection($this->username, $this->secret, $this->type);
    }

    /**
     * Get queryable service result
     * @return ContentCollection
     */
    public function get() {
        return new ContentCollection($this->username, $this->secret, $this->type);
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