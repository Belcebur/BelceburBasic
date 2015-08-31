<?php
namespace BelceburBasic\Form\Filter;
use Zend\Filter\FilterInterface;

class Nl2br implements FilterInterface {
    public function filter($value) {
        return nl2br($value);
    }
}