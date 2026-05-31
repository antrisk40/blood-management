<?php

namespace App\Controllers\Hospital;

use CodeIgniter\RESTful\ResourceController;
use App\Services\BloodService;
use App\Repositories\BloodRepository;

class InventoryController extends ResourceController
{
    protected $bloodService;
    protected $bloodRepo;

    public function __construct()
    {
        $this->bloodService = new BloodService();
        $this->bloodRepo = new BloodRepository();
    }

    public function index()
    {
        $user = $this->request->user;
        try {
            $data = $this->bloodService->getHospitalInventory($user['profile_id']);
            return $this->respond([
                'success' => true,
                'message' => 'Inventory retrieved successfully',
                'data'    => $data
            ]);
        } catch (\Throwable $e) {
            return $this->respond([
                'success' => false,
                'message' => 'Failed to retrieve inventory'
            ], 500);
        }
    }

    public function create()
    {
        $rules = [
            'blood_group'     => 'required',
            'units_available' => 'required|numeric|greater_than[0]',
            'expiry_date'     => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ], 400);
        }

        $data = $this->request->getJSON(true);
        $user = $this->request->user;

        try {
            $this->bloodService->addSample($user['profile_id'], $data);
            return $this->respond([
                'success' => true,
                'message' => 'Blood sample added successfully',
                'data'    => []
            ]);
        } catch (\Throwable $e) {
            return $this->respond([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function update($id = null)
    {
        // Simple update: just updating units or expiry
        $rules = [
            'units_available' => 'required|numeric|greater_than_equal_to[0]',
            'expiry_date'     => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ], 400);
        }

        $data = $this->request->getJSON(true);
        $user = $this->request->user;

        // Verify ownership
        $sample = $this->bloodRepo->findById($id);
        if (!$sample || $sample['hospital_id'] != $user['profile_id']) {
            return $this->respond(['success' => false, 'message' => 'Not found or unauthorized'], 404);
        }

        try {
            $this->bloodRepo->update($id, [
                'units_available' => $data['units_available'],
                'expiry_date'     => $data['expiry_date']
            ]);
            return $this->respond([
                'success' => true,
                'message' => 'Blood sample updated successfully',
                'data'    => []
            ]);
        } catch (\Throwable $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function delete($id = null)
    {
        $user = $this->request->user;

        try {
            $deleted = $this->bloodRepo->delete($id, $user['profile_id']);
            if (!$deleted) {
                return $this->respond(['success' => false, 'message' => 'Failed to delete or unauthorized'], 400);
            }
            return $this->respond([
                'success' => true,
                'message' => 'Blood sample deleted successfully',
                'data'    => []
            ]);
        } catch (\Throwable $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
