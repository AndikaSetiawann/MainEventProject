<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PopulateAdminsTable extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        $query = $db->table('users')->getWhere(['role' => 'admin']);

        foreach ($query->getResult() as $row) {
            $data = [
                'user_id' => $row->id,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ];
            $db->table('admins')->insert($data);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $db->table('admins')->truncate();
    }
}
