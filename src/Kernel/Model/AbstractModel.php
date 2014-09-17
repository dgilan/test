<?php
/**
 * Created by PhpStorm.
 * User: dgilan
 * Date: 9/16/14
 * Time: 8:36 PM
 */

namespace Kernel\Model;

use Kernel\Database\Connection;

abstract class AbstractModel
{

    protected $_item;

    protected $_fields = array();

    protected $_map = array();

    protected $_errors = array();

    abstract public function getTable();


    public function getList()
    {
    }


    public function getItem()
    {
        if (is_null($this->_item)) {
            $where = '';

            foreach ($this->_fields as $key => $value) {
                $where .= strlen($where)?" AND $key='$value'":"$key='$value'";
            }

            if (strlen($where)) {
                $where = " WHERE ($where)";
            }

            $query = 'SELECT * from '.$this->getTable()." $where LIMIT 1";

            $this->_item = Connection::getInstance()->select($query, true);
        }
        return $this->_item;
    }

    public function isValid()
    {
        $result = true;
        foreach ($this->_map as $field => $condition) {

            if (empty($this->_fields[$field]) || !preg_match($condition['pattern'], $this->_fields[$field], $match)) {
                $this->_errors[$field] = $condition['message'];
                $result                = false;
            }
        }
        return $result;
    }


    public function getErrors()
    {
        return $this->_errors;
    }

    public function set($param, $value)
    {
        $this->_fields[$param] = $value;
        return $this;
    }

    protected function _beforeInsert()
    {
    }

    public function insert()
    {
        $this->_beforeInsert();
        $keys   = implode(',', array_keys($this->_fields));
        $values = array();
        foreach ($this->_fields as $value) {
            array_push($values, "'$value'");
        }

        $values = implode(',', $values);
        $query  = 'INSERT INTO '.$this->getTable()." ($keys) VALUES ($values)";
        Connection::getInstance()->execute($query);
    }
}