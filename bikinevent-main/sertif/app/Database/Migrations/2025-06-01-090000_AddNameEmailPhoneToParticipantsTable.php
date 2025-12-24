<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNameEmailPhoneToParticipantsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('participants', [
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('participants', ['name', 'email', 'phone']);
    }
}
