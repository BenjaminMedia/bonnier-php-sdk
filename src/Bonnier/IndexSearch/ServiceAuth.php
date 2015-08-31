<?php
namespace Bonnier\IndexSearch;

use Bonnier\RestItem;
use Bonnier\ServiceResult;

class ServiceAuth extends RestItem implements IServiceEventListener {

    const TYPE = 'auth';

    /**
     * This is required in order to get autocompletion to work for this element.
     * @var ServiceBase
     */
    protected $service;

    public function __construct($username, $secret) {
        parent::__construct(new ServiceBase($username, $secret, self::TYPE));
    }

    public function onCreateCollection() {
        return new RestCollection($this->service);
    }

    public function onCreateItem() {
        return new self($this->service->getUsername(), $this->service->getSecret());
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