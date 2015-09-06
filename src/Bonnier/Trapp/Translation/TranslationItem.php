<?php
namespace Bonnier\Trapp\Translation;

use Bonnier\RestItem;

class TranslationItem extends RestItem {

	protected $revisions;

	public function __construct($username, $secret, $type) {
		parent::__construct($username, $secret, $type);
		$this->revisions = array();
	}

	public function setRow(\stdClass $row) {
		parent::setRow($row);

		// TODO: Parse $this->row and set the revisions to $this->revisions
	}

	/**
	 * Checks if theres any revisions availible
	 * @return bool
	 */
	public function hasRevisions() {
		return (count($this->revisions) > 0);
	}

	/**
	 * Get revision by index
	 * @param $index
	 *
	 * @return TranslationRevision|NULL
	 */
	public function getRevision($index) {
		return (isset($this->revisions[$index])) ? $this->revisions[$index] : NULL;
	}

	/**
	 * Add new revision
	 * @param TranslationRevision $revision
	 */
	public function addRevision(TranslationRevision $revision) {
		$this->revisions[] = $revision;

		// TODO: Add revision to $this->row

	}

	/**
	 * Get the original revision
	 * @return TranslationRevision|NULL
	 */
	public function getOriginalRevision() {
		return $this->getRevision(0);
	}

}