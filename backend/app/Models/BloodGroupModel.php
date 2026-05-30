<?php

namespace App\Models;

use CodeIgniter\Model;

class BloodGroupModel extends Model
{
    protected $table            = 'blood_groups';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['group_name'];

    protected $useTimestamps = false;
}
