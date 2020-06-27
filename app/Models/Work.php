<?php

namespace App\Models;

use Core\Model;

class Work extends Model
{
    protected $table = 'works';

    public function getAll()
    {
        $query = $this->exec('SELECT * FROM '.$this->table);

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function insert($data)
    {
        $sql = $this->buildQueryInsert($this->table, $data);
        $this->exec($sql);

        $lastId = $this->lastInsertId();

        return $this->find($lastId);
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function update($id, $data)
    {
        $sql = $this->buildQueryUpdate($this->table, $data, 'id = :id');
        $this->exec($sql, [
            ':id' => $id
        ]);

        return $this->find($id);
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function find($id)
    {
        $query = $this->exec('SELECT * FROM ' . $this->table . ' WHERE `id` = :id', [
            ':id' => $id
        ]);
        $data = $query->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            throw new \Exception('Resource not found', 404);
        }

        return $data;
    }

    public function delete($id)
    {
        $query = $this->exec('DELETE FROM `' . $this->table . '` WHERE id = :id', [
            ':id' => $id
        ]);

        return $query->rowCount();
    }
}