<?php
/**
 * Created by PhpStorm.
 * User: dgarcia
 * Date: 29/07/14
 * Time: 11:35
 */

namespace BelceburBasic\Resource\Base;

use Doctrine\ORM\EntityManager;
use Zend\Http\Headers;
use Zend\Http\Request;
use Zend\Http\Response\Stream;
use Zend\I18n\Translator\Translator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\RequestInterface;

abstract class Controller extends AbstractActionController
{

    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    private $serviceManager;

    /**
     * @var EntityManager $entityManager
     */
    private $entityManager;

    /**
     * @var Translator $translator
     */
    private $translator;

    public function onDispatch(MvcEvent $e)
    {
        /**
         * @var \Zend\Mvc\I18n\Translator $mvcTranslator
         * @var EntityManager $entityManager
         */
        $serviceManager = $e->getApplication()->getServiceManager();

        if (!$this->serviceManager) {
            $this->setServiceManager($serviceManager);
        }

        if (!$this->entityManager) {
            $entityManager = $serviceManager->get(EntityManager::class);
            $this->setEntityManager($entityManager);
        }

        if (!$this->translator) {
            $mvcTranslator = $serviceManager->get('MvcTranslator');
            $translator = $mvcTranslator->getTranslator();

            $this->setTranslator($translator);
        }

        return parent::onDispatch($e);
    }

    /**
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
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

    /**
     * @return EntityManager|null
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Request|\Zend\Stdlib\RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * Genera un password
     *
     * @deprecated Use randomPassword
     * @param int $length
     * @return string
     */
    public function random_password($length = 8): string
    {
        return $this->randomPassword($length);
    }

    /**
     * Genera un password
     *
     * @param int $length
     *
     * @param string $chars
     * @return string
     */
    public function randomPassword($length = 8, string $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!$%^&_-.?'): string
    {
        return substr(str_shuffle($chars), 0, $length);
    }

    public function downloadFile($filePath, $filename = FALSE)
    {
        $name = $filename ?: basename($filePath);
        $response = new Stream();
        $response->setStream(fopen($filePath, 'r'));
        $response->setStatusCode(200);
        $response->setStreamName(basename($filePath));
        $headers = new Headers();
        $headers->addHeaders(array(
            'Content-Disposition' => "attachment; filename='{$name}'",
            'Content-Type' => 'application/octet-stream',
            'Content-Length' => filesize($filePath),
            'Expires' => '@0', // @0, because zf2 parses date as string to \DateTime() object
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public'
        ));
        $response->setHeaders($headers);

        return $response;
    }

    public function displayImage($imagePath)
    {
        /**
         * @var \SplFileInfo $fileInfo
         * @var \Zend\Http\PhpEnvironment\Response $response
         */
        $fileInfo = new \SplFileInfo($imagePath);
        $mime = mime_content_type($imagePath);
        $response = NULL;
        if ($fileInfo->isFile()) {
            $imageContent = file_get_contents($imagePath);
            $response = $this->getResponse();
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