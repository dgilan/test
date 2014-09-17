<?php
/**
 * Blog Post controller
 *
 * @package CMS\Controller
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace CMS\Controller;

use CMS\Model\Post;
use Kernel\Controller\AbstractController;
use Kernel\Exception\DatabaseException;
use Kernel\Exception\HttpNotFoundException;
use Kernel\Request\Request;
use Kernel\Security\Token;

/**
 * Class BlogController
 *
 * @package CMS\Controller
 */
class BlogController extends AbstractController
{
    /**
     * Returns home page with the latest blog posts
     *
     * @return string
     */
    public function indexAction()
    {
        $limit = Request::get('limit', 'int', 5);
        $page  = Request::get('page', 'int', 1);

        $model = new Post();
        $posts = $model->limit($limit)->skip($limit * ($page - 1))->join('users', 'name', 'author_id')
                       ->orderBy('updated_at', 'desc')->getList();
        $total = $model->getTotal();
        return $this->_renderView(
                    'index.html',
                        array('posts' => $posts, 'user' => $this->getUser(), 'limit' => $limit, 'page' => $page, 'total' => $total)
        );
    }

    /**
     * Returns and processes form for adding post
     *
     * @return string
     */
    public function addAction()
    {
        if (!$this->getUser()) {
            $this->redirect('/', 'Please, login first!');
        }

        $post   = new \stdClass();
        $errors = array();

        if (Request::isPost()) {
            $model = new Post();
            $date = new \DateTime();
            $date->setTimezone(new \DateTimeZone(\Application::getConfig('timezone')));
            $model->set('title', Request::get('title'))->set('content', Request::get('content'))->set('author_id', $this->getUser()->id)
                ->set('updated_at', $date->format('Y-m-d H:i:s'));
            if ($model->isValid()) {
                try{
                    $model->insert();
                    $this->redirect('/', 'The data has been saved successfully');
                } catch(DatabaseException $e){
                    array_push($errors, $e->getMessage());
                }
            } else {
                $errors = $model->getErrors();
                $post   = $model->getFieldsObject();
            }
        }

        return $this->_renderView('form.html', array('post' => $post, 'errors' => $errors, 'action' => '/posts/add'));
    }

    /**
     * Shows post. Form if it's user's post, and view if it's not
     *
     * @param int $id Post's id
     *
     * @return string
     * @throws \Kernel\Exception\HttpNotFoundException
     */
    public function showAction($id)
    {
        $model = new Post();
        if (!$post = $model->set('id', $id)->getItem()) {
            throw new HttpNotFoundException('Post not found!');
        };

        if ($this->getUser() && $this->getUser()->id === $post->author_id) {
            return $this->_renderView('form.html', array('post' => $post, 'errors' => array(), 'action' => '/posts/'.$id.'/edit'));
        } else {
            return $this->_renderView('show.html', array('post' => $post));
        }
    }

    /**
     * Updates post
     *
     * @param int $id
     *
     * @return string
     */
    public function editAction($id)
    {
        if (!$this->getUser()) {
            $this->redirect('/', 'Please, login first!');
        }

        $model = new Post();
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone(\Application::getConfig('timezone')));
        $model->set('title', Request::get('title'))->set('content', Request::get('content'))->set('id', $id)
            ->set('updated_at', $date->format('Y-m-d H:i:s'));
        if ($model->isValid()) {
            try{
                $model->update();
                $this->redirect('/', 'The data has been saved successfully');
            } catch(DatabaseException $e){
                array_push($errors, $e->getMessage());
            }
        } else {
            $post = $model->getFieldsObject();
            return $this->_renderView(
                        'form.html',
                            array('post' => $post, 'errors' => $model->getErrors(), 'action' => '/posts/'.$id.'/edit')
            );
        }
    }
} 