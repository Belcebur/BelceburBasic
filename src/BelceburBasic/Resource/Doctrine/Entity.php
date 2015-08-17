<?php
namespace BelceburBasic\Resource\Doctrine;


use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Description of Entity
 *
 * @author David Garcia
 * @ORM\MappedSuperclass()
 *
 */
abstract class Entity {

    function __construct($params = NULL) {
    }

    /**
     * Fill Object From Array
     *
     * @param array $data
     *
     * @return $this
     */
    public static function setFromArray(array $data) {
        $hydrator = new ClassMethodsHydrator();

        return $hydrator->hydrate($data, new static());
    }

    /**
     * @param $name
     *
     * @return string
     */
    public static function fieldToSetterMethod($name) {
        return 'set' . static::toCamelCase($name);
    }

    /**
     * @param $name
     *
     * @return string
     */
    public static function toCamelCase($name) {
        return implode('', array_map('ucfirst', explode('_', $name)));
    }

    /**
     * @param $name
     *
     * @return string
     */
    public static function fieldToGetterMethod($name) {
        return 'get' . static::toCamelCase($name);
    }


    /**
     * @param $name
     *
     * @return string
     */
    public static function fromCamelCase($name) {
        return trim(
            preg_replace_callback(
                '/([A-Z])/', function ($c) {
                return '_' . strtolower($c[1]);
            }, $name
            ), '_'
        );
    }

    /**
     * @param $name
     *
     * @return null
     */

    public function getProperty($name) {
        return isset($this->$name) ? $this->$name : NULL;
    }

    /**
     * Object To Array
     *
     * @return array
     */
    public function toArray() {
        $hydrator = new ClassMethodsHydrator();

        return $hydrator->extract($this);
    }


    /**
     * List Get Methods
     *
     * @return array
     */
    public function extractGetMethods() {
        return $this->extractMethods('get');
    }

    /**
     *
     * Extract Methods startBy
     *
     * @param      $startBy
     * @param bool $class
     *
     * @return array
     */
    private function extractMethods($startBy, $class = FALSE) {
        if (!$class) {
            $class = $this;
        }
        $classMethods = array_diff((array)get_class_methods(get_class($class)), (array)get_class_methods(get_parent_class($class)));

        return array_filter($classMethods,
            function ($method) use ($startBy) {
                return preg_match("/^{$startBy}/", $method);
            }
        );
    }

    /**
     * List Set Methods
     *
     * @return array
     */
    public function extractSetMethods() {
        return $this->extractMethods('set');
    }
}
