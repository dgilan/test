<?php
/**
 * Class for MySql Connection
 *
 * @package Kernel\Database
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace Kernel\Database;

use Kernel\Exception\DatabaseException;

/**
 * Class Connection
 *
 * @package Kernel\Database
 */
class Connection
{
    /**
     * @var null
     */
    private static $_instance = null;

    /**
     * MySql connection resource
     *
     * @var resource
     */
    private $_connection;

    /**
     * Returns Router instance
     *
     * @return self
     */
    public static function getInstance()
    {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
            self::$_instance->connect();
        }

        return self::$_instance;
    }

    /**
     * Constructor. Prevents from direct construct
     */
    private final function __construct()
    {
    }

    /**
     * Prevents router from cloning
     */
    private final function __clone()
    {
    }

    private function connect()
    {
        $config = \Application::getConfig();
        if (!$this->_connection = mysql_connect($config['db_host'], $config['db_user'], $config['db_pass'])) {
            throw new DatabaseException('Could not connect to MySQL server');
        }
        if (!mysql_select_db($config['db_name'])) {
            throw new DatabaseException(sprintf('Database "%s" was not found', $config['db_name']));
        }
    }

    /**
     * Closes connection to DB
     */
    public function disconnect()
    {
        if ($this->_connection) {
            mysql_close($this->_connection);
        }
    }

    public function execute($query)
    {
        if (!mysql_query($query, $this->_connection)) {
            throw new DatabaseException('MySQL query has not been executed: '.mysql_error($this->_connection));
        }
    }

    public function select($query, $single = false)
    {
        if (!$result  = mysql_query($query, $this->_connection)) {
            throw new DatabaseException('MySQL query has not been executed: '.mysql_error($this->_connection));
        }

        return $single?mysql_fetch_object($result):mysql_fetch_assoc($result);
    }
}