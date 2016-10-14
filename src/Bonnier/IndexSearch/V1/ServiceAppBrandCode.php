<?php

namespace Bonnier\IndexSearch\V1;


class ServiceAppBrandCode extends ServiceBase
{
    const COMBINATIONS = 'combinations';
    const APP_CODES = 'appcodes';
    const BRAND_CODES = 'brandcodes';

    public function __construct($username, $secret)
    {
        parent::__construct($username, $secret, '');
    }

    public function getAppCodes()
    {
        $response = $this->api(self::APP_CODES);

        return $response['rows'];
    }

    public function getBrandCodes()
    {
        $response = $this->api(self::BRAND_CODES);

        return $response['rows'];
    }

    public function checkCombination($appCode, $brandCode)
    {
        foreach ($this->getList() as $validCombination) {
            if ($appCode === $validCombination['app_code']['value'] && $brandCode === $validCombination['brand_code']['value']) {
                return true;
            }
        }
        return false;
    }

    public function getList()
    {
        $response = $this->api(self::COMBINATIONS);

        return $response['rows'];
    }

}