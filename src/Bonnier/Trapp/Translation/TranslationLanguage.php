<?php
namespace Bonnier\Trapp\Translation;

class TranslationLanguage {

	public $id;
	public $locale;
	public $isOriginal;

	public function __construct($locale, $isOriginal = false) {
		$this->locale = $locale;
        $this->isOriginal = $isOriginal;
	}

	/**
	 * @return string
	 */
	public function getLocale() {
		return $this->locale;
	}

    /**
     * @param $locale
     * @internal param string $label
     * @return self
     */
	public function setLocale($locale) {
		$this->locale = $locale;
        return $this;
	}

	/**
	 * @return bool
	 */
	public function isOriginal() {
		return $this->isOriginal;
	}

    /**
     * @return bool
     */
    private function getOriginal() {
        return $this->isOriginal;
    }

    /**
     * @param $original
     * @return bool
     */
    private function setOriginal($original) {
        $this->isOriginal = $original;
        return $this;
    }

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

    /**
     * @param $id
     * @return string
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

	public function toArray() {
		return (array) $this;
	}

	/**
	 * Creates new TranslationField object from array
	 *
	 * @param array $field
	 * @throws \Bonnier\ServiceException
	 * @return self
	 */
	public static function fromArray(array $field) {
		$object = new self($field['locale']);

        if(isset($field['is_original'])){
            $object->setOriginal($field['is_original']);
        }

        if(isset($field['id'])){
            $object->setId($field['id']);
        }
		return $object;
	}

}