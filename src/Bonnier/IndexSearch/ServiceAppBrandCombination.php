<?php
namespace Bonnier\IndexSearch;

use Bonnier\RestItem;
use Bonnier\ServiceException;
use Bonnier\ServiceResult;

class ServiceAppBrandCombination extends RestItem {

    const TYPE = 'combination';

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
     * Check if you have access to given combination
     *
     * @param string $brandCode
     * @param string $appCode
     * @throws \Bonnier\ServiceException
     * @return boolean
     */
    public function check($brandCode, $appCode) {
        if(is_null($appCode) || is_null($brandCode)) {
            throw new ServiceException('Invalid argument for parameters');
        }

        $combinations = $this->api();

        foreach($combinations->getRows() as $combination) {
            $appCodeValue = $combination->app_code['value'];
            $brandCodeValue = $combination->brand_code['value'];

            if ($appCode === $appCodeValue && $brandCode === $brandCodeValue) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get list of combinations you have access to
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