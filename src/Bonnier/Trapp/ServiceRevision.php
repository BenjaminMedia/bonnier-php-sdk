<?php
namespace Bonnier\Trapp;

use Bonnier\Trapp\Translation\TranslationField;
use Pecee\Http\Rest\RestBase;
use Pecee\Http\Rest\RestItem;
use Bonnier\ServiceException;
use Bonnier\Trapp\Translation\RevisionCollection;
use Bonnier\Trapp\Translation\TranslationRevision;

class ServiceRevision extends RestItem {

	const TYPE = 'revisions';

	public function __construct($username, $secret) {
		parent::__construct(new ServiceBase($username, $secret, self::TYPE));
		$this->service->setServiceEventListener($this);
		$this->service->getHttpRequest()->setPostJson(true);
		$this->row->revisions = array();
	}


	/**
	 * Get revision by id
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


	public function onCreateCollection() {
		return new RevisionCollection($this->service);
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
	 * @return RevisionCollection
	 */
	public function getCollection() {
		return $this->onCreateCollection();
	}

	public function setDevelopment($bool) {
		$this->service->setDevelopment($bool);
		return $this;
	}

	/**
	 * @return ServiceBase
	 */
	public function getService() {
		return parent::getService();
	}


	/**
	 * Get fields
	 *
	 * @return array(TranslationField)
	 */
	public function getFields() {
		$out = array();
		if(isset($this->row->fields) && count($this->row->fields)) {
			foreach($this->row->fields as $field) {
				$out[] = TranslationField::fromArray($field);
			}
		}

		return $out;
	}

	/**
	 * Get field
	 *
	 * @param int $index
	 * @return TranslationField|null
	 */
	public function getField($index) {
		if(isset($this->row->fields[$index])) {
			return TranslationField::fromArray($this->row->fields[$index]);
		}
		return null;
	}

	/**
	 * Get id
	 *
	 * @return string
	 */
	public function getId() {
		return $this->row->_id;
	}

	/**
	 * Get state
	 *
	 * @return string
	 */
	public function getState() {
		return $this->row->state;
	}

	/**
	 * Get id
	 *
	 * @return int
	 */
	public function getRevisionCount() {
		return $this->row->revision_count;
	}

	/**
	 * Get id
	 *
	 * @return string
	 */
	public function getComment() {
		return $this->row->comment;
	}

	/**
	 * Get id
	 *
	 * @return string
	 */
	public function getTranslationId() {
		return $this->row->entity_id;
	}


}
