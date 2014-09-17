<?php
/**
 * Application starter
 *
 * @author Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

use \Kernel\Helper\Renderer;
use \Kernel\Router\Router;
use \Kernel\Database\Connection;

/**
 * Class Application
 */
class Application
{
    private static $_config;

    /**
     * Initializes application
     */
    public static function init()
    {
        try{
            Connection::getInstance();
            $content = Router::getInstance()->processRoute();
        } catch(\Exception $e){
            $renderer = new Renderer(APP_PATH.'/../src/Kernel/views/error.html.php');
            $renderer->assign(array('code' => $e->getCode(), 'message' => $e->getMessage(), 'backtrace' => $e->getTrace()));
            $content = $renderer->render();
        }

        self::_sendResponse($content);
    }

    /**
     * Loads application config
     *
     * @param array $config
     */
    public static function loadConfig($config)
    {
        self::$_config = $config;
    }

    /**
     * Returns config parameter by name or config array if parameter is not set
     *
     * @param string $param
     *
     * @return mixed
     */
    public static function getConfig($param = null)
    {
        return is_null($param)?self::$_config:(array_key_exists($param, self::$_config)?self::$_config[$param]:null);
    }

    /**
     * Injects particular page content into the main layout and sends it as response
     *
     * @param string $content
     */
    private static function _sendResponse($content)
    {
        $renderer = new Renderer(APP_PATH.'/layout.html.php');
        $renderer->assign('content', $content);
        $content = $renderer->render();
        Connection::getInstance()->disconnect();
        echo $content;
    }
} 