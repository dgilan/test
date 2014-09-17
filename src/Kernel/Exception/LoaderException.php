<?php
/**
 * Class LoaderException. Throws when system doesn't find a php class
 *
 * @package Kernel\Exception
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace Kernel\Exception;

/**
 * Class LoaderException
 *
 * @package Kernel\Exception
 */
class LoaderException extends AbstractException
{
    public function __construct($message = "", \Exception $previous = null)
    {
        parent::__construct($message, 500, $previous);
    }
}