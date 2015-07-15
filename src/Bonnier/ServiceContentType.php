<?php
namespace Bonnier;

use Bonnier\Service\ServiceContentResult;
use Bonnier\Service\ServiceException;
use Bonnier\Service\ServiceItem;

class ServiceContentType extends ServiceItem {

    const TYPE = 'contenttype';

    public function __construct($username, $secret) {
        parent::__construct($username, $secret, self::TYPE);
    }

    /**
     * Get queryable service result
     * @return ServiceResult
     */
    public function get() {
        return new ServiceResult($this->username, $this->secret, $this->type);
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

}