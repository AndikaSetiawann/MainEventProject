<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyUsersTable extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('users', ['name', 'phone_number']);
    }

    public function down()
    {
        $this->forge->addColumn('users', [
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
                'after' => 'email',
            ],
            'phone_number' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'after' => 'name',
            ],
        ]);
    }
}
