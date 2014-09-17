<?php
/**
 * Abstract Controller
 *
 * @package Kernel\Controller
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace Kernel\Controller;

use Kernel\Helper\Renderer;
use Kernel\Request\Request;
use Kernel\Security\Token;

/**
 * Class AbstractController
 *
 * @package Kernel\Controller
 */
abstract class AbstractController implements ControllerInterface
{
    /**
     * @inheritDoc
     */
    public function redirect($url, $flushMessage = null)
    {
        \Application::redirect($url, $flushMessage);
    }

    /**
     * @inheritDoc
     */
    public function getUser()
    {
        return Token::getUser();
    }

    /**
     * Renders view
     *
     * @param string $view
     * @param array  $data Data to be assigned to the view
     *
     * @return string
     */
    protected function _renderView($view, $data = array())
    {
        $render = new Renderer($this->_getViewPath($view));
        $render->assign($data);
        return $render->render();
    }

    /**
     * Returns full path to the view
     *
     * @param string $view
     *
     * @return string
     */
    private function _getViewPath($view)
    {
        $paths     = explode('\\', get_class($this));
        $classname = array_pop($paths);
        array_pop($paths); // remove Controller folder
        return APP_PATH.'/../src/'.implode('/', $paths).'/views/'.str_replace('Controller', '', $classname).'/'.$view.'.php';
    }
}