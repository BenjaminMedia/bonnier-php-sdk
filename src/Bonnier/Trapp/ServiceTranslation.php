<?php
namespace Bonnier\Trapp;

use Bonnier\Trapp\Translation\TranslationField;
use Bonnier\Trapp\Translation\TranslationLanguage;
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
		$this->row->fields = array();
		$this->row->translate_into = array();
	}

    public function getId() {
        return $this->id;
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


    // TODO: possibly rename to toArray() or delete if unused?
	public function getPostData() {
		$row = (array)$this->getRow();
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


    /**
     * Returns an array of languages to be translated into
     *
     * @return array(TranslationLanguage)
     */
    public function getTranslateInto() {
        $out = array();
        if(isset($this->row->translate_into) && count($this->row->translate_into)) {
            foreach($this->row->translate_into as $language) {
                $out[] = TranslationLanguage::fromArray($language);
            }
        }
        return $out;
	}

    /**
     * Add language for the item to be translated into
     *
     * @param TranslationLanguage $language
     * @return ServiceTranslation
     */
    public function addTranslateInto(TranslationLanguage $language) {
        $this->row->translate_into[] = $language->getLocale();
        return $this;
    }

    /**
     * Sets new array of languages to be translated into
     * @param array $languages
     * @return $this
     * @throws ServiceException
     */
    public function setTranslateInto(array $languages) {
        $newLanguages = [];
        /** @var \Bonnier\Trapp\Translation\TranslationField $field */
        foreach ($languages as $language) {
            if (! $language instanceof TranslationLanguage) {
                throw new ServiceException('Objects in array passed to setTranslateInto() must be instance of TranslationLanguage');
            }
            $newLanguages[] = $language->getLocale();
        }
        $this->row->translate_into = $newLanguages;
        return $this;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return self
     */
    public function setComment($comment) {
		$this->row->comment = $comment;
		return $this;
	}

    /**
     * Set comment
     *
     * @return string Comment
     */
    public function getComment() {
        return  $this->row->comment;
    }

    /**
     * Set comment
     *
     * @return string Comment
     */
    public function getOriginalId() {
        return  $this->row->original_entity_id;
    }

    /**
     * Set comment
     *
     * @return string Comment
     */
    public function isOriginal() {
        return is_null($this->row->original_entity_id);
    }

    /**
     * Set state
     *
     * @param string $state
     * @return self
     */
    public function setState($state){
		$this->row->state = $state;
		return $this;
	}

    /**
     * Add field to be translated
     *
     * @param TranslationField $field
     * @return self
     */
    public function addField(TranslationField $field) {
        $this->row->fields[] = $field->toArray();
        return $this;
    }

    /**
     * Get array of fields
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
     * Set fields
     *
     * @param array $fields must be of type TranslationField
     * @return self
     * @throws ServiceException
     */
    public function setFields(array $fields) {
        $newFields = [];
        /** @var \Bonnier\Trapp\Translation\TranslationField $field */
        foreach ($fields as $field) {
            if (! $field instanceof TranslationField) {
                throw new ServiceException('Objects in array passed to setFields() must be instance of TranslationField');
            }
            $newFields[] = $field->toArray();
        }
        $this->row->fields = $newFields;
        return $this;
    }


    /**
     * Get the deadline
     *
     * @return \DateTime
     */
    public function getDeadline() {
		return new \DateTime($this->row->deadline);
	}


    /**
     * Set the deadline
     *
     * @param \DateTime $datetime
     * @return $this
     */
    public function setDeadline(\DateTime $datetime) {
		$this->row->deadline = $datetime->format(DATE_W3C);
		return $this;
	}

    /**
     * Returns the title
     *
     * @return string title
     */
    public function getTitle() {
		return $this->row->title;
	}


	public function setTitle($title) {
		$this->row->title = $title;
		return $this;
	}

    /**
     * Returns the app_code
     *
     * @return string app_code
     */
    public function getAppCode() {
        return $this->row->app_code;
    }


    public function setAppCode($appCode) {
        $this->row->app_code = $appCode;
        return $this;
    }

    /**
     * Returns the brand_code
     *
     * @return string brand_code
     */
    public function getBrandCode() {
        return $this->row->brand_code;
    }


    public function setBrandCode($brandCode) {
        $this->row->brand_code = $brandCode;
        return $this;
    }


    /**
	 * @return ServiceBase
	 */
	public function getService() {
		return parent::getService();
	}

	/**
	 * Create new object from callback response.
	 *
	 * @param string $username
	 * @param string $secret
	 * @param \stdClass $response
	 *
	 * @return static
	 */
	public static function fromCallback($username, $secret, \stdClass $response) {
		$translation = new static($username, $secret);
		$translation->setRow($response);
		return $translation;
	}

}
