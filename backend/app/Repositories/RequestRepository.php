<?php

namespace App\Repositories;

use App\Models\BloodRequestModel;

class RequestRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new BloodRequestModel();
    }

    public function create(array $data)
    {
        return $this->model->insert($data, true);
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function update(int $id, array $data)
    {
        return $this->model->update($id, $data);
    }

    public function hasRequested(int $receiverId, int $bloodSampleId): bool
    {
        $existing = $this->model->where('receiver_id', $receiverId)
                                ->where('blood_sample_id', $bloodSampleId)
                                ->first();
        return !empty($existing);
    }

    public function findRequestsForHospital(int $hospitalId)
    {
        return $this->model->select('blood_requests.*, receivers.full_name as receiver_name, blood_groups.group_name, blood_samples.units_available')
                           ->join('receivers', 'receivers.id = blood_requests.receiver_id')
                           ->join('blood_samples', 'blood_samples.id = blood_requests.blood_sample_id')
                           ->join('blood_groups', 'blood_groups.id = blood_samples.blood_group_id')
                           ->where('blood_samples.hospital_id', $hospitalId)
                           ->findAll();
    }

    public function findRequestsForReceiver(int $receiverId)
    {
        return $this->model->select('blood_requests.*, hospitals.hospital_name, blood_groups.group_name')
                           ->join('blood_samples', 'blood_samples.id = blood_requests.blood_sample_id')
                           ->join('hospitals', 'hospitals.id = blood_samples.hospital_id')
                           ->join('blood_groups', 'blood_groups.id = blood_samples.blood_group_id')
                           ->where('blood_requests.receiver_id', $receiverId)
                           ->findAll();
    }
}
