<?php

namespace DB;

class Database {

    private $host = 'localhost';

    private $port = '';

    private $username = 'u4945_als';

    private $password = 'Alex25101969';

    private $database = 'u4945_main';

    private $connect;

    public function __construct()
    {
        $this->connect = new \Mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        $this->connect->set_charset('utf8');
    }

    public function selectData($table, $where, $limit)
    {
        $tail = '';

        if (!empty($where)) {
            $tail .= ' WHERE ' . $where;
        }

        if ($limit) {
            $tail .= ' LIMIT ' . implode(',', $limit);
        }

        $query = $this->connect->query('SELECT * FROM ' . $table . $tail);

        return $query->fetch_all(MYSQLI_ASSOC);
    }

    public function insertData ($table, $data)
    {
        try {
            $this->connect->query(
                "INSERT INTO "
                . $table
                . "("
                . implode(',', array_keys($data))
                . ") VALUES ("
                . implode(',', array_values($data))
                . ")"
            );

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteData ($table, $where) {
        $tail = '';

        if (!empty($where)) {
            $tail .= ' WHERE ' . $where;
        }

        try {
            $this->connect->query('DELETE FROM ' . $table . $tail);
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateData ($table, $fields, $where) {
        if(!$fields) {
            return false;
        }

        $set = [];
        $tail = '';

        foreach ($fields as $key => $field) {
            $set[] = $key . '=' . $field;
        }

        if (!empty($where)) {
            $tail .= ' WHERE ' . $where;
        }

        try {
            $this->connect->query('UPDATE ' . $table . ' SET ' . implode(',', $set) . $tail);
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
