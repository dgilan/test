<?php
/**
 * Routes config
 *
 * @author Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

return array(
    'home'           => array(
        'pattern'    => '/',
        'controller' => 'CMS\\Controller\\BlogController',
        'action'     => 'index'
    ),
    'signin'         => array(
        'pattern'    => '/signin',
        'controller' => 'Kernel\\Controller\\SecurityController',
        'action'     => 'signin'
    ),
    'login'          => array(
        'pattern'    => '/login',
        'controller' => 'Kernel\\Controller\\SecurityController',
        'action'     => 'login'
    ),
    'logout'         => array(
        'pattern'    => '/logout',
        'controller' => 'Kernel\\Controller\\SecurityController',
        'action'     => 'logout'
    ),
    'update_profile' => array(
        'pattern'       => '/profile',
        'controller'    => 'CMS\\Controller\\ProfileController',
        'action'        => 'update',
        '_requirements' => array(
            '_method' => 'POST'
        )
    ),
    'profile'        => array(
        'pattern'    => '/profile',
        'controller' => 'CMS\\Controller\\ProfileController',
        'action'     => 'get'
    ),
    'add_post'       => array(
        'pattern'    => '/posts/add',
        'controller' => 'CMS\\Controller\\BlogController',
        'action'     => 'add'
    ),
    'show_post'      => array(
        'pattern'       => '/posts/{id}',
        'controller'    => 'CMS\\Controller\\BlogController',
        'action'        => 'show',
        '_requirements' => array(
            'id' => '\d+'
        )

    ),
    'edit_post'      => array(
        'pattern'       => '/posts/{id}/edit',
        'controller'    => 'CMS\\Controller\\BlogController',
        'action'        => 'edit',
        '_requirements' => array(
            'id'      => '\d+',
            '_method' => 'POST'
        )

    )
);