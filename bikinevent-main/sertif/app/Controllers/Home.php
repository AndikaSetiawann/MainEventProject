<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\ParticipantModel;

class Home extends BaseController
{
    public function index(): \CodeIgniter\HTTP\RedirectResponse|string
    {
        if (! session()->get('isLoggedIn')) {
            // If not logged in, show the home page or redirect to login
            return view('pages/home', ['title' => 'Selamat Datang di BikinEvent.my.id']);
        }

        // If logged in, redirect to the appropriate dashboard
        return redirect()->to('/dashboard');
    }
}
