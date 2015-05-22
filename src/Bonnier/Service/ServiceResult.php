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

    public function setSort($sort) {
        $this->_data['sort'] = $sort;
    }

    public function setOrder($order) {
        $this->_data['order'] = $order;
    }

    public function addFilter($name, $value) {
        $this->_data[$name] = $value;
        return $this;
    }

    public function setDsl(array $dsl) {
        $this->_data['dsl'] = json_encode($dsl);
        return $this;
    }

    public function getTook(){
        return $this->took;
    }

    public function setTook($took){
        $this->took = $took;
    }

    public function getTimedOut(){
        return $this->timedOut;
    }

    public function setTimedOut($timedOut){
        $this->timedOut = $timedOut;
    }

    public function getMaxScore(){
        return $this->maxScore;
    }

    public function setMaxScore($maxScore){
        $this->maxScore = $maxScore;
    }

    public function getTotal(){
        return $this->total;
    }

    public function setTotal($total){
        $this->total = $total;
    }
}