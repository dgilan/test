<?php
/**
 * Database Exception Class
 *
 * @package Kernel\Exception
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace Kernel\Exception;

/**
 * Class DatabaseException
 *
 * @package Kernel\Exception
 */
class DatabaseException extends AbstractException
{
    /**
     * Constructor
     *
     * @param string     $message
     * @param \Exception $previous
     */
    public function __construct($message = "", \Exception $previous = null)
    {
        parent::__construct($message, 500, $previous);
    }
}