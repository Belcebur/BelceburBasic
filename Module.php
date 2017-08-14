<?php

namespace BelceburBasic;

use Zend\Loader\StandardAutoloader;
use Zend\Mvc\MvcEvent;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        /**
         * @var \Zend\ServiceManager\ServiceManager $sm
         * @var \Zend\Mvc\Application $application
         */
        $application = $e->getApplication();
        $eventManager = $application->getEventManager();
        $translator = $application->getServiceManager()->get('translator');
        $eventManager->attach(MvcEvent::EVENT_ROUTE, [$this, 'configGedmoTranslations'], -1000);
    }

    public function configGedmoTranslations(MvcEvent $e)
    {
        /**
         * @var \Zend\Mvc\Application $application
         * @var \Zend\Mvc\I18n\Translator $translator
         */

        $application = $e->getApplication();

        $eventManager = $application->getEventManager();
        $translator = $application->getServiceManager()->get('translator');
        define('BELCEBUR_GEDMO_TRANSLATION_LOCALE', $translator->getLocale());
        define('BELCEBUR_GEDMO_TRANSLATION_FALLBACK_LOCALE', $translator->getFallbackLocale());
    }

    public function getConfig(): array
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig(): array
    {
        return array(
            StandardAutoloader::class => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }


}
