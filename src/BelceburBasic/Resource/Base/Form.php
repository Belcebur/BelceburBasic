<?php

namespace BelceburBasic\Resource\Base;

use Zend\Form\Form as ZendForm;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class Form extends ZendForm {

    /**
     * @var \Doctrine\ORM\EntityManager
     * @name $em
     *
     */
    protected $em;


    /**
     * Doctrine Entity
     */
    protected $entity;


    /**
     *
     * @var \Zend\I18n\Translator\Translator
     * @name $translator
     */
    protected $translator;

    /**
     * @param array $options
     */
    function __construct($options = array()) {
        parent::__construct();
        $this->em         = isset($options['em']) ? $options['em'] : NULL;
        $this->entity     = isset($options['entity']) ? $options['entity'] : NULL;
        $this->translator = isset($options['translator']) ? $options['translator'] : NULL;
        $this
            ->setAttribute('method', 'post')
            ->setAttribute('class', 'form-horizontal')
            ->setAttributes(isset($options['attributes']) ? $options['attributes'] : array());

        $this
            ->setInputFilter(new InputFilter())
            ->setHydrator(new ClassMethodsHydrator(FALSE));
    }

    /**
     * @return \Zend\I18n\Translator\Translator
     */
    public function getTranslator() {
        return $this->translator;
    }

    /**
     * @param \Zend\I18n\Translator\Translator $translator
     */
    public function setTranslator($translator) {
        $this->translator = $translator;
    }


    public function getInputFilterSpecification() {
        return array();
    }

    public function addElements($elements = array()) {
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    public function getHiddenElements() {
        $hiddenElements = array();
        foreach ($this->getElements() as $key => $element) {
            if (is_a($element, '\Zend\Form\Element\Hidden')) {
                $hiddenElements[] = $element;
            }
        }

        return $hiddenElements;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm() {
        return $this->em;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEm($em) {
        $this->em = $em;
    }

    /**
     * @return $this->entity
     */
    public function getEntity() {
        return $this->entity;
    }

    /**
     * @param $entity
     */
    public function setEntity($entity) {
        $this->entity = $entity;
    }

    public function removeFromFieldset($fieldsetName, array $names) {
        /**
         *
         * @var \Zend\Form\Fieldset $fieldset
         */
        $fieldset = $this->get($fieldsetName);
        foreach ($names as $name) {
            $fieldset->remove($name);
            $this->getInputFilter()->get($fieldsetName)->remove($name);
        }
    }
}