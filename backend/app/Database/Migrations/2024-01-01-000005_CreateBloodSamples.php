<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBloodSamples extends Migration
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
            'hospital_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'blood_group_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'units_available' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'expiry_date' => [
                'type' => 'DATE',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('hospital_id', 'hospitals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('blood_group_id', 'blood_groups', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('blood_samples');
    }

    public function down()
    {
        $this->forge->dropTable('blood_samples');
    }
}
