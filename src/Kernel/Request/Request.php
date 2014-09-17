<?php
/**
 * Created by PhpStorm.
 * User: dgilan
 * Date: 9/15/14
 * Time: 11:30 PM
 */

namespace Kernel\Request;

class Request
{

    public static function  getUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function isPost()
    {
        return 'POST' === $_SERVER['REQUEST_METHOD'];
    }

    public static function get($key, $type = 'string', $default = null)
    {
        $value = array_key_exists($key, $_POST)?$_POST[$key]:$default;

        switch ($type) {
            case 'int':
                return (int)$value;
            default:
                return htmlspecialchars($value);
        }
    }
}