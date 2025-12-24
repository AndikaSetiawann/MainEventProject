<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SpecificUserAndEventSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Create a specific user
        $dataUser = [
            'id' => 2,
            'email' => 'alvink13131ali@gmail.com',
            'password' => password_hash('alvin123', PASSWORD_DEFAULT),
            'role' => 'participant',
            'name' => 'alvin kali',
            'phone_number' => '081234567890',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $db->table('users')->insert($dataUser);

        // Create a specific event
        $dataEvent = [
            'id' => 6,
            'title' => 'Seminar Bareng Marsha',
            'description' => 'Seminar tentang manajemen event',
            'start_date' => date('Y-m-d H:i:s', strtotime('+1 day')),
            'end_date' => date('Y-m-d H:i:s', strtotime('+2 days')),
            'location' => 'Surabaya',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $db->table('events')->insert($dataEvent);
    }
}
