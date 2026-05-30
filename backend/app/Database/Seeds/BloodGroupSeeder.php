<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BloodGroupSeeder extends Seeder
{
    public function run()
    {
        $groups = [
            ['group_name' => 'O-'],
            ['group_name' => 'O+'],
            ['group_name' => 'A-'],
            ['group_name' => 'A+'],
            ['group_name' => 'B-'],
            ['group_name' => 'B+'],
            ['group_name' => 'AB-'],
            ['group_name' => 'AB+'],
        ];

        $this->db->table('blood_groups')->insertBatch($groups);
    }
}
