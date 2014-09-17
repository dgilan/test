<?php
/**
 * Created by PhpStorm.
 * User: dgilan
 * Date: 9/16/14
 * Time: 8:06 PM
 */

namespace Kernel\Controller;

use Kernel\Model\SecurityModel;
use Kernel\Request\Request;

class SecurityController extends AbstractController
{

    public function signinAction()
    {

        //TODO: if i'm authentificated => to dashboard
        if (Request::isPost()) {

            $model = new SecurityModel();

            $model->set('email', Request::get('email'))->set('password', Request::get('password'));
            if ($model->isValid()) {
                $model->insert();
            } else {
                return $this->_renderView('signin.html', array('errors' => $model->getErrors()));
            }

            $model->getItem();
        }

        return $this->_renderView('signin.html');
    }

    public function loginAction()
    {

        if (Request::isPost()) {

            $model = new SecurityModel();
            if ($item = $model->set('email', Request::get('email'))->getItem()){

                if (0 === strcmp(crypt(Request::get('password'), $item->salt), $item->password)){
                    //pass OK

                }
            }

            return $this->_renderView('login.html', array('errors'=>'Invalid username or password'));

        }

        return $this->_renderView('login.html');
    }
}