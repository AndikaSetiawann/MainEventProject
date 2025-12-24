<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\ParticipantModel;

class Dashboard extends BaseController
{
    public function index(): \CodeIgniter\HTTP\RedirectResponse|string
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = session()->get('role');

        if ($role === 'admin') {
            $eventModel = new EventModel();
            $participantModel = new ParticipantModel(); // Ensure ParticipantModel is instantiated for admin dashboard

            $totalEvents = $eventModel->countAllResults();
            $upcomingEvents = $eventModel->where('start_date >', date('Y-m-d H:i:s'))->countAllResults();
            $totalParticipants = $participantModel->countAllResults(); // Get total participants

            // Hitung sertifikat yang diterbitkan (event yang sudah selesai)
            $issuedCertificates = $eventModel->where('end_date <', date('Y-m-d H:i:s'))->countAllResults();

            $data = [
                'title' => 'Admin Dashboard - BikinEvent.my.id',
                'page' => 'dashboard',
                'total_events' => $totalEvents,
                'upcoming_events' => $upcomingEvents,
                'total_participants' => $totalParticipants,
                'issued_certificates' => $issuedCertificates, // Tambahkan jumlah sertifikat yang diterbitkan
            ];

            return view('layout/main', $data);
        } elseif ($role === 'participant') {
            $userId = session()->get('id');
            $userName = session()->get('name'); // Get user's name from session

            $participantModel = new ParticipantModel();
            $eventModel = new EventModel();

            // Get events the participant has registered for
            $registeredEvents = (new ParticipantModel()) // Re-instantiate model for fresh query
                ->where('user_id', $userId)
                ->join('events', 'events.id = participants.event_id')
                ->where('participants.event_id IS NOT NULL')
                ->countAllResults(); // countAllResults() will reset the builder for the next query

            // Get upcoming events for the participant
            $upcomingEvents = (new ParticipantModel()) // Re-instantiate model for fresh query
                ->join('events', 'events.id = participants.event_id')
                ->where('participants.user_id', $userId)
                ->where('events.start_date >', date('Y-m-d H:i:s'))
                ->countAllResults(); // countAllResults() will reset the builder for the next query

            // Get available certificates (assuming all registered events have certificates)
            $availableCertificates = (new ParticipantModel()) // Re-instantiate model for fresh query
                ->join('events', 'events.id = participants.event_id')
                ->where('participants.user_id', $userId)
                ->where('events.end_date <', date('Y-m-d H:i:s')) // Event must be completed
                ->countAllResults(); // countAllResults() will reset the builder for the next query

            $data = [
                'title' => 'Dashboard Peserta - BikinEvent.my.id',
                'page' => 'participant_dashboard',
                'userName' => $userName,
                'registered_events' => $registeredEvents,
                'upcoming_events' => $upcomingEvents,
                'available_certificates' => $availableCertificates,
            ];

            return view('layout/main', $data);
        } else {
            // Fallback for unknown roles or if role is not set
            return redirect()->to('/no_access');
        }
    }
}
