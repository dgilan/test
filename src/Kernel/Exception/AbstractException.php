<?php
/**
 * Abstract Application Exception
 *
 * @package Kernel\Exception
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace Kernel\Exception;

use Exception;

/**
 * Class AbstractException
 *
 * @package Kernel\Exception
 */
class AbstractException extends \Exception
{
    /**
     * @inheritDoc
     */
    public function __construct($message = "", $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}