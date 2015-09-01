<?php
namespace Bonnier\IndexSearch;

use Bonnier\RestItem;
use Bonnier\ServiceException;
use Bonnier\ServiceResult;

class ServiceAuth extends RestItem {

    const TYPE = 'auth';

    /**
     * This is required in order to get autocompletion to work for this element.
     * @var ServiceBase
     */
    protected $service;

    public function __construct($username, $secret) {
        parent::__construct(new ServiceBase($username, $secret, self::TYPE));
    }

    /**
     * Check if you have access to given role
     *
     * @param string $role
     * @throws \Bonnier\ServiceException
     * @return self
     */
    public function check($role) {
        if(is_null($role)) {
            throw new ServiceException('Invalid argument for parameter $id');
        }
        return $this->api($role);
    }

    public function setDevelopment($bool) {
        $this->service->setDevelopment($bool);
        return $this;
    }

}