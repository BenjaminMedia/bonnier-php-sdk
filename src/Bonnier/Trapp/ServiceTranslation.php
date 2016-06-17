<?php
namespace Bonnier\Trapp;

use Bonnier\Trapp\Translation\TranslationField;
use Bonnier\Trapp\Translation\TranslationLanguage;
use Pecee\Http\Rest\RestBase;
use Pecee\Http\Rest\RestItem;
use Bonnier\ServiceException;
use Bonnier\Trapp\Translation\TranslationCollection;
use Bonnier\Trapp\Translation\TranslationRevision;

class ServiceTranslation extends ServiceBase
{

    const TYPE = 'translation';

    public function __construct($username, $secret)
    {
        parent::__construct($username, $secret, self::TYPE);
        $this->fields = array();
        $this->translate_into = array();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Get translation by id
     *
     * @param int $id
     * @throws \Bonnier\ServiceException
     * @return self
     */
    public function getById($id)
    {
        if (is_null($id)) {
            throw new ServiceException('Invalid argument for parameter $id');
        }

        $newService = clone $this;

        $newService->setRow($this->api($id));

        return $newService;
    }


    // TODO: possibly rename to toArray() or delete if unused?
    public function getPostData()
    {
        $row = (array)$this->getRow();
        return $row;
    }

    /**
     * Update translation
     *
     * @throws \Bonnier\ServiceException
     * @return self
     */
    public function update()
    {
        $this->setRow($this->api($this->id, self::METHOD_PUT, $this->toArray()));
        return $this;
    }

    /**
     * Save translation
     *
     * @throws \Bonnier\ServiceException
     * @return self
     */
    public function save()
    {
        $this->setRow($this->api($this->id, self::METHOD_POST, $this->toArray()));
        return $this;
    }

    /**
     * Delete translation
     *
     * @throws \Bonnier\ServiceException
     * @return boolean
     */
    public function delete()
    {
        $response = $this->api($this->id, self::METHOD_DELETE, $this->toArray());
        if($response['deleted']) {
            $this->setRow([]);
        }
        return $response['deleted'];
    }

    public function onCreateCollection()
    {
        return new TranslationCollection($this->service);
    }

    /**
     * @return self
     */
    public function onCreateItem()
    {
        $self = new self($this->service->getUsername(), $this->service->getSecret());
        $self->setService($this->service);
        return $self;
    }

    /**
     * Get queryable translation collection.
     *
     * @return TranslationCollection
     */
    public function getCollection()
    {
        return $this->onCreateCollection();
    }

    /**
     * Get locale for the original item
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Get edit uri for the item
     *
     * @return string
     */
    public function getEditUri()
    {
        return $this->edit_uri;
    }

    /**
     * Set the locale for the original item
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }


    /**
     * Returns an array of related translations
     *
     * @return array(TranslationLanguage)
     */
    public function getRelatedTranslations()
    {
        $out = array();
        if (isset($this->related_translations) && count($this->related_translations)) {
            foreach ($this->related_translations as $language) {
                $out[] = TranslationLanguage::fromArray($language);
            }
        }
        return $out;
    }

    /**
     * Add language for the item to be translated into
     *
     * @param string $locale a valid locale ie: da_dk, sv_se, ect.
     * @return ServiceTranslation
     */
    public function addTranslatation($locale)
    {
        $translateInto = $this->getTranslateInto();
        $translateInto[] = $locale;
        $this->setTranslateInto($translateInto);
        return $this;
    }

    /**
     * Sets new array of languages to be translated into
     * @param array $locales
     * @return $this
     * @throws ServiceException
     */
    public function setTranslateInto(array $locales)
    {
        $this->translate_into = $locales;
        return $this;
    }

    /**
     * Returns an array of languages to be translated into
     *
     * @return array(TranslationLanguage)
     */
    public function getTranslateInto()
    {
        return $this->translate_into;
    }

    /**
     * Check if item has a translation with provided locale
     *
     * @param string $locale the locale to look for
     * @return array
     */
    public function hasTranslation($locale)
    {
        if (isset($this->related_translations) && count($this->related_translations)) {
            /** @var TranslationLanguage $translation */
            foreach ($this->related_translations as $translation) {
                if (TranslationLanguage::fromArray($translation)->getLocale() === $locale) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * Set comment
     *
     * @param string $comment
     * @return self
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Set comment
     *
     * @return string Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set comment
     *
     * @return string Comment
     */
    public function getOriginalId()
    {
        return $this->original_entity_id;
    }

    /**
     * Set comment
     *
     * @return string Comment
     */
    public function isOriginal()
    {
        return is_null($this->original_entity_id);
    }

    /**
     * Set state
     *
     * @param string $state
     * @return self
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Get state
     * @return ServiceTranslation
     * @internal param string $state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Add field to be translated
     *
     * @param TranslationField $field
     * @return self
     */
    public function addField(TranslationField $field)
    {
        $fields = $this->getFields();
        $fields[] = $field;
        $this->setFields($fields);
        return $this;
    }

    /**
     * Get array of fields
     *
     * @return array(TranslationField)
     */
    public function getFields()
    {
        $out = array();
        if (isset($this->fields) && count($this->fields)) {
            foreach ($this->fields as $field) {
                $out[] = TranslationField::fromArray($field);
            }
        }

        return $out;
    }

    /*
    * Get array of fields
    *
    * @return array(TranslationField)
    */
    public function getFieldBySharedKey($sharedKey)
    {
        if (isset($this->fields) && count($this->fields)) {
            foreach ($this->fields as $field) {
                if($field['shared_key'] == $sharedKey){
                    return TranslationField::fromArray($field);
                }
            }
        }

        return false;
    }


    /**
     * Set fields
     *
     * @param array $fields must be of type TranslationField
     * @return self
     * @throws ServiceException
     */
    public function setFields(array $fields)
    {
        $newFields = [];
        /** @var \Bonnier\Trapp\Translation\TranslationField $field */
        foreach ($fields as $field) {
            if (!$field instanceof TranslationField) {
                throw new ServiceException('Objects in array passed to setFields() must be instance of TranslationField');
            }
            $newFields[] = $field->toArray();
        }
        $this->fields = $newFields;
        return $this;
    }


    /**
     * Get the deadline
     *
     * @return \DateTime
     */
    public function getDeadline()
    {
        return new \DateTime($this->deadline);
    }


    /**
     * Set the deadline
     *
     * @param \DateTime $datetime
     * @return $this
     */
    public function setDeadline(\DateTime $datetime)
    {
        $this->deadline = $datetime->format(DATE_W3C);
        return $this;
    }

    /**
     * Returns the title
     *
     * @return string title
     */
    public function getTitle()
    {
        return $this->title;
    }


    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Returns the app_code
     *
     * @return string app_code
     */
    public function getAppCode()
    {
        return $this->app_code;
    }


    public function setAppCode($appCode)
    {
        $this->app_code = $appCode;
        return $this;
    }

    /**
     * Returns the brand_code
     *
     * @return string brand_code
     */
    public function getBrandCode()
    {
        return $this->brand_code;
    }


    public function setBrandCode($brandCode)
    {
        $this->brand_code = $brandCode;
        return $this;
    }


    /**
     * @return ServiceBase
     */
    public function getService()
    {
        return $this;
    }

    /**
     * Create new object from callback response.
     *
     * @param string $username
     * @param string $secret
     * @param string $response the raw response body string containing encoded json
     *
     * @return static
     */
    public static function fromCallback($username, $secret, $response)
    {
        $data = (object) json_decode($response, true);
        $translation = new static($username, $secret);
        $translation->setRow($data);
        return $translation;
    }

}
