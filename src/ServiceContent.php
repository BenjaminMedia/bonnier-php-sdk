<?php
namespace BonnierPHPSDK;

use BonnierPHPSDK\Service\ServiceItem;

class ServiceContent extends ServiceItem {

    const TYPE = 'content';

    public function __construct($secret) {
        parent::__construct($secret, self::TYPE);
    }

    /**
     * Get queryable service result
     * @return ServiceResult
     */
    public function get() {
        return new ServiceResult($this->secret, $this->type);
    }

    /**
     * Get single item by id
     * @param $id
     * @return ServiceItem
     * @throws ServiceException
     */
    public function getById($id) {
        return $this->api($id);
    }

    /**
     * Save item
     * @param \stdClass $row
     * @return $this
     */
    public function save(\stdClass $row) {
        $item = new ServiceItem($this->secret, $this->type);
        $item->row = $row;
        return $item->save();
    }

    public function update() {
        $this->row = $this->api($this->row->id, self::METHOD_PUT, (array)$this->row);
        return $this;
    }

    public function delete($id) {
        return $this->api($id, self::METHOD_DELETE);
    }

}