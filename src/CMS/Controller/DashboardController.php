<?php
/**
 * Created by PhpStorm.
 * User: dgilan
 * Date: 9/15/14
 * Time: 10:39 PM
 */

namespace CMS\Controller;

use Kernel\Controller\AbstractController;

class DashboardController extends AbstractController
{
    public function indexAction($id)
    {
        return 'huy'.$id;
    }
} 