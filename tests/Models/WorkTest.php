<?php

namespace Tests\Model;

use App\Models\Work;
use PHPUnit\Framework\TestCase;

class WorkTest extends TestCase
{
    public function testGetAll()
    {
        $workModel = new Work();
        $this->assertIsArray($workModel->getAll());

    }

    public function testFind()
    {
        $workModel = new Work();
        $this->assertIsArray($workModel->find(17));
    }

    public function testInsert()
    {
        $workModel = new Work();
        $this->assertIsArray($workModel->insert([
            'work_name' => 'Event From Test',
            'status'    => 1,
            'start_at' => date('Y-m-d H:i:s'),
            'end_at' => date('Y-m-d H:i:s', time() + 3600),
        ]));
    }

    public function testDelete()
    {
        $workModel = new Work();
        $this->assertIsNumeric($workModel->delete(17));
    }

}