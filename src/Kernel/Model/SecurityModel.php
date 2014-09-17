<?php
/**
 * Security/User Model
 *
 * @package Kernel\Model
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace Kernel\Model;

use Kernel\Security\Token;

/**
 * Class SecurityModel
 *
 * @package Kernel\Model
 */
class SecurityModel extends AbstractModel
{
    /**
     * @var array Validation rules
     */
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

    /**
     * @return string
     */
    public function getTable()
    {
        return 'users';
    }

    /**
     * @inheritDoc
     */
    protected function _beforeInsert()
    {
        $this->_fields['salt']     = uniqid();
        $this->_fields['password'] = Token::cryptPassword($this->_fields['password'], $this->_fields['salt']);
        $this->_fields['name']     = 'User';

        $date                           = $this->_getCurrentDate();
        $this->_fields['created_at']    = $date->format('Y-m-d');
        $this->_fields['last_activity'] = $date->format('Y-m-d H:i:s');
    }

    /**
     * @inheritDoc
     */
    protected function _beforeUpdate()
    {
        $date = $this->_getCurrentDate();
        $this->set('last_activity', $date->format('Y-m-d H:i:s'));
    }

    /**
     * @return \DateTime
     */
    private function _getCurrentDate()
    {
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone(\Application::getConfig('timezone')));
        return $date;
    }
}