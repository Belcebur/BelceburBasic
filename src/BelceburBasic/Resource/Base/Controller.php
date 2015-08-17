<?php
/**
 * Created by PhpStorm.
 * User: dgarcia
 * Date: 29/07/14
 * Time: 11:35
 */

namespace BelceburBasic\Resource\Base;

use Zend\Http\Headers;
use Zend\Http\Response\Stream;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Exception;
use Zend\Mvc\MvcEvent;

abstract class Controller extends AbstractActionController {

    /**
     * @var \Doctrine\ORM\EntityManager $entityManager
     */
    private $entityManager = NULL;

    /**
     * @var \Zend\I18n\Translator\Translator $translator
     */
    private $translator = NULL;

    public function onDispatch(MvcEvent $e) {
        /**
         * @var \Zend\I18n\Translator\Translator $translator
         * @var \Doctrine\ORM\EntityManager      $entityManager
         */
        if (!$this->entityManager) {
            $entityManager = $e->getApplication()->getServiceManager()->get('Doctrine\ORM\EntityManager');
            $this->setEntityManager($entityManager);
        }

        if (!$this->translator) {
            $translator = $e->getApplication()->getServiceManager()->get('translator');
            $this->setTranslator($translator);
        }

        return parent::onDispatch($e);
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

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager() {
        return $this->entityManager;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function setEntityManager($entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @return \Zend\Http\Request
     */
    public function getRequest() {
        return $this->request;
    }


    /**
     * Genera un password
     *
     * @param int $length
     *
     * @return string
     */
    function random_password($length = 8) {
        $chars    = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!$%^&_-.?";
        $password = substr(str_shuffle($chars), 0, $length);

        return $password;
    }


    public function downloadFile($filePath, $filename = FALSE) {
        $name     = $filename ? $filename : basename($filePath);
        $response = new Stream();
        $response->setStream(fopen($filePath, 'r'));
        $response->setStatusCode(200);
        $response->setStreamName(basename($filePath));
        $headers = new Headers();
        $headers->addHeaders(array(
            'Content-Disposition' => "attachment; filename='{$name}'",
            'Content-Type'        => 'application/octet-stream',
            'Content-Length'      => filesize($filePath),
            'Expires'             => '@0', // @0, because zf2 parses date as string to \DateTime() object
            'Cache-Control'       => 'must-revalidate',
            'Pragma'              => 'public'
        ));
        $response->setHeaders($headers);

        return $response;
    }

    public function displayImage($imagePath) {
        /**
         * @var \SplFileInfo                       $fileInfo
         * @var \Zend\Http\PhpEnvironment\Response $response
         */
        $fileInfo = new \SplFileInfo($imagePath);
        $mime     = mime_content_type($imagePath);
        $response = NULL;
        if ($fileInfo->isFile()) {
            $imageContent = file_get_contents($imagePath);
            $response     = $this->getResponse();
            $response->setContent($imageContent);
            $response
                ->getHeaders()
                ->addHeaderLine('Content-Transfer-Encoding', 'binary')
                ->addHeaderLine('Content-Type', $mime)
                ->addHeaderLine('Content-Length', mb_strlen($imageContent));
        }

        return $response;
    }


} 