<?php
/**
 * 404 Http not found error
 *
 * @package Kernel\Exception
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace Kernel\Exception;

/**
 * Class HttpNotFoundException
 *
 * @package Kernel\Exception
 */
class HttpNotFoundException extends AbstractException
{
    /**
     * Constructor
     *
     * @param string     $message
     * @param \Exception $previous
     */
    public function __construct($message = "", \Exception $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}