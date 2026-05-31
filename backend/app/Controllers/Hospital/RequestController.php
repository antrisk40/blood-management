<?php

namespace App\Controllers\Hospital;

use CodeIgniter\RESTful\ResourceController;
use App\Services\RequestService;

class RequestController extends ResourceController
{
    protected $requestService = null;

    public function index()
    {
        $user = $this->request->user;
        
        try {
            $this->requestService = new RequestService();
            $data = $this->requestService->getHospitalRequests($user['profile_id']);
            return $this->respond([
                'success' => true,
                'message' => 'Requests retrieved successfully',
                'data'    => $data
            ]);
        } catch (\Throwable $e) {
            return $this->respond([
                'success' => false,
                'message' => 'Failed to retrieve requests'
            ], 500);
        }
    }

    public function deliver($id = null)
    {
        $user = $this->request->user;
        
        try {
            $this->requestService = new RequestService();
            $this->requestService->markAsDelivered($id, $user['profile_id']);
            return $this->respond([
                'success' => true,
                'message' => 'Request marked as delivered successfully',
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
