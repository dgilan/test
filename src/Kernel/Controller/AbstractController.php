<?php
/**
 * Created by PhpStorm.
 * User: dgilan
 * Date: 9/15/14
 * Time: 9:38 PM
 */

namespace Kernel\Controller;

use Kernel\Helper\Renderer;

class AbstractController implements ControllerInterface
{
    protected function _renderView($view, $data = array())
    {
        $render = new Renderer($this->_getViewPath($view));
        $render->assign($data);
        return $render->render();
    }

    private function _getViewPath($view)
    {
        $paths     = explode('\\', get_class($this));
        $classname = array_pop($paths);
        array_pop($paths); // remove Controller folder
        return APP_PATH.'/../src/'.implode('/', $paths).'/views/'.str_replace('Controller', '', $classname).'/'.$view.'.php';
    }
}