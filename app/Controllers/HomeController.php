<?php

namespace App\Controllers;

use App\Models\Work;
use Core\Controller;

class HomeController extends Controller
{
    /**
     * @throws \Exception
     */
    public function indexAction()
    {
        return $this->view->render('home.index');
    }
}