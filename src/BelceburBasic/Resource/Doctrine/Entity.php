<?php

namespace BelceburBasic\Resource\Doctrine;


use Doctrine\ORM\Mapping as ORM;
use Zend\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Description of Entity
 *
 * @author David Garcia
 * @ORM\MappedSuperclass()
 *
 */
abstract class Entity
{

    /**
     * Fill Object From Array
     *
     * @param array $data
     *
     * @return Entity|object
     * @throws \Zend\Hydrator\Exception\BadMethodCallException
     */
    public static function setFromArray(array $data)
    {
        $hydrator = new ClassMethodsHydrator();

        return $hydrator->hydrate($data, new static());
    }

    /**
     * @param $name
     *
     * @return null
     */

    public function getProperty($name)
    {
        return $this->$name ?? NULL;
    }

    /**
     * Object To Array
     *
     * @return array
     * @throws \Zend\Hydrator\Exception\BadMethodCallException
     */
    public function toArray(): array
    {
        $hydrator = new ClassMethodsHydrator();

        return $hydrator->extract($this);
    }
}
