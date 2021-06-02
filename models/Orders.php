<?php

namespace Models;

use DB\Database;

class Orders
{
    private $table = 'orders';

    private $fields = [
        'id',
        'phone',
        'from_address',
        'to_address',
        'status',
        'created_at',
        'status_changed',
    ];

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getData($params)
    {
        $limit = [];
        $where = [];

        if(isset($params['page']) && isset($params['offset'])){
            $limit = [
                $params['page'] * $params['offset'],
                $params['offset'],
            ];
        }

        $data = array_intersect_key($params, array_flip($this->fields));

        if ($data){
            foreach ($data as $field => $val){
                $where[] = $field . ' = ' . $val;
            }
        }

        return $this->db->selectData($this->table, implode('AND', $where), $limit);
    }

    public function insertData($params){
        $data = array_intersect_key($params, array_flip($this->fields));
        return $this->db->insertData($this->table, $data);
    }

    public function deleteData($params){
        $data = array_intersect_key($params, array_flip($this->fields));
        return $this->db->deleteData($this->table, $data);
    }

    public function updateData($fields, $conditions){
        $fields = array_intersect_key($fields, array_flip($this->fields));

        return $this->db->updateData($this->table, $fields, $conditions);
    }
}
