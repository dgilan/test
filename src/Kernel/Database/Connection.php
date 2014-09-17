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

    /**
     * Connects to MySQL instance with Config params
     *
     * @throws \Kernel\Exception\DatabaseException When connection is failed
     */
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

    /**
     * Executes MySQL Query
     *
     * @param string $query
     *
     * @throws \Kernel\Exception\DatabaseException When query is failed
     */
    public function execute($query)
    {
        if (!mysql_query($query, $this->_connection)) {
            throw new DatabaseException('MySQL query has not been executed: '.mysql_error($this->_connection));
        }
    }

    /**
     * Selects data from MySQL tables
     *
     * @param  string $query
     * @param bool    $single Checks what should be returned: single object or an array
     *
     * @return array|\stdClass
     * @throws \Kernel\Exception\DatabaseException When query is failed
     */
    public function select($query, $single = false)
    {
        if (!$result = mysql_query($query, $this->_connection)) {
            throw new DatabaseException('MySQL query has not been executed: '.mysql_error($this->_connection));
        }

        if ($single) {
            return mysql_fetch_object($result);
        } else {
            $list = array();
            if (mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_object($result)) {
                    array_push($list, $row);
                }
            }
            return $list;
        }
    }
}