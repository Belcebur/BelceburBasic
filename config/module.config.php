<?php
return [
    'filters' => [
        'invokables' => [
            'nl2br' => \BelceburBasic\Form\Filter\Nl2br::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'bTools' => function (\Zend\View\HelperPluginManager $pluginManager) {
                return new \BelceburBasic\View\Helper\BTools($pluginManager);
            },
        ],
    ],
    'service_manager' => [
        'bAdmin' => \BelceburBasic\Service\AdminNavigationFactory::class,
    ],
    'belcebur-basic' => [
    ],
    'navigation' => [
        'bAdmin' => [],
        'default' => [],
    ]
];