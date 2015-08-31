<?php
namespace Bonnier\IndexSearch;

use Bonnier\RestItem;
use Bonnier\ServiceResult;

class ServiceAuth extends RestItem {

    const TYPE = 'auth';

    public function __construct($username, $secret) {
        parent::__construct(new ServiceBase($username, $secret, self::TYPE));
    }

    /**
     * Check if you have access to given role
     *
     * @param string $role
     * @throws \Bonnier\ServiceException
     * @return \Bonnier\RestItem
     */
    public function check($role) {
        return $this->api($role);
    }

    public function setDevelopment($bool) {
        $this->service->setDevelopment($bool);
        return $this;
    }

}