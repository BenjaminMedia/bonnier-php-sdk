<?php
namespace Bonnier\Trapp;

use Pecee\Http\Rest\RestBase;
use Pecee\Http\Rest\RestItem;
use Bonnier\ServiceException;
use Bonnier\Trapp\Translation\TranslationCollection;
use Bonnier\Trapp\Translation\TranslationRevision;

class ServiceTranslation extends RestItem {

	const TYPE = 'translation';

	public function __construct($username, $secret) {
		parent::__construct(new ServiceBase($username, $secret, self::TYPE));
		$this->service->setServiceEventListener($this);
		$this->service->getHttpRequest()->setPostJson(true);
		$this->row->revisions = array();
		$this->row->fields = array();
		$this->row->translate_into = array();
	}


	/**
	 * Get translation by id
	 *
	 * @param int $id
	 * @throws \Bonnier\ServiceException
	 * @return self
	 */
	public function getById($id) {
		if(is_null($id)) {
			throw new ServiceException('Invalid argument for parameter $id');
		}

		return $this->api($id);
	}

	protected function getPostData() {
		// TODO: only post fields that the api understand
		$row = (array)$this->getRow();
		$revision = $this->getRevision(count($this->getRevisions())-1);
		if($revision) {
			$revision = $revision->toArray();
			$fields = $revision['fields'];
			$row['fields'] = $fields;
		}

		return $row;
	}

	/**
	 * Update translation
	 *
	 * @throws \Bonnier\ServiceException
	 * @return self
	 */
	public function update() {
		$this->row = $this->api($this->id, RestBase::METHOD_PUT, $this->getPostData())->getRow();
		return $this;
	}

	/**
	 * Save translation
	 *
	 * @throws \Bonnier\ServiceException
	 * @return self
	 */
	public function save() {
		$this->row = $this->api($this->id, RestBase::METHOD_POST, $this->getPostData())->getRow();
		return $this;
	}


	public function onCreateCollection() {
		return new TranslationCollection($this->service);
	}

	/**
	 * @return self
	 */
	public function onCreateItem(){
		$self = new self($this->service->getUsername(), $this->service->getSecret());
		$self->setService($this->service);
		return $self;
	}

	/**
	 * Get queryable translation collection.
	 *
	 * @return TranslationCollection
	 */
	public function getCollection() {
		return $this->onCreateCollection();
	}

	public function setDevelopment($bool) {
		$this->service->setDevelopment($bool);
		return $this;
	}

	/**
	 * Checks if theres any revisions available
	 *
	 * @return bool
	 */
	public function hasRevisions() {
		return (count($this->row->revisions) > 0);
	}

	/**
	 * Get revision by index
	 * @param int $index
	 * @return TranslationRevision
	 */
	public function getRevision($index) {
		return (isset($this->row->revisions[$index])) ? TranslationRevision::fromArray((object)$this->row->revisions[$index]) : null;
	}

	/**
	 * Get all revisions
	 *
	 * @return array
	 */
	public function getRevisions() {
		$out = array();
		if(isset($this->row->revisions)) {
			foreach($this->row->revisions as $revision) {
				$out[] = TranslationRevision::fromArray((object)$revision);
			}
		}
		return $out;
	}

	/**
	 * Get the original revision
	 *
	 * @return TranslationRevision|null
	 */
	public function getOriginalRevision() {
		return $this->getRevision(0);
	}

	/**
	 * Get latest revision
	 *
	 * @return TranslationRevision|null
	 */
	public function getLatestRevision() {
		return (count($this->row->revisions)) ? TranslationRevision::fromArray(end($this->row->revisions)) : null;
	}

	/**
	 * Get locale for the original item
	 *
	 * @return string
	 */
	public function getLocale() {
		return $this->row->locale;
	}

	/**
	 * Set the locale for the original item
	 *
	 * @param string $locale
	 */
	public function setLocale($locale) {
		$this->row->locale = $locale;
	}

	public function getLanguages() {
		return $this->row->translate_into;
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

	public function addRevision(TranslationRevision $revision) {
		$this->row->revisions[] = $revision->toArray();
		return $this;
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

	/**
	 * @return ServiceBase
	 */
	public function getService() {
		return parent::getService();
	}

}