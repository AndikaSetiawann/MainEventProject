<?php

namespace App\Models;

use CodeIgniter\Model;

class ParticipantModel extends Model
{
    protected $table      = 'participants';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['event_id', 'user_id', 'name', 'email', 'phone'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation       = false;

    public function getParticipantsByEvent($eventId)
    {
        return $this->select('participants.*, users.name as user_name, users.email as user_email')
                    ->join('users', 'users.id = participants.user_id')
                    ->where('event_id', $eventId)
                    ->findAll();
    }
}
