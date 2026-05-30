<?php

namespace App\Repositories;

use App\Models\HospitalModel;

class HospitalRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new HospitalModel();
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
