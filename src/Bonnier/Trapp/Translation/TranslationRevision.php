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
		if(isset($this->data->fields) && count($this->data->fields)) {
			foreach($this->data->fields as $field) {
				$out[] = TranslationField::fromArray($field);
			}
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

	public function getState() {
		return $this->data->state;
	}

	public function getDeadline() {
		return new \DateTime($this->row->deadline);
	}

	public function setDeadline(\DateTime $datetime) {
		$this->row->deadline = $datetime->format(DATE_W3C);
		return $this;
	}

	public function getTitle() {
		return $this->row->title;
	}

	public function setTitle($title) {
		$this->row->title = $title;
		return $this;
	}

	public function setComment($comment) {
		$this->row->comment = $comment;
		return $this;
	}

	public function setState($state){
		$this->row->state = $state;
		return $this;
	}

	/**
	 * Add language for the item to be translated into
	 *
	 * @param string $locale
	 * @return self
	 */
	public function addLanguage($locale) {
		$this->row->translate_into[] = $locale;
		return $this;
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