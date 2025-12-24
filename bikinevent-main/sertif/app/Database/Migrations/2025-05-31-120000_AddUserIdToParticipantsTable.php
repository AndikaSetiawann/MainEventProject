<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToParticipantsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('participants', [
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'after'          => 'event_id',
            ],
        ]);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('participants', 'user_id');
        $this->forge->dropColumn('participants', 'user_id');
    }
}
