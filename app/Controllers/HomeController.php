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
        $viewData = [
            'type' => []
        ];

//        $workModel = new Work();
//        $test = $workModel->insert([
//           'work_name' => 'Cuong',
//           'status' => 1,
//           'start_at' => date('Y-m-d h:i:s', time()),
//           'end_at' => date('Y-m-d h:i:s', time() + 3600)
//        ]);

        return $this->view->render('home.index', $viewData);
    }
}