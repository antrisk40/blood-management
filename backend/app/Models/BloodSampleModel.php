<?php

namespace App\Models;

use CodeIgniter\Model;

class BloodSampleModel extends Model
{
    protected $table            = 'blood_samples';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['hospital_id', 'blood_group_id', 'units_available', 'expiry_date'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
