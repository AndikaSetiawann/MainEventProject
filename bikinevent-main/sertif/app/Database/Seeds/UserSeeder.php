<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'email' => 'participant@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => 'participant',
                'name' => 'Participant',
                'phone_number' => '0987654321',
            ],
        ];

        // Simple Queries
        // $this->db->query("INSERT INTO users (email, name) VALUES(:email:, :name:)", $data);

        // Using Query Builder
        $model = new \App\Models\UserModel();
        $model->truncate();
        $model->insertBatch($data);
    }
}
