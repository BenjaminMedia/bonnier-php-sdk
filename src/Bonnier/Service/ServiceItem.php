<?php
namespace Bonnier\Service;

class ServiceItem extends ServiceBase {

    public $row;

    public function __construct($secret, $type) {
        parent::__construct($secret, $type);
        $this->row = new \stdClass();
    }

    public function setRow(\stdClass $row) {
        $this->row = $row;
    }

    public function save() {
        $this->row = $this->api(NULL, self::METHOD_POST, (array)$this->row);
        return $this;
    }

    public function __set($name, $value = NULL) {
        $this->row->$name = $value;
    }

    public function __get($name) {
        return (isset($this->row->$name)) ? $this->row->$name : NULL;
    }

}