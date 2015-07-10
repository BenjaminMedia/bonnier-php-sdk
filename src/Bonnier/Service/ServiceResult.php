<?php
namespace Bonnier\Service;
class ServiceResult extends ServiceBase {

    protected $_data = array();
    public $rows;

    protected $response;

    public function api($url = NULL, $method = self::METHOD_GET, array $data = NULL) {
        $data = (is_array($data)) ? array_merge($this->_data, $data) : $this->_data;
        return parent::api($url, $method, $data);
    }

    public function setResponse($response) {
        $this->response = $response;
    }

    public function getResponse() {
        return $this->response;
    }

    public function getRows() {
        return $this->rows;
    }

    public function setRows($rows) {
        $this->rows = $rows;
    }
}