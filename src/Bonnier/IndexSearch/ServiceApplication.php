<?php
namespace Bonnier\IndexSearch;

use Bonnier\RestCollection;
use Bonnier\RestItem;
use Bonnier\ServiceResult;

class ServiceApplication extends RestItem {

    const TYPE = 'application';

    public function __construct($username, $secret) {
        parent::__construct(new ServiceBase($username, $secret, self::TYPE));
    }

    /**
     * Get queryable collection
     *
     * @return \Bonnier\RestCollection
     * @throws \Bonnier\ServiceException
     */
    public function getCollection() {
        return new RestCollection($this->service);
    }

    /**
     * Get item by id
     *
     * @param int $id
     * @return \Bonnier\RestItem
     * @throws \Bonnier\ServiceException
     */
    public function getById($id) {
        return $this->api($id);
    }

    public function setDevelopment($bool) {
        $this->service->setDevelopment($bool);
        return $this;
    }

}