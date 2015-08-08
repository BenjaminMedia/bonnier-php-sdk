<?php

namespace Bonnier\Trapp\Translation;

class TranslationRevision {

	protected $data;

	public function __set($name, $value = NULL) {
		$this->data->$name = $value;
	}

	public function __get($name) {
		return (isset($this->data->$name)) ? $this->data->$name : NULL;
	}

	public function setData($data) {
		$this->data = $data;
	}

}