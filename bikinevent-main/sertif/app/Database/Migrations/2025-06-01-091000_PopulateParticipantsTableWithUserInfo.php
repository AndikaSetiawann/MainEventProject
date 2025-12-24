<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PopulateParticipantsTableWithUserInfo extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // Assuming participants are all users with role 'participant'
        $query = $db->table('users')->getWhere(['role' => 'participant']);

        foreach ($query->getResult() as $row) {
            $data = [
                'email' => $row->email,
            ];
            $db->table('participants')->where('user_id', $row->id)->update($data);
        }
    }

    public function down()
    {
        // No need to revert this
    }
}
