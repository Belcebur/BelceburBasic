<?php

namespace BelceburBasic\View\Helper;

use Doctrine\ORM\EntityManager;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Application;
use Zend\Mvc\I18n\Translator;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;
use Zend\View\HelperPluginManager;

class BTools extends AbstractHelper
{
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
     * @var EntityManager
     */
    protected $em;

    /**
     * @var \Zend\Mvc\I18n\Translator
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

    public function __construct(HelperPluginManager $pluginManager)
    {
        $this->pluginManager = $pluginManager;
        $this->serviceManager = $pluginManager->getServiceLocator();
        $this->app = $pluginManager->getServiceLocator()->get('Application');
        $this->request = $this->app->getRequest();
        $this->event = $this->app->getMvcEvent();
        $this->em = $this->serviceManager->get(EntityManager::class);
        $this->translator = $this->serviceManager->get('MvcTranslator');
    }

    /**
     * Convert BR tags to newlines and carriage returns.
     *
     * @param string $string The string to convert
     * @param string $separator The string to use as line separator
     *
     * @return string The converted string
     */
    public static function br2nl($string, $separator = PHP_EOL): string
    {
        $separator = in_array($separator, ["\n", "\r", "\r\n", "\n\r", chr(30), chr(155), PHP_EOL], FALSE) ? $separator : PHP_EOL;

        return self::superTrim(preg_replace('/<br(\s*)?\/?>/i', $separator, $string));
    }

    public static function superTrim($val): string
    {
        return trim(\preg_replace(['#^(\d\.\s|-|&nbsp;)#'], [''], trim(html_entity_decode($val), " \t\n\r\0\x0B\xC2\xA0")), " \t\n\r\0\x0B\xC2\xA0");
    }

    /**
     * @param string $string
     * @return string
     */
    public static function nl2br(string $string): string
    {
        $exploded = \preg_split('/\s{2,}/', \preg_replace('/( ){2,}/', ' ', Tools::br2nl($string)));
        if (\count($exploded) === 1) {
            return self::superTrim(current($exploded));
        }
        return \implode('<br/><br/>', $exploded);
    }

    /**
     * @param array $attributes
     * @param string $quote
     * @return string
     */
    public static function arrayToAttributesStatic(array $attributes, $quote = '"'): string
    {
        $string = '';
        foreach ($attributes as $key => $attribute) {
            if (\is_array($attribute)) {
                $attrs = \explode(' ', \implode(' ', $attribute));
                \array_unique($attrs);
                $attribute = \implode(' ', $attribute);
            }
            $string .= " {$key}={$quote}{$attribute}{$quote}";
        }

        return $string;
    }

    /**
     * Return Current URL
     *
     * @param array $extraParams
     *
     * @return string
     */
    public function getCurrentUrl(array $extraParams = []): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI']);
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
    public function convertUrlQuery(string $query): array
    {
        if (!empty($query)) {
            return [];
        }
        $queryParts = explode('&', $query);
        $params = [];
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1] ?? '';
        }

        return $params;
    }

    /**
     * @param array $attributes
     *
     * @param string $quote
     *
     * @return string
     */
    public function arrayToAttributes(array $attributes = [], $quote = '"'): string
    {
        $string = '';
        foreach ($attributes as $key => $attribute) {
            $string .= " {$key}={$quote}{$attribute}{$quote}";
        }

        return $string;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->getRouteMatch() ? $this->getRouteMatch()->getParams() : [];
    }

    /**
     * @return \Zend\Mvc\Router\RouteMatch
     */
    public function getRouteMatch(): RouteMatch
    {
        return $this->getEvent()->getRouteMatch();
    }

    /**
     * @return \Zend\Mvc\MvcEvent
     */
    public function getEvent(): MvcEvent
    {
        return $this->event;
    }

    /**
     * @param \Zend\Mvc\MvcEvent $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @param string $name
     * @param        $default
     *
     * @return mixed
     */
    public function getParam($name, $default)
    {
        return $this->getRouteMatch() ? $this->getRouteMatch()->getParam($name, $default) : $default;
    }

    /**
     * @return \Zend\Http\PhpEnvironment\Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return EntityManager
     */
    public function getEm(): EntityManager
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @return \Zend\Mvc\I18n\Translator
     */
    public function getTranslator(): Translator
    {
        return $this->translator;
    }

    /**
     * @param \Zend\Mvc\I18n\Translator $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager(): ServiceManager
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
     * @return HelperPluginManager
     */
    public function getPluginManager(): HelperPluginManager
    {
        return $this->pluginManager;
    }

    /**
     * @param HelperPluginManager $pluginManager
     */
    public function setPluginManager($pluginManager)
    {
        $this->pluginManager = $pluginManager;
    }

    /**
     * @return \Zend\Mvc\Application
     */
    public function getApp(): Application
    {
        return $this->app;
    }

    /**
     * @param \Zend\Mvc\Application $app
     */
    public function setApp($app)
    {
        $this->app = $app;
    }

    /**
     * @param string $text
     * @param array $replace
     * @param string $delimiter
     *
     * @return string
     */
    public function slugify(string $text, array $replace = [], string $delimiter = '-'): string
    {
        return self::SlugifyStatic($text, $replace, $delimiter);
    }

    /**
     * Hace slugify con un sistema mas avanzado Require PHP >=5.4 + Perl >2.0
     *
     * @param string $text
     * @param array $replace
     * @param string $delimiter
     *
     * @return string
     */
    public static function SlugifyStatic(string $text, array $replace = [], string $delimiter = '-'): string
    {

        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', transliterator_transliterate('Any-Latin; Latin-ASCII', $text));

        if (!empty($replace)) {
            $str = str_replace($replace, ' ', $str);
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
        $cyrylicTo = array(
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
        $otherTo = array(
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

    /**
     * @param string $value
     * @param string $default
     * @return string
     */
    public function getMetaByProperty(string $value, string $default = ''): string
    {
        return $this->getMetaBy('property', $value);
    }

    /**
     * @param string $type
     * @param string $value
     * @param string $default
     * @return string
     */
    public function getMetaBy(string $type, string $value, string $default = ''): string
    {
        /**
         * @var \Zend\View\Renderer\PhpRenderer $view
         */
        $view = $this->getView();
        $headMeta = $view->headMeta();
        foreach ($headMeta->getContainer() as $meta) {
            $metaArray = (array)$meta;
            if (\array_key_exists($type, $metaArray) && $metaArray[$type] === $value) {
                return (string)$metaArray['content'];
            }
        }
        return (string)$default;
    }

    /**
     * @param string $value
     * @param string $default
     * @return string
     */
    public function getMetaByName(string $value, string $default = ''): string
    {
        return $this->getMetaBy('name', $value);
    }

}
