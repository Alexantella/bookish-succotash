<?php

namespace Api;

use Models\Orders;

class OrderController
{
    private $entity;

    private $request = [];

    private $required_fields = [
        'phone',
        'from_address',
        'to_address',
    ];

    private $offset = 20;

    public function __construct()
    {
        $this->entity = new Orders();
        foreach ($_REQUEST as $key => $val) {
            $this->request[$key] = '"' . trim(htmlspecialchars($val)) . '"';
        }

    }

    public function index()
    {
        return $this->entity->getData(array_merge(
            $this->request,
            [
                'offset' => $this->offset
            ]));
    }

    public function view($params)
    {
        return $this->entity->getData(['id' => intval($params[0])]);
    }

    public function create()
    {
        if(!$this->validate()) {
            return false;
        }

        $result = $this->entity->insertData(array_merge(
            $this->request,
            ['status' => '"new"']
        ));

        if($result && is_bool($result)) {
            return 'Ok';
        } else {
            return 'Something goes wrong';
        }
    }

    public function destroy($params)
    {
        $result = $this->entity->updateData(['status' => '"canceled"'], 'id = ' . intval($params[0]));

        if($result && is_bool($result)) {
            return 'Ok';
        } else {
            return 'Something goes wrong';
        }
    }

    private function validate()
    {
        foreach ($this->required_fields as $required) {
            if(!isset($this->request[$required])) {
                return false;
            }
        }

        return true;
    }
}
