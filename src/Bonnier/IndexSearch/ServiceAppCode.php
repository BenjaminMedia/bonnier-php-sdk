<?php
namespace Bonnier\IndexSearch;

use Bonnier\RestItem;
use Bonnier\ServiceException;
use Bonnier\ServiceResult;

class ServiceAppCode extends RestItem {

    const TYPE = 'appcode';

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

    public function setDevelopment($bool) {
        $this->service->setDevelopment($bool);
        return $this;
    }

    /**
     * Get list of Brand codes
     *
     * @throws \Bonnier\ServiceException
     * @return array
     */
    public function getList() {
        return $this->api()->getRows();
    }

    /**
     * @return ServiceBase
     */
    public function getService() {
        return parent::getService();
    }

}