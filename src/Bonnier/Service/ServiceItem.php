<?php
namespace Bonnier\Service;
class ServiceItem extends \Bonnier\Service {

    public $index;
    public $type;
    public $id;
    public $score;
    public $source;
    public $version;
    public $created;

    public function __construct($secret, \stdClass $source) {
        parent::__construct($secret);
        $this->source = (object)$source;
    }

    public function setSource(array $source) {
        $this->source = (object)$source;
    }

    public function save() {
        $response = $this->api($this->id, self::METHOD_POST, (array)$this->source);
        if(isset($response['created'])) {
            $this->created = $response['created'];
            $this->version = $response['version'];
        }
        return $this;
    }

    public function delete() {
        return $this->api($this->id, self::METHOD_DELETE);
    }

    public function update() {
        $response = $this->api($this->id, self::METHOD_PUT, (array)$this->source);
        if(isset($response['_version'])) {
            $this->version = $response['_version'];
        }
        return $this;
    }
}