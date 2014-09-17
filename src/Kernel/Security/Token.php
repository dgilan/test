<?php
/**
 * Class Token. Uses for managing session and user authentication
 *
 * @package Kernel\Security
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace Kernel\Security;

use Kernel\Model\SecurityModel;

/**
 * Class Token
 *
 * @package Kernel\Security
 */
class Token
{
    /**
     * @var \stdClass|null User Instance
     */
    private static $_user;

    /**
     * Initializes session, checks is user's info saves in session and gets it from db if it is
     */
    public static function init()
    {
        if (!session_id()) {
            session_start();
        }

        if ($id = self::get('username')) {
            $model = new SecurityModel();
            if ($item = $model->set('id', $id)->getItem()) {
                $model->update();
                self::$_user = $item;
            }
        }
    }

    /**
     * Returns current user or null
     *
     * @return \stdClass|null
     */
    public static function getUser()
    {
        return self::$_user;
    }

    /**
     * Saves in session user object
     *
     * @param \stdClass|null $user
     */
    public static function setUser($user)
    {
        self::$_user          = $user;
        $_SESSION['username'] = is_null($user)?null:$user->id;
    }

    /**
     * Crypts user's password with a salt
     *
     * @param string $password
     * @param string $salt
     *
     * @return string
     */
    public static function cryptPassword($password, $salt = null)
    {
        return md5(crypt($password, $salt));
    }

    /**
     * Sets param to the session
     *
     * @param string $key
     * @param string $value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Returns value from the session
     *
     * @param string $key
     *
     * @return null|string
     */
    public static function get($key)
    {
        return !empty($_SESSION[$key])?$_SESSION[$key]:null;
    }

    /**
     * Clears session from user's data
     */
    public function clear()
    {
        self::setUser(null);
    }
}