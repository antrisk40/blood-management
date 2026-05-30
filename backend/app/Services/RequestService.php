<?php

namespace App\Services;

use App\Repositories\RequestRepository;
use App\Repositories\BloodRepository;
use App\Repositories\ReceiverRepository;
use App\Libraries\BloodCompatibilityLibrary;

class RequestService
{
    protected $requestRepo;
    protected $bloodRepo;
    protected $receiverRepo;

    public function __construct()
    {
        $this->requestRepo = new RequestRepository();
        $this->bloodRepo = new BloodRepository();
        $this->receiverRepo = new ReceiverRepository();
    }

    public function requestSample(int $receiverProfileId, int $bloodSampleId, int $unitsRequested)
    {
        if ($this->requestRepo->hasRequested($receiverProfileId, $bloodSampleId)) {
            throw new \Exception("You have already requested this blood sample.");
        }

        $sample = $this->bloodRepo->findById($bloodSampleId);
        if (!$sample) {
            throw new \Exception("Blood sample not found.");
        }
        if ($unitsRequested > $sample['units_available']) {
            throw new \Exception("Cannot request more units than available ({$sample['units_available']} units).");
        }

        $receiver = $this->receiverRepo->findByUserId(session()->get('user_id') ?? $receiverProfileId); // simplified lookup
        // Wait, the profile ID passed is actually the receiver's ID.
        // Let's just fetch receiver by ID directly via model or use the stored session role payload.
        $receiverModel = new \App\Models\ReceiverModel();
        $receiver = $receiverModel->find($receiverProfileId);

        if (!$receiver) {
            throw new \Exception("Receiver profile not found.");
        }

        if (!BloodCompatibilityLibrary::canRequest($receiver['blood_group'], $sample['group_name'])) {
            throw new \Exception("Your blood group ({$receiver['blood_group']}) is not compatible with sample blood group ({$sample['group_name']}).");
        }

        return $this->requestRepo->create([
            'receiver_id' => $receiverProfileId,
            'blood_sample_id' => $bloodSampleId,
            'units_requested' => $unitsRequested,
            'status' => 'PENDING',
            'requested_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getHospitalRequests(int $hospitalProfileId)
    {
        return $this->requestRepo->findRequestsForHospital($hospitalProfileId);
    }

    public function markAsDelivered(int $requestId, int $hospitalProfileId)
    {
        $request = $this->requestRepo->findById($requestId);
        if (!$request) {
            throw new \Exception("Request not found.");
        }

        if ($request['status'] !== 'PENDING' && $request['status'] !== 'APPROVED') {
            throw new \Exception("Request must be in PENDING or APPROVED status to be delivered.");
        }

        $sample = $this->bloodRepo->findById($request['blood_sample_id']);
        if (!$sample || $sample['hospital_id'] != $hospitalProfileId) {
            throw new \Exception("Unauthorized to deliver this request.");
        }

        if ($request['units_requested'] > $sample['units_available']) {
            throw new \Exception("Not enough blood units available to fulfill this request.");
        }

        // Deduct from inventory
        $newUnits = $sample['units_available'] - $request['units_requested'];
        $this->bloodRepo->update($sample['id'], ['units_available' => $newUnits]);

        // Update request status
        return $this->requestRepo->update($requestId, ['status' => 'DELIVERED']);
    }
}
