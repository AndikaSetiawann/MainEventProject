<?php

namespace App\Controllers;

use App\Models\ParticipantModel;

class Participants extends BaseController
{
    public function register()
    {
        $rules = [
            'event_id' => 'required|numeric',
            'name'     => 'required|min_length[3]|max_length[255]',
            'email'    => 'required|valid_email',
        ];

        if (! $this->validate($rules)) {
            $eventId = $this->request->getPost('event_id');
            if ($eventId) {
                return redirect()->to('/events/register/' . $eventId)->withInput()->with('errors', $this->validator->getErrors());
            } else {
                return redirect()->to('/events/register')->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        $eventId = $this->request->getPost('event_id');

        // Fetch event details to check participant limit
        $eventModel = new \App\Models\EventModel();
        $event = $eventModel->find($eventId);

        if (! $event) {
            return redirect()->to('/events')->with('error', 'Event tidak ditemukan.');
        }

        $participantModel = new ParticipantModel();

        // Check current participants against max_participants
        $currentParticipants = $participantModel->where('event_id', $eventId)->countAllResults();
        $maxParticipants = $event['max_participants'];

        if ($maxParticipants !== null && $maxParticipants > 0 && $currentParticipants >= $maxParticipants) {
            return redirect()->to('/events/register/' . $eventId)->with('error', 'Maaf, event ini sudah penuh.');
        }

        $data = [
            'event_id' => $eventId,
            'user_id' => session()->get('id'),
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
        ];

        // Check if participant already registered for this event
        $existingParticipant = $participantModel->where('event_id', $data['event_id'])
            ->where('email', $data['email'])
            ->first();

        if ($existingParticipant) {
            return redirect()->to('/events/register/' . $data['event_id'])->with('error', 'Anda sudah terdaftar di event ini.');
        }

        $participantModel->insert($data);

        log_message('info', 'Berhasil Daftar: ' . $data['email'] . ' untuk event ' . $data['event_id']);

        return redirect()->to('/events/view/' . $this->request->getPost('event_id'))->with('success', 'Pendaftaran berhasil!');
    }

    public function certificate($id)
    {
        $participantModel = new ParticipantModel();
        $participant = $participantModel->find($id);

        $eventModel = new \App\Models\EventModel();
        $event = $eventModel->find($participant['event_id']);

        $data = [
            'participant' => $participant,
            'event' => $event
        ];

        return view('pages/events/certificate', $data);
    }

    public function index()
    {
        $model = new ParticipantModel();
        $participants = $model->findAll();

        $data = [
            'title' => 'Daftar Peserta - BikinEvent.my.id',
            'page' => 'events/participants',
            'participants' => $participants
        ];

        return view('layout/main', $data);
    }
}
