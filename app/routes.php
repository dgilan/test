<?php

return array(
    array(
        'pattern'    => '/',
        'controller' => 'CMS\\Controller\\DashboardController',
        'action'     => 'index'
    ),

    array(
        'pattern'    => '/signin',
        'controller' => 'Kernel\\Controller\\SecurityController',
        'action'     => 'signin'
    ),

    array(
        'pattern'    => '/login',
        'controller' => 'Kernel\\Controller\\SecurityController',
        'action'     => 'login'
    ),


    array(
        'pattern'       => '/blogs/{id}',
        'controller'    => 'CMS\\Controller\\DashboardController',
        'action'        => 'index',
        '_requirements' => array(
            'id' => '\d+'
        )

    )
);