<?php
namespace Bonnier\IndexSearch\REST;

use Bonnier\IndexSearch\ServiceBase;

class RESTItem extends ServiceBase {

    protected $row;

    public function __construct($username, $secret, $type = '') {
        parent::__construct($username, $secret, $type);
        $this->row = new \stdClass();
    }

    public function setRow(\stdClass $row) {
        $this->row = $row;
    }

    public function update() {
        $this->row = $this->api($this->id, self::METHOD_PUT, (array)$this->row)->getRow();
        return $this;
    }

    public function save() {
        $this->row = $this->api(NULL, self::METHOD_POST, (array)$this->row)->getRow();
        return $this;
    }

    public function __set($name, $value = NULL) {
        $this->row->$name = $value;
    }

    public function __get($name) {
        return (isset($this->row->$name)) ? $this->row->$name : NULL;
    }

    public function getRow() {
        return $this->row;
    }

}