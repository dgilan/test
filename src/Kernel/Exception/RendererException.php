<?php
/**
 * Class RendererException. Throws when system doesn't find a layout for rendering
 *
 * @package Kernel\Exception
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace Kernel\Exception;

/**
 * Class RendererException
 *
 * @package Kernel\Exception
 */
class RendererException extends AbstractException
{
    public function __construct($message = "", \Exception $previous = null)
    {
        parent::__construct($message, 500, $previous);
    }
} 