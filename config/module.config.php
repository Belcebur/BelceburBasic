<?php
return array(
    'view_helpers'    => array(
        'factories' => array(
            'bTools' => function (\Zend\View\HelperPluginManager $pluginManager) {
                return new \BelceburBasic\View\Helper\BTools($pluginManager);
            },
        ),
    ),
    'service_manager' => array(
        'bAdmin' => 'BelceburBasic\Service\AdminNavigationFactory',
    ),
    'belcebur'        => array(
        'belcebur-basic' => array(),
    ),
    'navigation'      => array(
        'bAdmin'  => array(),
        'default' => array(),
    )
);