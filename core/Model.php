<?php

namespace Core;

use PDO;
use PDOException;

abstract class Model
{
    protected $pdo;
    protected $type      = 'mysql';
    protected $options   = [];
    protected $debugMode = false;
    protected $statement;
    protected $config;

    public function __construct()
    {
        $this->config = new Config();

        try {
            $dsn = sprintf('%s:host=%s;port=%d;dbname=%s;charset=%s', $this->config->dbConfig('driver'),
                $this->config->dbConfig('host'),
                $this->config->dbConfig('port'),
                $this->config->dbConfig('name'),
                $this->config->dbConfig('charset')
            );
            $this->pdo = new PDO(
                $dsn,
                $this->config->dbConfig('user'),
                $this->config->dbConfig('pass'),
                $this->options
            );
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    public function exec($query, $map = [])
    {
        $this->statement = null;

        if ($this->debugMode) {
            echo $this->generate($query, $map);

            $this->debugMode = false;

            return false;
        }

        $statement = $this->pdo->prepare($query);

        if (!$statement) {
            $this->errorInfo = $this->pdo->errorInfo();
            $this->statement = null;

            return false;
        }

        $this->statement = $statement;

        foreach ($map as $key => $value) {
            $statement->bindValue($key, $value);
        }

        $execute = $statement->execute();

        $this->errorInfo = $statement->errorInfo();

        if (!$execute) {
            $this->statement = null;
        }

        return $statement;
    }

    public function quote($string)
    {
        return $this->pdo->quote($string);
    }

    public function buildQueryInsert($table, $data)
    {
        $colums = array_keys($data);
        $columSqlRaw = implode('`, `', $colums);
        $valueSqlRaw = implode('\', \'', $data);

        $sql = 'INSERT INTO `%s` (`%s`) VALUES (\'%s\')';
        return sprintf($sql, $table, $columSqlRaw, $valueSqlRaw);
    }

    public function buildQueryUpdate($table, $data, $where)
    {
        foreach ($data as $column => $value) {
            $dataUpdate[] = sprintf('`%s`=\'%s\'', $column, $value);
        }
        $sql = 'UPDATE `%s` SET %s WHERE %s';
        return sprintf($sql, $table, implode(', ', $dataUpdate), $where);
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    protected function generate($query, $map)
    {
        $identifier = [
            'mysql' => '`$1`'
        ];

        $query = preg_replace(
            '/"([a-zA-Z0-9_]+)"/i',
            isset($identifier[$this->type]) ? $identifier[$this->type] : '"$1"',
            $query
        );

        foreach ($map as $key => $value) {
            if ($value[1] === PDO::PARAM_STR) {
                $replace = $this->quote($value[0]);
            } elseif ($value[1] === PDO::PARAM_NULL) {
                $replace = 'NULL';
            } elseif ($value[1] === PDO::PARAM_LOB) {
                $replace = '{LOB_DATA}';
            } else {
                $replace = $value[0];
            }

            $query = str_replace($key, $replace, $query);
        }

        return $query;
    }
}