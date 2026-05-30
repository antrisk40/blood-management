<?php

namespace App\Repositories;

use App\Models\UserModel;

class UserRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function create(array $data)
    {
        return $this->model->insert($data, true);
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByUsername(string $username)
    {
        return $this->model->where('username', $username)->first();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }
}
