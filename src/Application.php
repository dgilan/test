<?php
/**
 * Application starter
 *
 * @author Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

use \Kernel\Helper\Renderer;
use \Kernel\Router\Router;
use \Kernel\Database\Connection;
use \Kernel\Security\Token;
use \Kernel\Request\Request;

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
            Token::init();
            $content = Router::getInstance()->processRoute();
        } catch(\Exception $e){
            $renderer = new Renderer(APP_PATH.'/../src/Kernel/views/error.html.php');
            $renderer->assign(array('code' => $e->getCode(), 'message' => $e->getMessage(), 'backtrace' => $e->getTrace()));
            $content = $renderer->render();
        }

        self::_sendResponse($content);
    }

    /**
     * Redirects page
     *
     * @param string $url
     * @param string $flushMessage Flush message to be shown
     */
    public static function redirect($url, $flushMessage = null)
    {
        if ($flushMessage) {
            Token::set('flush', $flushMessage);
        }

        session_write_close();
        header('Location: '.Request::getHost().$url);
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
        $renderer->assign(
                 array(
                     'content' => $content,
                     'user'    => Token::getUser(),
                     'route'   => Router::getInstance()->getRoute()
                 )
        );

        $response = $renderer->render();
        Connection::getInstance()->disconnect();
        echo $response;
        Token::set('flush', null);
        exit;
    }
}