<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBloodGroups extends Migration
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
            'group_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'unique'     => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('blood_groups');
    }

    public function down()
    {
        $this->forge->dropTable('blood_groups');
    }
}
