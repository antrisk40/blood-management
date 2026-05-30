<?php

namespace App\Repositories;

use App\Models\ReceiverModel;

class ReceiverRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new ReceiverModel();
    }

    public function create(array $data)
    {
        return $this->model->insert($data, true);
    }

    public function findByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)->first();
    }
}
