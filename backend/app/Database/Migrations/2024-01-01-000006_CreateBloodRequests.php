<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBloodRequests extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'receiver_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'blood_sample_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['PENDING', 'APPROVED', 'REJECTED'],
                'default'    => 'PENDING',
            ],
            'requested_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['receiver_id', 'blood_sample_id']);
        $this->forge->addForeignKey('receiver_id', 'receivers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('blood_sample_id', 'blood_samples', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('blood_requests');
    }

    public function down()
    {
        $this->forge->dropTable('blood_requests');
    }
}
