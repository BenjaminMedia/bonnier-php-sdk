<?php
namespace Bonnier\IndexSearch;

use Pecee\Http\Rest\RestItem;
use Bonnier\ServiceException;

class ServiceAppBrandCode extends RestItem {

    public function __construct($username, $secret) {
        parent::__construct(new ServiceBase($username, $secret));
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
        if(is_null($brandCode) || is_null($appCode)) {
            throw new ServiceException('Invalid argument for parameters');
        }

        $combinations = $this->api('combinations');

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
     * Returns a list of combinations you have access to
     *
     * @throws \Bonnier\ServiceException
     * @return array
     */
    public function getList() {
        return $this->api('combinations')->getRows();
    }

    /**
     * Returns a list of all Brand codes
     *
     * @throws \Bonnier\ServiceException
     * @return array
     */
    public function getBrandCodes() {
        return $this->api('brandcodes')->getRows();
    }

    /**
     * Returns a list of all App codes
     *
     * @throws \Bonnier\ServiceException
     * @return array
     */
    public function getAppCodes() {
        return $this->api('appcodes')->getRows();
    }

    /**
     * @return ServiceBase
     */
    public function getService() {
        return parent::getService();
    }

}