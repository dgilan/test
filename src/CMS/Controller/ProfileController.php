<?php
/**
 * User Profile controller
 *
 * @package CMS\Controller
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace CMS\Controller;

use Kernel\Controller\AbstractController;
use Kernel\Exception\DatabaseException;
use Kernel\Model\SecurityModel;
use Kernel\Request\Request;

/**
 * Class ProfileController
 *
 * @package CMS\Controller
 */
class ProfileController extends AbstractController
{
    /**
     * Returns profile form
     *
     * @return string
     */
    public function getAction()
    {
        if (!$this->getUser()) {
            $this->redirect('/login', 'Please, login first!');
        }
        return $this->_renderView('form.html', array('user' => $this->getUser()));
    }

    /**
     * Updates user's profile
     *
     * @return string
     */
    public function updateAction()
    {
        if (!$this->getUser()) {
            $this->redirect('/login', 'Please, login first!');
        }
        $errors = array();

        $model = new SecurityModel();
        $model->setItem($this->getUser());
        $model->set('email', Request::get('email'))->set('name', Request::get('name'));

        if ($model->isValid()) {
            try{
                $model->update();
                $this->redirect('/', 'Data has been saved successfully');
            } catch(DatabaseException $e){
                $errors['email'] = 'Email already exists!';
            }
        } else {
            $errors = $model->getErrors();
        }

        return $this->_renderView('form.html', array('user' => $this->getUser(), 'errors' => $errors));
    }
}