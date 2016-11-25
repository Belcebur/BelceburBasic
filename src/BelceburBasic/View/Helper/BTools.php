<?php

namespace BelceburBasic\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\HelperPluginManager;

class BTools extends AbstractHelper {
    /**
     *
     * @var \Zend\Http\PhpEnvironment\Request
     */
    protected $request;
    /**
     *
     * @var \Zend\Mvc\MvcEvent
     */
    protected $event;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Zend\I18n\Translator\Translator
     */
    protected $translator;


    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

    /**
     * @var \Zend\View\HelperPluginManager
     */
    protected $pluginManager;


    /**
     * @var \Zend\Mvc\Application
     */
    protected $app;

    public function __construct(HelperPluginManager $pluginManager) {
        $this->pluginManager  = $pluginManager;
        $this->serviceManager = $pluginManager->getServiceLocator();
        $this->app            = $pluginManager->getServiceLocator()->get('Application');
        $this->request        = $this->app->getRequest();
        $this->event          = $this->app->getMvcEvent();
        $this->em             = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $this->translator     = $this->serviceManager->get('translator');
    }

    /**
     * Return Current URL
     *
     * @param array $extraParams
     *
     * @return string
     */
    public function getCurrentUrl($extraParams = array()) {
        $uri    = parse_url($_SERVER['REQUEST_URI']);
        $params = array_merge($this->convertUrlQuery($_SERVER['QUERY_STRING']), $extraParams);

        return $uri['path'] . '?' . http_build_query($params);
    }

    /**
     * Returns the url query as associative array
     *
     * @param    string $query
     *
     * @return    array    params
     */
    public function convertUrlQuery($query) {
        if (!empty($query)) {
            return array();
        }
        $queryParts = explode('&', $query);
        $params     = array();
        foreach ($queryParts as $param) {
            $item             = explode('=', $param);
            $params[$item[0]] = isset($item[1]) ? $item[1] : '';
        }

        return $params;
    }

    /**
     * @param array  $attributes
     *
     * @param string $quote
     *
     * @return string
     */
    public function arrayToAttributes(array $attributes, $quote = '"') {
        $string = '';
        foreach ($attributes as $key => $attribute) {
            $string .= " {$key}={$quote}{$attribute}{$quote}";
        }

        return $string;
    }

    /**
     * @return array
     */
    public function getParams() {
        return $this->getRouteMatch() ? $this->getRouteMatch()->getParams() : array();
    }

    /**
     * @return \Zend\Mvc\Router\RouteMatch
     */
    public function getRouteMatch() {
        return $this->getEvent()->getRouteMatch();
    }

    /**
     * @return \Zend\Mvc\MvcEvent
     */
    public function getEvent() {
        return $this->event;
    }

    /**
     * @param \Zend\Mvc\MvcEvent $event
     */
    public function setEvent($event) {
        $this->event = $event;
    }

    /**
     * @param string $name
     * @param        $default
     *
     * @return mixed
     */
    public function getParam($name, $default) {
        return $this->getRouteMatch() ? $this->getRouteMatch()->getParam($name, $default) : $default;
    }

    /**
     * @return \Zend\Http\PhpEnvironment\Request
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     */
    public function setRequest($request) {
        $this->request = $request;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm() {
        return $this->em;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEm($em) {
        $this->em = $em;
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
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager() {
        return $this->serviceManager;
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function setServiceManager($serviceManager) {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return HelperPluginManager
     */
    public function getPluginManager() {
        return $this->pluginManager;
    }

    /**
     * @param HelperPluginManager $pluginManager
     */
    public function setPluginManager($pluginManager) {
        $this->pluginManager = $pluginManager;
    }

    /**
     * @return \Zend\Mvc\Application
     */
    public function getApp() {
        return $this->app;
    }

    /**
     * @param \Zend\Mvc\Application $app
     */
    public function setApp($app) {
        $this->app = $app;
    }

    /**
     * @param string $text
     * @param array  $replace
     * @param string $delimiter
     *
     * @return string
     */
    public function slugify($text, $replace = array(), $delimiter = '-') {
        return self::SlugifyStatic($text, $replace, $delimiter);
    }

    /**
     * Hace slugify con un sistema mas avanzado Require PHP >=5.4 + Perl >2.0
     *
     * @param string $text
     * @param array  $replace
     * @param string $delimiter
     *
     * @return string
     */
    public static function SlugifyStatic($text, $replace = array(), $delimiter = '-') {

        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', transliterator_transliterate('Any-Latin; Latin-ASCII', $text));

        if (!empty($replace)) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $initialChars = array(
            'Á' => 'A', 'Ç' => 'c', 'É' => 'e', 'Í' => 'i', 'Ñ' => 'n',
            'Ó' => 'o', 'Ú' => 'u', 'á' => 'a',
            'ç' => 'c', 'é' => 'e', 'í' => 'i', 'ñ' => 'n', 'ó' => 'o',
            'ú' => 'u', 'à' => 'a', 'è' => 'e',
            'ì' => 'i', 'ò' => 'o', 'ù' => 'u', 'ä' => 'a', 'Ä' => 'A',
            'ā' => 'a', 'Ḩ' => 'H', 'Š' => 'S',
            'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z',
            'Č' => 'C', 'č' => 'c', 'Ć' => 'C',
            'ć' => 'c', 'À' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Å' => 'A', 'Æ' => 'A',
            'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Ì' => 'I', 'Î' => 'I',
            'Ï' => 'I', 'Ò' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
            'Ù' => 'U', 'Ü' => 'U', 'Û' => 'U',
            'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'â' => 'a', 'ã' => 'a',
            'å' => 'a', 'æ' => 'a', 'ê' => 'e', 'ë' => 'e', 'î' => 'i', 'ï' => 'i',
            'ð' => 'o', 'ô' => 'o',
            'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
            'ü' => 'u', 'û' => 'u', 'ý' => 'y',
            'þ' => 'b', 'ÿ' => 'y',
            'Ŕ' => 'R', 'ŕ' => 'r', 'ū' => 'u', 'ī' => 'i',
            'Ā' => 'A', 'Ş' => 'S', 'ḩ' => 'h',
        );

        $cyrylicFrom = array(
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л',
            'М', 'Н', 'О', 'П', 'Р', 'С', 'Т',
            'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
            'а', 'б', 'в', 'г', 'д', 'е', 'ё',
            'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т',
            'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ',
            'ъ', 'ы', 'ь', 'э', 'ю', 'я'
        );
        $cyrylicTo   = array(
            'A', 'B', 'W', 'G', 'D', 'Ie', 'Io', 'Z', 'Z', 'I', 'J', 'K',
            'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T',
            'U', 'F', 'Ch', 'C', 'Tch', 'Sh', 'Shtch', '', 'Y', '', 'E',
            'Iu', 'Ia', 'a', 'b', 'w', 'g', 'd', 'ie',
            'io', 'z', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r',
            's', 't', 'u', 'f', 'ch', 'c', 'tch', 'sh',
            'shtch', '', 'y', '', 'e', 'iu', 'ia'
        );

        $cyrylicChars = array_combine($cyrylicFrom, $cyrylicTo);

        $otherFrom = array(
            'Á', 'À', 'Â', 'Ä', 'Ă', 'Ā', 'Ã', 'Å', 'Ą', 'Æ', 'Ć', 'Ċ', 'Ĉ',
            'Č', 'Ç', 'Ď', 'Đ', 'Ð', 'É',
            'È', 'Ė', 'Ê', 'Ë', 'Ě', 'Ē', 'Ę', 'Ə', 'Ġ', 'Ĝ', 'Ğ', 'Ģ', 'á',
            'à', 'â', 'ä', 'ă', 'ā', 'ã',
            'å', 'ą', 'æ', 'ć', 'ċ', 'ĉ', 'č', 'ç', 'ď', 'đ', 'ð', 'é', 'è',
            'ė', 'ê', 'ë', 'ě', 'ē', 'ę',
            'ə', 'ġ', 'ĝ', 'ğ', 'ģ', 'Ĥ', 'Ħ', 'I', 'Í', 'Ì', 'İ', 'Î', 'Ï',
            'Ī', 'Į', 'Ĳ', 'Ĵ', 'Ķ', 'Ļ',
            'Ł', 'Ń', 'Ň', 'Ñ', 'Ņ', 'Ó', 'Ò', 'Ô', 'Ö', 'Õ', 'Ő', 'Ø', 'Ơ',
            'Œ', 'ĥ', 'ħ', 'ı', 'í', 'ì',
            'i', 'î', 'ï', 'ī', 'į', 'ĳ', 'ĵ', 'ķ', 'ļ', 'ł', 'ń', 'ň', 'ñ',
            'ņ', 'ó', 'ò', 'ô', 'ö', 'õ',
            'ő', 'ø', 'ơ', 'œ', 'Ŕ', 'Ř', 'Ś', 'Ŝ', 'Š', 'Ş', 'Ť', 'Ţ', 'Þ',
            'Ú', 'Ù', 'Û', 'Ü', 'Ŭ', 'Ū',
            'Ů', 'Ų', 'Ű', 'Ư', 'Ŵ', 'Ý', 'Ŷ', 'Ÿ', 'Ź', 'Ż', 'Ž', 'ŕ', 'ř',
            'ś', 'ŝ', 'š', 'ş', 'ß', 'ť',
            'ţ', 'þ', 'ú', 'ù', 'û', 'ü', 'ŭ', 'ū', 'ů', 'ų', 'ű', 'ư', 'ŵ',
            'ý', 'ŷ', 'ÿ', 'ź', 'ż', 'ž'
        );
        $otherTo   = array(
            'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'C',
            'C', 'C', 'C', 'D', 'D', 'D', 'E',
            'E', 'E', 'E', 'E', 'E', 'E', 'E', 'G', 'G', 'G', 'G', 'G', 'a',
            'a', 'a', 'a', 'a', 'a', 'a',
            'a', 'a', 'ae', 'c', 'c', 'c', 'c', 'c', 'd', 'd', 'd', 'e',
            'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'g', 'g', 'g', 'g', 'g', 'H', 'H', 'I', 'I', 'I', 'I', 'I', 'I',
            'I', 'I', 'IJ', 'J', 'K', 'L',
            'L', 'N', 'N', 'N', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O',
            'CE', 'h', 'h', 'i', 'i', 'i',
            'i', 'i', 'i', 'i', 'i', 'ij', 'j', 'k', 'l', 'l', 'n', 'n',
            'n', 'n', 'o', 'o', 'o', 'o', 'o',
            'o', 'o', 'o', 'o', 'R', 'R', 'S', 'S', 'S', 'S', 'T', 'T', 'T',
            'U', 'U', 'U', 'U', 'U', 'U',
            'U', 'U', 'U', 'U', 'W', 'Y', 'Y', 'Y', 'Z', 'Z', 'Z', 'r', 'r',
            's', 's', 's', 's', 'B', 't',
            't', 'b', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'w',
            'y', 'y', 'y', 'z', 'z', 'z'
        );

        $otherChars = array_combine($otherFrom, $otherTo);
        $characters = array_merge($initialChars, $cyrylicChars, $otherChars);

        $str = strtr($str, $characters);
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', '-', $str);

        if (substr($str, strlen($str) - 1, strlen($str)) === '-') {
            $str = substr($str, 0, -1);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace('/[^a-zA-Z0-9\/_|+ -]/', '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace('/[\/_|+ -]+/', $delimiter, $clean);

        if ($clean === '') {
            return $str;
        }

        return $clean;
    }


}
