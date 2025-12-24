<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\ParticipantModel;
use App\Models\UserModel;

class Certificates extends BaseController
{
    protected $eventModel;
    protected $participantModel;
    protected $userModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
        $this->participantModel = new ParticipantModel();
        $this->userModel = new UserModel();
    }

    public function generate($participantId)
    {
        // Ambil data peserta
        $participant = $this->participantModel->find($participantId);
        
        if (!$participant) {
            return redirect()->to('/reports/participants')->with('error', 'Data peserta tidak ditemukan.');
        }

        // Ambil data event
        $event = $this->eventModel->find($participant['event_id']);
        
        if (!$event) {
            return redirect()->to('/reports/participants')->with('error', 'Data event tidak ditemukan.');
        }

        // Cek apakah event sudah selesai
        if (strtotime($event['end_date']) > time()) {
            return redirect()->to('/reports/participants')->with('error', 'Sertifikat hanya dapat dicetak setelah event selesai.');
        }

        // Ambil data user (peserta)
        $user = $this->userModel->find($participant['user_id']);

        // Pastikan kunci-kunci yang dibutuhkan ada di array $event
        // Berikan nilai default jika kolom ini tidak ada di tabel 'events'
        $event['organizer_name'] = $event['organizer_name'] ?? '(Nama Ketua Penyelenggara)'; 
        $event['organizer_role'] = $event['organizer_role'] ?? '(Jabatan Ketua Penyelenggara)';
        $event['institution_logo'] = $event['institution_logo'] ?? ''; 
        $event['organizer_signature'] = $event['organizer_signature'] ?? ''; 
        $event['certificate_number'] = $event['certificate_number'] ?? ''; 

        $data = [
            'title' => 'Sertifikat - ' . $event['title'],
            'event' => $event,
            'user' => $user,
            'participant' => $participant
        ];

        return view('pages/certificates/generate', $data);
    }
} 