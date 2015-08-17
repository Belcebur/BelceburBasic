<?php
/**
 * Created by PhpStorm.
 * User: dgarcia
 * Date: 25/02/2015
 * Time: 13:53
 */

namespace BelceburBasic\Service;

use Zend\Navigation\Service\AbstractNavigationFactory;

class AdminNavigationFactory extends AbstractNavigationFactory {
    /**
     * @return string
     */
    protected function getName() {
        return 'bAdmin';
    }
}
