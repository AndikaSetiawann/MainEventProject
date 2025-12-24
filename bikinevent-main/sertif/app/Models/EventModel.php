<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table      = 'events';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'title', 
        'description', 
        'start_date', 
        'end_date', 
        'location', 
        'max_participants',
        'institution_name',
        'certificate_number',
        'organizer_name',
        'organizer_role',
        'institution_logo',
        'organizer_signature'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation       = false;

    public function getEventStats()
    {
        $events = $this->findAll();
        $eventStats = [];

        foreach ($events as $event) {
            $participants = $this->db->table('participants')
                ->where('event_id', $event['id'])
                ->countAllResults();

            $eventStats[] = [
                'event' => [
                    'id' => $event['id'],
                    'name' => $event['title'],
                    'start_date' => $event['start_date'],
                    'end_date' => $event['end_date'],
                    'location' => $event['location'],
                    'max_participants' => $event['max_participants']
                ],
                'total_participants' => $participants
            ];
        }

        return $eventStats;
    }
}
