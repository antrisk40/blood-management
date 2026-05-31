<?php

namespace App\Controllers\Blood;

use CodeIgniter\RESTful\ResourceController;
use App\Services\BloodService;

class BloodController extends ResourceController
{
    protected $bloodService;

    public function __construct()
    {
        $this->bloodService = new BloodService();
    }

    public function index()
    {
        try {
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
