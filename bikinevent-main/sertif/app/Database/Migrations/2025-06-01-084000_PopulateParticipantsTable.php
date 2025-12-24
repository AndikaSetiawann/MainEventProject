<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PopulateParticipantsTable extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // Assuming participants are all users with role 'participant'
        $query = $db->table('users')->getWhere(['role' => 'participant']);

        foreach ($query->getResult() as $row) {
            $data = [
                'user_id' => $row->id,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ];
            $db->table('participants')->insert($data);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $db->table('participants')->truncate();
    }
}
