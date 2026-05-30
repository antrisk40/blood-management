<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateBloodRequestsForDelivery extends Migration
{
    public function up()
    {
        // Add units_requested column
        $this->forge->addColumn('blood_requests', [
            'units_requested' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
                'after'      => 'blood_sample_id'
            ]
        ]);

        // Modify status ENUM to include DELIVERED
        $this->db->query("ALTER TABLE blood_requests MODIFY COLUMN status ENUM('PENDING', 'APPROVED', 'REJECTED', 'DELIVERED') DEFAULT 'PENDING'");
    }

    public function down()
    {
        $this->forge->dropColumn('blood_requests', 'units_requested');
        $this->db->query("ALTER TABLE blood_requests MODIFY COLUMN status ENUM('PENDING', 'APPROVED', 'REJECTED') DEFAULT 'PENDING'");
    }
}
