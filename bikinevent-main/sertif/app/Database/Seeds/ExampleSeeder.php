<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ExampleSeeder extends Seeder
{
    public function run()
    {
        $this->call('UserSeeder');
    }
}
