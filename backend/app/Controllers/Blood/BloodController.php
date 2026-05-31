<?php

namespace App\Controllers\Blood;

use CodeIgniter\RESTful\ResourceController;
use App\Services\BloodService;

class BloodController extends ResourceController
{
    protected $bloodService = null;

    public function index()
    {
        try {
            $this->bloodService = new BloodService();
            $data = $this->bloodService->getPublicList();
            return $this->respond([
                'success' => true,
                'message' => 'Available blood samples retrieved successfully',
                'data'    => $data
            ]);
        } catch (\Throwable $e) {
            return $this->respond([
                'success' => false,
                'message' => 'Failed to retrieve available blood samples'
            ], 500);
        }
    }
}
