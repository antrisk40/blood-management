<?php

namespace App\Controllers\Blood;

use CodeIgniter\RESTful\ResourceController;
use App\Services\RequestService;

class BloodRequestController extends ResourceController
{
    protected $requestService;

    public function __construct()
    {
        $this->requestService = new RequestService();
    }

    public function create()
    {
        $rules = [
            'blood_sample_id' => 'required|numeric',
            'units_requested' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ], 400);
        }

        $data = $this->request->getJSON(true);
        $user = $this->request->user; // Set by AuthFilter

        try {
            // $user['profile_id'] contains the receiver_id
            $this->requestService->requestSample($user['profile_id'], $data['blood_sample_id'], $data['units_requested']);
            return $this->respond([
                'success' => true,
                'message' => 'Blood request submitted successfully',
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
