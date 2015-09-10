<?php

namespace Bonnier\Trapp\Translation;

class TranslationRevision {

	protected $data;

	public function __construct() {
		$this->data = (object)array();
		$this->data->fields = array();
	}

	public function getFields() {
		$out = array();
		foreach($this->data->fields as $field) {
			$out[] = TranslationField::fromArray($field);
		}

		return $out;
	}

	/**
	 * Add field to be translated
	 *
	 * @param TranslationField $field
	 * @return self
	 */
	public function addField(TranslationField $field) {
		$this->data->fields[] = $field->toArray();
		return $this;
	}

	/**
	 * Get field
	 *
	 * @param int $index
	 * @return TranslationField|null
	 */
	public function getField($index) {
		if(isset($this->data->fields[$index])) {
			return TranslationField::fromArray($this->data->fields[$index]);
		}
		return null;
	}

	public function getId() {
		return $this->data->_id;
	}

	public function setState($state) {
		$this->data->state = $state;
		return $this;
	}

	public function getState() {
		return $this->data->state;
	}

	public function __set($name, $value = null) {
		$this->data->$name = $value;
	}

	public function __get($name) {
		return (isset($this->data->$name)) ? $this->data->$name : null;
	}

	public function toArray() {
		return (array)$this->data;
	}

	/**
	 * Create new revision from array
	 *
	 * @param \stdClass $array
	 * @return TranslationRevision
	 */
	public static function fromArray($array) {
		$revision = new self();
		$revision->setData($array);
		return $revision;
	}

	/**
	 * @return array
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * @param \stdClass $data
	 */
	public function setData($data) {
		$this->data = $data;
	}
}