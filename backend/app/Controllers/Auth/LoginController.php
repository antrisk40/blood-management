<?php

namespace App\Controllers\Auth;

use CodeIgniter\RESTful\ResourceController;
use App\Services\AuthService;

class LoginController extends ResourceController
{
    protected $authService = null;

    public function login()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ], 400);
        }

        $data = $this->request->getJSON(true);

        try {
            $this->authService = new AuthService();
            $result = $this->authService->login($data['username'], $data['password']);
            return $this->respond([
                'success' => true,
                'message' => 'Login successful',
                'data'    => $result
            ]);
        } catch (\Throwable $e) {
            return $this->respond([
                'success' => false,
                'message' => $e->getMessage()
            ], 401);
        }
    }
}
