<?php

namespace App\Controllers\Auth;

use CodeIgniter\RESTful\ResourceController;
use App\Services\AuthService;

class RegisterController extends ResourceController
{
    protected $authService = null;

    public function hospital()
    {
        $rules = [
            'hospital_name' => 'required|min_length[3]',
            'email'         => 'required|valid_email',
            'username'      => 'required|min_length[3]',
            'password'      => 'required|min_length[6]',
            'address'       => 'required',
            'phone'         => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ], 400);
        }

        try {
            $this->authService = new AuthService();
            $this->authService->registerHospital($this->request->getJSON(true));
            return $this->respond([
                'success' => true,
                'message' => 'Hospital registered successfully',
                'data'    => []
            ]);
        } catch (\Throwable $e) {
            return $this->respond([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function receiver()
    {
        $rules = [
            'full_name'   => 'required|min_length[3]',
            'email'       => 'required|valid_email',
            'username'    => 'required|min_length[3]',
            'password'    => 'required|min_length[6]',
            'blood_group' => 'required',
            'phone'       => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ], 400);
        }

        try {
            $this->authService = new AuthService();
            $this->authService->registerReceiver($this->request->getJSON(true));
            return $this->respond([
                'success' => true,
                'message' => 'Receiver registered successfully',
                'data'    => []
            ]);
        } catch (\Throwable $e) {
            return $this->respond([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
