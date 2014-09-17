<?php
/**
 * Controller Interface. All Controllers used in routes must implement this interface
 *
 * @package Kernel\Controller
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace Kernel\Controller;

/**
 * Interface ControllerInterface
 *
 * @package Kernel\Controller
 */
interface ControllerInterface
{
    /**
     * Redirects to the other url
     *
     * @param string $url
     * @param string $flushMessage Message that will be shown on the redirected page
     */
    public function redirect($url, $flushMessage = null);

    /**
     * Returns current user's info if it's authenticated now
     *
     * @return \stdClass|null
     */
    public function getUser();
} 