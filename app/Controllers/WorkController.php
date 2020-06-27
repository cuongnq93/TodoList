<?php

namespace App\Controllers;

use App\Models\Work;
use Core\Controller;

class WorkController extends Controller
{
    protected $workModel;

    public function __construct()
    {
        $this->workModel = new Work();
        parent::__construct();
    }

    public function indexAction()
    {
        $works = $this->workModel->getAll();
        return $this->apiResponse($works);
    }

    /**
     * @return false|string
     * @throws \Exception
     */
    public function newAction()
    {
        $this->assertPostOnly();
        $data = $this->request->getBody();

        if (strlen($data['work_name']) < 1) {
            return $this->apiError('Please enter work name');
        }

        if ($data['status'] == 0) {
            return $this->apiError('Please choose status');
        }

        $work = $this->workModel->insert($data);
        return $this->apiResponse($work);
    }

    /**
     * @return false|string
     * @throws \Exception
     */
    public function showAction()
    {
        $params = $this->request->getParams();
        return $this->apiResponse($this->workModel->find($params['id']));
    }

    /**
     * @return false|string
     * @throws \Exception
     */
    public function updateAction()
    {
        $this->assertPostOnly();
        $params = $this->request->getParams();
        $data = $this->request->getBody();

        return $this->apiResponse($this->workModel->update($params['id'], $data));
    }

    public function deleteAction()
    {
        $params = $this->request->getParams();

        $rowEffect = $this->workModel->delete($params['id']);

        return $this->apiResponse([
            'row_effect' => $rowEffect
        ]);
    }
}