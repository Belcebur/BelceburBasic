<?php

namespace BelceburBasic\Resource\Base;

use Doctrine\ORM\EntityManager;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Form\Element\Hidden;
use Zend\Form\Fieldset;
use Zend\Form\Form as ZendForm;
use Zend\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\I18n\Translator\Translator;
use Zend\InputFilter\InputFilter;

class Form extends ZendForm
{

    /**
     * @var EntityManager
     * @name $em
     *
     */
    protected $em;

    /**
     * Doctrine Entity
     */
    protected $entity;

    /**
     * @var Translator
     * @name $translator
     */
    protected $translator;

    /**
     * @param array $options
     */
    public function __construct($options = [])
    {
        parent::__construct();
        $this->em = $options['em'] ?? NULL;
        $this->entity = $options['entity'] ?? NULL;
        $this->translator = $options['translator'] ?? NULL;
        $this
            ->setAttribute('method', 'post')
            ->setAttribute('class', 'form-horizontal')
            ->setAttributes($options['attributes'] ?? []);

        $this
            ->setInputFilter(new InputFilter())
            ->setHydrator(new ClassMethodsHydrator(FALSE));
    }

    /**
     * @return Translator|null
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param Translator $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }


    public function getInputFilterSpecification(): array
    {
        return [];
    }

    public function addElements(array $elements = [])
    {
        foreach ($elements as $element) {
            $this->add($element);
        }
        return $this;
    }

    public function getHiddenElements(): array
    {
        $hiddenElements = [];
        foreach ($this->getElements() as $key => $element) {
            if (is_a($element, Hidden::class)) {
                $hiddenElements[] = $element;
            }
        }

        return $hiddenElements;
    }

    /**
     * @return \Doctrine\ORM\EntityManager|null
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @return $this->entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function removeFromFieldset(string $fieldsetName, array $names = [])
    {
        /**
         * @var \Zend\Form\Fieldset $fieldset
         */
        $fieldset = $this->get($fieldsetName);
        foreach ($names as $name) {
            $fieldset->remove($name);
            $inputFieldset = $this->getInputFilter();
            if ($inputFieldset->has($fieldsetName)) {
                $inputFieldset->get($fieldsetName)->remove($name);
            }
        }
    }

    /**
     * @param Fieldset $fieldset
     * @param $entity
     * @return null|\Zend\InputFilter\InputFilterInterface
     * @throws \Zend\InputFilter\Exception\InvalidArgumentException
     * @throws \Zend\Form\Exception\InvalidArgumentException
     */
    public function applyFormAnnotationSpecificationsFromEntity(Fieldset $fieldset, $entity)
    {
        $builder = new AnnotationBuilder();
        $inputFilter = $this->getInputFilter();
        $fieldsetInputFilter = $inputFilter->get($fieldset->getName());
        foreach ((array)$builder->getFormSpecification($entity)->offsetGet('input_filter') as $name => $filters) {
            if ($fieldset->has($name)) {
                $fieldsetInputFilter->add($filters, $name);
            }
        }
        return $inputFilter;
    }
}