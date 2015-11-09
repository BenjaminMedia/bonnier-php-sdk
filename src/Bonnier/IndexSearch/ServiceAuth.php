<?php
namespace Bonnier\IndexSearch;

use Pecee\Http\Rest\RestItem;
use Bonnier\ServiceException;

class ServiceAuth extends RestItem {

    const TYPE = 'auth';

    public function __construct($username, $secret) {
        parent::__construct(new ServiceBase($username, $secret, self::TYPE));
        $this->service->setServiceEventListener($this);
    }

    /**
     * @return self
     */
    public function onCreateItem(){
        $self = new self($this->service->getUsername(), $this->service->getSecret());
        $self->setService($this->service);
        return $self;
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

    /**
     * @return ServiceBase
     */
    public function getService() {
        return parent::getService();
    }

}