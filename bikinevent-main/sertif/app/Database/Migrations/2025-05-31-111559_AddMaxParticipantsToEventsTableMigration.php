<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMaxParticipantsToEventsTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addColumn('events', [
            'max_participants' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'default' => null,
                'after' => 'location',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('events', ['max_participants']);
    }
}
