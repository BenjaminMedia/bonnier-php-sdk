<?php
namespace Bonnier\IndexSearch;

use Bonnier\IndexSearch\Content\ContentCollection;

class ServiceContent extends ServiceBase {

    const TYPE = 'content';

    public function __construct($username, $secret) {
        parent::__construct($username, $secret, self::TYPE);
    }

    /**
     * Returns result-collection specific for this service.
     *
     * @return ContentCollection
     */
    protected function onCreateCollection() {
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
     *
     * @param string $id
     * @throws \Bonnier\ServiceException
     * @return \Bonnier\IndexSearch\REST\RESTItem
     */
    public function getById($id) {
        return $this->api($id);
    }

    /**
     * Delete item by id
     *
     * @param string $id
     * @throws \Bonnier\ServiceException
     * @return \Bonnier\IndexSearch\REST\RESTItem
     */
    public function delete($id) {
        return $this->api($id, self::METHOD_DELETE);
    }

}