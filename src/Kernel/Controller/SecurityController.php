<?php
/**
 * Controller that is responsible for user's sign in. login and logout
 *
 * @package Kernel\Controller
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace Kernel\Controller;

use Kernel\Exception\DatabaseException;
use Kernel\Model\SecurityModel;
use Kernel\Request\Request;
use Kernel\Security\Token;

/**
 * Class SecurityController
 *
 * @package Kernel\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * Renders registration form and registers user in the system after form submitting
     *
     * @return string
     */
    public function signinAction()
    {
        $this->_redirectIfLoggedIn();
        $errors = array();

        if (Request::isPost()) {
            $model = new SecurityModel();
            $model->set('email', Request::get('email'))->set('password', Request::get('password'));

            if ($model->isValid()) {
                try{
                    $model->insert();
                    $this->redirect('/login');
                } catch(DatabaseException $e){
                    $errors['email'] = 'Email already exists!';
                }
            } else {
                $errors = $model->getErrors();
            }
        }

        return $this->_renderView('signin.html', array('errors' => $errors));
    }

    /**
     * Renders login form and authenticates user after form submitting
     *
     * @return string
     */
    public function loginAction()
    {
        $this->_redirectIfLoggedIn();
        $errors = array();

        if (Request::isPost()) {
            $model = new SecurityModel();
            if ($item = $model->set('email', Request::get('email'))->getItem()) {
                if (0 === strcmp(Token::cryptPassword(Request::get('password'), $item->salt), $item->password)) {
                    Token::setUser($item);
                    $this->redirect('/');
                }
            }
            array_push($errors, 'Invalid username or password');
        }

        return $this->_renderView('login.html', array('errors' => $errors));
    }

    /**
     * Logouts user from the system
     */
    public function logoutAction()
    {
        Token::clear();
        $this->redirect('/');
    }

    /**
     * Checks is user already authenicated and redirects to dashboard if it is
     */
    private function _redirectIfLoggedIn()
    {
        if (Token::getUser()) {
            $this->redirect('/');
        }
    }
}