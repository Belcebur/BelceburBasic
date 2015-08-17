<?php
namespace BelceburBasic\Resource\Doctrine\Gedmo;

use BelceburBasic\Resource\Doctrine\Entity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Zend\Form\Annotation;

/**
 * Description of GenericEntity
 *
 * @author David Garcia
 * @ORM\MappedSuperclass()
 *
 */
abstract class EntityTranslatable extends Entity implements Translatable {

    /**
     * @Gedmo\Locale()
     * @Annotation\Exclude()
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    protected $locale;

    function __construct($params = NULL) {
        parent::__construct($params);
    }

    /**
     * @return mixed
     */
    public function getTranslatableLocale() {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     */
    public function setTranslatableLocale($locale) {
        $this->locale = $locale;
    }


}
