<?php

namespace App\Services;

use App\Repositories\BloodRepository;
use App\Models\BloodGroupModel;

class BloodService
{
    protected $bloodRepo;
    protected $bloodGroupModel;

    public function __construct()
    {
        $this->bloodRepo = new BloodRepository();
        $this->bloodGroupModel = new BloodGroupModel();
    }

    public function addSample(int $hospitalId, array $data)
    {
        $group = $this->bloodGroupModel->where('group_name', $data['blood_group'])->first();
        if (!$group) {
            throw new \Exception("Invalid blood group");
        }

        return $this->bloodRepo->create([
            'hospital_id'     => $hospitalId,
            'blood_group_id'  => $group['id'],
            'units_available' => $data['units_available'],
            'expiry_date'     => $data['expiry_date']
        ]);
    }

    public function getHospitalInventory(int $hospitalId)
    {
        return $this->bloodRepo->findByHospital($hospitalId);
    }

    public function getPublicList()
    {
        return $this->bloodRepo->getAvailableBlood();
    }
}
