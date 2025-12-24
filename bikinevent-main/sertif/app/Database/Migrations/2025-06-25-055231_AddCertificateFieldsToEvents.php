<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCertificateFieldsToEvents extends Migration
{
    public function up()
    {
        $fields = [
            'institution_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'max_participants'
            ],
            'certificate_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'institution_name'
            ],
            'organizer_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'certificate_number'
            ],
            'organizer_role' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'organizer_name'
            ],
            'institution_logo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'organizer_role'
            ],
            'organizer_signature' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'institution_logo'
            ]
        ];

        $this->forge->addColumn('events', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('events', [
            'institution_name',
            'certificate_number',
            'organizer_name',
            'organizer_role',
            'institution_logo',
            'organizer_signature'
        ]);
    }
}
