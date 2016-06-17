<?php
namespace Bonnier\Trapp\Translation;

class TranslationLanguage {

	public $id;
	public $locale;
	public $isOriginal;
	public $editUri;
	public $state;

	public function __construct($locale, $isOriginal = false, $editUri = null, $state = null) {
		$this->locale = $locale;
        $this->isOriginal = $isOriginal;
        $this->editUri = $editUri;
        $this->state = $state;
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
	 * @return string|null
	 */
	public function getEditUri()
	{
		return $this->editUri;
	}

	/**
	 * @param string $editUri
	 */
	public function setEditUri($editUri)
	{
		$this->editUri = $editUri;
	}

	/**
	 * @return string|null $state
	 */
	public function getState()
	{
		return $this->state;
	}

	/**
	 * @param string $state
	 */
	public function setState($state)
	{
		$this->state = $state;
	}

	/**
     * @return bool
     */
    private function getIsOriginal() {
        return $this->isOriginal;
    }

    /**
     * @param $original
     * @return bool
     */
    private function setIsOriginal($original) {
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
            $object->setIsOriginal($field['is_original']);
        }

        if(isset($field['id'])){
            $object->setId($field['id']);
        }

		if(isset($field['state'])){
			$object->setState($field['state']);
		}

		if(isset($field['edit_uri'])){
			$object->setEditUri($field['edit_uri']);
		}

		return $object;
	}

}