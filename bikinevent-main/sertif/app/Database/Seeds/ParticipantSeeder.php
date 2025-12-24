<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ParticipantSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        $data = [
            [
                'user_id' => 2, // ID peserta (pastikan ada user dengan ID ini)
                'event_id' => 6, // ID event (pastikan ada event dengan ID ini)
                'name' => 'alvin kali',
                'email' => 'alvink13131ali@gmail.com',
                'phone' => '081234567890',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $db->table('participants')->insertBatch($data);
    }
}
