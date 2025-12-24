<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\EventModel;
use App\Models\ParticipantModel;

class Admin extends BaseController
{
    private function checkAdmin()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }
    }

    public function index()
    {
        $this->checkAdmin();

        $eventModel = new EventModel();
        $participantModel = new ParticipantModel();
        $userModel = new UserModel();

        $data = [
            'title' => 'Admin Panel - BikinEvent.my.id',
            'page' => 'admin/index',
            'total_events' => $eventModel->countAllResults(),
            'total_participants' => $participantModel->countAllResults(),
            'total_users' => $userModel->countAllResults(),
            'total_admins' => $userModel->where('role', 'admin')->countAllResults()
        ];

        return view('layout/main', $data);
    }

    public function users()
    {
        $this->checkAdmin();

        $userModel = new UserModel();
        $users = $userModel->findAll();

        $data = [
            'title' => 'Kelola User - Admin Panel',
            'page' => 'admin/users',
            'users' => $users
        ];

        return view('layout/main', $data);
    }

    public function events()
    {
        $this->checkAdmin();

        $eventModel = new EventModel();
        $participantModel = new ParticipantModel();

        $events = $eventModel->findAll();

        // Add participant count for each event
        foreach ($events as &$event) {
            $event['participant_count'] = $participantModel->where('event_id', $event['id'])->countAllResults();
        }
        unset($event);

        $data = [
            'title' => 'Kelola Event - Admin Panel',
            'page' => 'admin/events',
            'events' => $events
        ];

        return view('layout/main', $data);
    }
}
