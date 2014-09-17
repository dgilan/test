<?php
/**
 * Created by PhpStorm.
 * User: dgilan
 * Date: 9/16/14
 * Time: 8:38 PM
 */

namespace Kernel\Model;

class SecurityModel extends AbstractModel
{

    protected $_map = array(
        'email'    => array(
            'pattern' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/',
            'message' => 'Invalid email'
        ),
        'password' => array(
            'pattern' => '/^.{2,}$/',
            'message' => 'Password must contain at least 2 symbols'
        )

    );

    protected function _beforeInsert()
    {
        $this->_fields['salt']     = uniqid();
        $this->_fields['password'] = crypt($this->_fields['password'], $this->_fields['salt']);
        $this->_fields['name']     = 'User';

        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone(\Application::getConfig('timezone')));
        $this->_fields['created_at']    = $date->format('Y-m-d');
        $this->_fields['last_activity'] = $date->format('Y-m-d H:i:s');
    }


    public function getTable()
    {
        return 'users';
    }
}