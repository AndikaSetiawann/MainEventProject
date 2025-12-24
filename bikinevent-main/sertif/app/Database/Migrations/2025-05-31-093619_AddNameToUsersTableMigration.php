<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNameToUsersTableMigration extends Migration
{
    public function up()
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

    public function down()
    {
        $this->forge->dropColumn('users', ['name', 'phone_number']);
    }
}
