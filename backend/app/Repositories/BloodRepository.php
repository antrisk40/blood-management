<?php

namespace App\Repositories;

use App\Models\BloodSampleModel;

class BloodRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new BloodSampleModel();
    }

    public function create(array $data)
    {
        return $this->model->insert($data, true);
    }

    public function update(int $id, array $data)
    {
        return $this->model->update($id, $data);
    }

    public function delete(int $id, int $hospitalId)
    {
        // Ensure only the owner can delete
        return $this->model->where('id', $id)
                           ->where('hospital_id', $hospitalId)
                           ->delete();
    }

    public function findByHospital(int $hospitalId)
    {
        return $this->model->select('blood_samples.*, blood_groups.group_name')
                           ->join('blood_groups', 'blood_groups.id = blood_samples.blood_group_id')
                           ->where('hospital_id', $hospitalId)
                           ->findAll();
    }

    public function getAvailableBlood()
    {
        // Public list: Show available units and hospital details
        return $this->model->select('blood_samples.id, blood_samples.units_available, blood_samples.expiry_date, blood_groups.group_name, hospitals.hospital_name, hospitals.address')
                           ->join('blood_groups', 'blood_groups.id = blood_samples.blood_group_id')
                           ->join('hospitals', 'hospitals.id = blood_samples.hospital_id')
                           ->where('units_available >', 0)
                           ->where('expiry_date >=', date('Y-m-d'))
                           ->findAll();
    }

    public function findById(int $id)
    {
        return $this->model->select('blood_samples.*, blood_groups.group_name')
                           ->join('blood_groups', 'blood_groups.id = blood_samples.blood_group_id')
                           ->where('blood_samples.id', $id)
                           ->first();
    }
}
