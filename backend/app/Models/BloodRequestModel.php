<?php

namespace App\Models;

use CodeIgniter\Model;

class BloodRequestModel extends Model
{
    protected $table            = 'blood_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['receiver_id', 'blood_sample_id', 'units_requested', 'status', 'requested_at'];

    protected $useTimestamps = true; // For requested_at, but we'll manage it carefully
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'requested_at';
    protected $updatedField  = '';
}
