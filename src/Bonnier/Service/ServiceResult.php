<?php
namespace Bonnier\Service;
class ServiceResult extends \Bonnier\Service {

    protected $_data;

    public $took;
    public $timedOut;
    public $maxScore;
    public $total;
    public $hits;

    public function getHits() {
        return $this->hits;
    }

    public function query($query) {
        $this->_data['q'] = $query;
        return $this;
    }

    public function addFilter($name, $value) {
        $this->_data[$name] = $value;
        return $this;
    }

    public function setDsl(array $dsl) {
        $this->_data['dsl'] = json_encode($dsl);
        return $this;
    }
}