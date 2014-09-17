<?php
/**
 * Post model
 *
 * @package CMS\Model
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace CMS\Model;

use Kernel\Model\AbstractModel;

/**
 * Class Post
 *
 * @package CMS\Model
 */
class Post extends AbstractModel
{
    /**
     * @var array
     */
    protected $_map = array(
        'title'   => array(
            'pattern' => '/^.{2,}$/',
            'message' => 'Title must contain at least 2 symbols'
        ),
        'content' => array(
            'pattern' => '/^.{2,}$/',
            'message' => 'Content must contain at least 2 symbols'
        )
    );

    /**
     * Returns table name model associated with
     *
     * @return string
     */
    public function getTable()
    {
        return 'posts';
    }
}