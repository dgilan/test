<?php
/**
 * Helper for working with HTTP Request data
 *
 * @package Kernel\Request
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace Kernel\Request;

/**
 * Class Request
 *
 * @package Kernel\Request
 */
class Request
{
    /**
     * Returns current uri
     *
     * @return string
     */
    public static function  getUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Checks is request method post
     *
     * @return bool
     */
    public static function isPost()
    {
        return 'POST' === $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Returns value from get and post data
     *
     * @param string $key
     * @param string $type
     * @param mixed  $default
     *
     * @return int|string
     */
    public static function get($key, $type = 'string', $default = null)
    {
        $getParams = array();
        foreach (explode('&', $_SERVER['QUERY_STRING']) as $param) {
            if (!strlen($param)) {
                continue;
            }
            list($name, $value) = explode('=', $param);
            $getParams[$name] = $value;
        }

        $value = array_key_exists($key, $getParams)?$getParams[$key]:(array_key_exists($key, $_POST)?$_POST[$key]:$default);

        switch ($type) {
            case 'int':
                return (int)$value;
            default:
                return trim(htmlspecialchars($value));
        }
    }

    /**
     * Returns current host
     *
     * @return string
     */
    public static function getHost()
    {
        //TODO: need to add supporting of HTTPS
        return 'http://'.$_SERVER['HTTP_HOST'];
    }

    /**
     * Returns current request method
     *
     * @return string
     */
    public static function method()
    {
        return self::isPost()?'POST':'GET';
    }
}