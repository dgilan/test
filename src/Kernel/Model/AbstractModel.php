<?php
/**
 * Abstract Model
 *
 * @package Kernel\Model
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace Kernel\Model;

use Kernel\Database\Connection;

/**
 * Class AbstractModel
 *
 * @package Kernel\Model
 */
abstract class AbstractModel
{
    /**
     * @var \stdClass Current Item object
     */
    protected $_item;

    /**
     * @var array Field's values for filtering, inserting or updating
     */
    protected $_fields = array();

    /**
     * @var array Validation rules
     */
    protected $_map = array();

    /**
     * @var array Model Errors
     */
    protected $_errors = array();

    /**
     * @var int Limit value for getList queries
     */
    protected $_limit;

    /**
     * @var int Skip value for getList queries
     */
    protected $_skip = 0;

    /**
     * @var array Join properties
     */
    protected $_join;

    /**
     * @var array Order properties
     */
    protected $_orderBy;

    /**
     * Returns table name model associated with
     *
     * @return string
     */
    abstract public function getTable();

    /**
     * Returns the list of items
     *
     * @return array
     */
    public function getList()
    {
        if (!is_null($this->_join)) {
            $query = 'SELECT t.*,j.'.$this->_join['field'].' FROM '.$this->getTable().' as t';
            $query .= ' JOIN '.$this->_join['table'].' j ON j.id=t.'.$this->_join['on'];
        } else {
            $query = 'SELECT * FROM '.$this->getTable().' t';
        }

        if ($this->_orderBy) {
            $query .= ' ORDER BY t.'.$this->_orderBy[0].' '.strtoupper($this->_orderBy[1]);
        }

        if ($this->_limit) {
            $query .= " LIMIT $this->_skip, $this->_limit";
        }

        return Connection::getInstance()->select($query);
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        if (!is_null($this->_join)) {
            $query = 'SELECT COUNT(t.id) as total FROM '.$this->getTable().' as t';
            $query .= ' JOIN '.$this->_join['table'].' j ON j.id=t.'.$this->_join['on'];
        } else {
            $query = 'SELECT * FROM '.$this->getTable().' t';
        }

        return Connection::getInstance()->select($query, true)->total;
    }

    /**
     * Returns current item or null if it's not found
     *
     * @return \stdClass|null
     */
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

    /**
     * Sets current item
     *
     * @param \stdClass $item
     */
    public function setItem($item)
    {
        $this->_item = $item;
    }

    /**
     * Checks are all fields in {$this->_fields} are set properly according to {$this->_maps} rules
     *
     * @return bool
     */
    public function isValid()
    {
        $result = true;
        foreach ($this->_map as $field => $condition) {
            if (!isset($this->_fields[$field]) && !is_null($this->_item)) {
                //it's update operation
                continue;
            }

            if (empty($this->_fields[$field]) || !preg_match($condition['pattern'], $this->_fields[$field], $match)) {
                $this->_errors[$field] = $condition['message'];
                $result                = false;
            }
        }
        return $result;
    }

    /**
     * Returns Model errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Inserts new item to the table
     */
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

    /**
     * Updates item
     */
    public function update()
    {
        $this->_beforeUpdate();

        $id = isset($this->_fields['id'])?$this->_fields['id']:$this->getItem()->id;
        unset($this->_fields['id']);
        $values = array();
        foreach ($this->_fields as $key => $value) {
            array_push($values, "$key='$value'");
        }

        $set = implode(',', $values);

        $query = 'UPDATE '.$this->getTable()." SET $set WHERE id='$id'";
        Connection::getInstance()->execute($query);
    }

    /**
     * Sets value to the fields
     *
     * @param string $param Name
     * @param mixed  $value Value
     *
     * @return $this
     */
    public function set($param, $value)
    {
        $this->_fields[$param] = $value;
        return $this;
    }

    /**
     * Sets limit value for getList
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit($limit)
    {
        $this->_limit = $limit;
        return $this;
    }

    /**
     * Sets skip value for getList
     *
     * @param int $skip
     *
     * @return $this
     */
    public function skip($skip)
    {
        $this->_skip = $skip;
        return $this;
    }

    /**
     * Sets Join attributes
     *
     * @param string $table Table to join
     * @param string $field Joined filed
     * @param string $on    foreign key
     *
     * @return $this
     */
    public function join($table, $field, $on)
    {
        //TODO: of cause this is not abstract but I don't have time to create ORM
        $this->_join = array('table' => $table, 'field' => $field, 'on' => $on);
        return $this;
    }

    /**
     * Sets ordering
     *
     * @param string $field
     * @param string $direction
     *
     * @return $this
     */
    public function orderBy($field, $direction = 'ASC')
    {
        $this->_orderBy = array($field, $direction);
        return $this;
    }

    /**
     * Returns model fields as object
     *
     * @return object
     */
    public function getFieldsObject()
    {
        return (object)$this->_fields;
    }

    /**
     * Called before insert operation
     */
    protected function _beforeInsert()
    {
    }

    /**
     * Called before update operation
     */
    protected function _beforeUpdate()
    {
    }
}