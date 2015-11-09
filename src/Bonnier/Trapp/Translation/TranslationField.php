<?php
namespace Bonnier\Trapp\Translation;

use Bonnier\ServiceException;

class TranslationField {

	const DISPLAY_FORMAT_TEXT = 'text';
	const DISPLAY_FORMAT_IMAGE = 'image';

	static $displayFormats = array(self::DISPLAY_FORMAT_TEXT, self::DISPLAY_FORMAT_IMAGE);

	public $id;
	public $label;
	public $value;
	public $displayFormat;
	public $group;
	public $sharedKey;

	public function __construct($label, $value) {
		$this->label = $label;
		$this->value = $value;
		$this->displayFormat = self::DISPLAY_FORMAT_TEXT;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @param string $label
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

	/**
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param string $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function getDisplayFormat() {
		return $this->displayFormat;
	}

	/**
	 * @param string $displayFormat
	 * @throws \Bonnier\ServiceException
	 */
	public function setDisplayFormat($displayFormat) {
		if(!in_array($displayFormat, self::$displayFormats)) {
			throw new ServiceException('Invalid display format - must be one of the following: ' . join(', ', self::$displayFormats));
		}
		$this->displayFormat = $displayFormat;
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * @param string $group
	 */
	public function setGroup($group) {
		$this->group = $group;
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getSharedKey() {
		return $this->sharedKey;
	}

	/**
	 * @param string $sharedKey
	 */
	public function setSharedKey( $sharedKey ) {
		$this->sharedKey = $sharedKey;
	}

	public function toArray() {
		return array(
			'label' => $this->label,
			'value' => $this->value,
			'display_format' => $this->displayFormat,
			'group' => $this->group,
			'shared_key' => $this->sharedKey
		);
	}

	/**
	 * Creates new TranslationField object from array
	 *
	 * @param array $field
	 * @throws \Bonnier\ServiceException
	 * @return self
	 */
	public static function fromArray(array $field) {
		$object = new self($field['label'], $field['value']);

		if(isset($field['_id'])) {
			$object->setId($field['_id']);
		}

		if(isset($field['group'])) {
			$object->setGroup($field['group']);
		}

		if(isset($field['display_format'])) {
			$object->setDisplayFormat($field['display_format']);
		}

		if(isset($field['shared_key'])) {
			$object->setSharedKey($field['shared_key']);
		}

		return $object;
	}

}