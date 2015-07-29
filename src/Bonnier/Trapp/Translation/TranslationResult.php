<?php
namespace Bonnier\IndexDB\Content;
use Bonnier\Trapp\TrappBase;

class TranslationResult extends TrappBase {

    public $total;
    public $skip;
    public $limit;
    public $searchTime;
    protected $_data = array();
    public $rows;

    protected $response;

    // TODO: Do advanced logic here

    public function api($url = NULL, $method = self::METHOD_GET, array $data = NULL) {
        $data = (is_array($data)) ? array_merge($this->_data, $data) : $this->_data;
        return parent::api($url, $method, $data);
    }

    public function setResponse($response) {
        $this->searchTime = $response['searchTime'];
        $this->skip = $response['skip'];
        $this->limit = $response['limit'];
    }

    public function getSkip() {
        return $this->skip;
    }

    public function getLimit() {
        return $this->limit;
    }
}