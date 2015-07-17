<?php
namespace Bonnier\IndexDB;

use Bonnier\IndexDB\Content\ContentResult;

class ServiceContent extends IndexDBBase {

    const TYPE = 'content';

    public function __construct($username, $secret) {
        parent::__construct($username, $secret, self::TYPE);
    }

    /**
     * Get queryable service result
     * @return ContentResult
     */
    public function get() {
        return new ContentResult($this->username, $this->secret, $this->type);
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
     * Save item
     * @return ServiceContent
     */
    public function save() {
        $this->row = $this->api(NULL, self::METHOD_POST, (array)$this->row);
        return $this;
    }

    /**
     * Get queryable service result
     * @return ServiceContent
     */
    public function update() {
        $this->row = $this->api($this->row->id, self::METHOD_PUT, (array)$this->row);
        return $this;
    }

    /**
     * @return ServiceContent
     * @throws \Bonnier\ServiceException
     */
    public function delete($id) {
        return $this->api($id, self::METHOD_DELETE);
    }

}