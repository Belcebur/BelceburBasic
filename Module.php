<?php
namespace BelceburBasic;

use Zend\Mvc\MvcEvent;

class Module {

    public function onBootstrap(MvcEvent $e) {
        /**
         * @var \Zend\ServiceManager\ServiceManager $sm
         * @var \Zend\Mvc\Application               $application
         */
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'configGedmoTranslations'), -100);
    }

    public function configGedmoTranslations(MvcEvent $e) {
        /**
         * @var \Zend\Mvc\Application            $application
         * @var \Zend\I18n\Translator\Translator $translator
         */
        $application = $e->getApplication();
        $translator  = $e->getApplication()->getServiceManager()->get('translator');
        define('BELCEBUR_GEDMO_TRANSLATION_LOCALE', $translator->getLocale());
        define('BELCEBUR_GEDMO_TRANSLATION_FALLBACK_LOCALE', $translator->getFallbackLocale());
    }


    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }


    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }


}
