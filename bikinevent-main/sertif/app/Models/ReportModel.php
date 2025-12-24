<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    protected $table = 'events'; // Base table
    protected $primaryKey = 'id';
    protected $allowedFields = [];

    // === DASHBOARD ANALYTICS ===

    public function getEventTrends(int $months = 12): array
    {
        $builder = $this->db->table('events');
        $builder->select("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count");
        $builder->where('created_at >=', date('Y-m-d', strtotime("-$months months")));
        $builder->groupBy("DATE_FORMAT(created_at, '%Y-%m')");
        $builder->orderBy('month', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    public function getParticipantTrends(int $months = 12): array
    {
        $builder = $this->db->table('participants');
        $builder->select("DATE_FORMAT(participants.created_at, '%Y-%m') as month, COUNT(*) as count");
        $builder->where('participants.created_at >=', date('Y-m-d', strtotime("-$months months")));
        $builder->groupBy("DATE_FORMAT(participants.created_at, '%Y-%m')");
        $builder->orderBy('month', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    public function getEventsByStatus(): array
    {
        $now = date('Y-m-d H:i:s');
        
        $builder = $this->db->table('events');
        $builder->select("
            SUM(CASE WHEN start_date > '$now' THEN 1 ELSE 0 END) as upcoming,
            SUM(CASE WHEN start_date <= '$now' AND end_date >= '$now' THEN 1 ELSE 0 END) as ongoing,
            SUM(CASE WHEN end_date < '$now' THEN 1 ELSE 0 END) as completed
        ");
        
        return $builder->get()->getRowArray();
    }

    public function getTopEventsByParticipants(int $limit = 10): array
    {
        $builder = $this->db->table('events');
        $builder->select('events.*, COUNT(participants.id) as participant_count');
        $builder->join('participants', 'participants.event_id = events.id', 'left');
        $builder->groupBy('events.id');
        $builder->orderBy('participant_count', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }

    // === EVENT ANALYTICS ===

    public function getEventDetails(int $eventId): array
    {
        $builder = $this->db->table('events');
        $builder->select('events.*, COUNT(participants.id) as participant_count');
        $builder->join('participants', 'participants.event_id = events.id', 'left');
        $builder->where('events.id', $eventId);
        $builder->groupBy('events.id');
        
        return $builder->get()->getRowArray() ?: [];
    }

    public function getEventParticipantsByDate(int $eventId): array
    {
        $builder = $this->db->table('participants');
        $builder->select("DATE(created_at) as date, COUNT(*) as count");
        $builder->where('event_id', $eventId);
        $builder->groupBy('DATE(created_at)');
        $builder->orderBy('date', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    public function getEventCompletionRate(): array
    {
        $builder = $this->db->table('events');
        $builder->select('
            COUNT(*) as total_events,
            SUM(CASE WHEN end_date < NOW() THEN 1 ELSE 0 END) as completed_events
        ');
        
        $result = $builder->get()->getRowArray();
        $result['completion_rate'] = $result['total_events'] > 0 
            ? round(($result['completed_events'] / $result['total_events']) * 100, 2) 
            : 0;
            
        return $result;
    }

    // === PARTICIPANT ANALYTICS ===

    public function getParticipantsByEvent(): array
    {
        $builder = $this->db->table('events');
        $builder->select('events.title, events.id, COUNT(participants.id) as participant_count');
        $builder->join('participants', 'participants.event_id = events.id', 'left');
        $builder->groupBy('events.id');
        $builder->orderBy('participant_count', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getParticipantGrowth(int $months = 12): array
    {
        $builder = $this->db->table('participants');
        $builder->select("
            DATE_FORMAT(created_at, '%Y-%m') as month,
            COUNT(*) as new_participants,
            (SELECT COUNT(*) FROM participants p2 WHERE p2.created_at <= LAST_DAY(STR_TO_DATE(CONCAT(DATE_FORMAT(participants.created_at, '%Y-%m'), '-01'), '%Y-%m-%d'))) as cumulative_participants
        ");
        $builder->where('created_at >=', date('Y-m-d', strtotime("-$months months")));
        $builder->groupBy("DATE_FORMAT(created_at, '%Y-%m')");
        $builder->orderBy('month', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    public function getActiveParticipants(): array
    {
        $builder = $this->db->table('users');
        $builder->select('users.*, COUNT(participants.id) as event_count');
        $builder->join('participants', 'participants.user_id = users.id');
        $builder->where('users.role', 'user');
        $builder->groupBy('users.id');
        $builder->having('event_count >', 0);
        $builder->orderBy('event_count', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    // === CERTIFICATE ANALYTICS ===

    public function getCertificateStats(): array
    {
        $builder = $this->db->table('participants');
        $builder->select('
            COUNT(*) as total_eligible,
            COUNT(CASE WHEN events.end_date < NOW() THEN 1 END) as certificates_issued
        ');
        $builder->join('events', 'events.id = participants.event_id');
        
        $result = $builder->get()->getRowArray();
        $result['issuance_rate'] = $result['total_eligible'] > 0 
            ? round(($result['certificates_issued'] / $result['total_eligible']) * 100, 2) 
            : 0;
            
        return $result;
    }

    public function getCertificatesByMonth(int $months = 12): array
    {
        $builder = $this->db->table('participants');
        $builder->select("
            DATE_FORMAT(events.end_date, '%Y-%m') as month,
            COUNT(*) as certificates_issued
        ");
        $builder->join('events', 'events.id = participants.event_id');
        $builder->where('events.end_date <', date('Y-m-d H:i:s'));
        $builder->where('events.end_date >=', date('Y-m-d', strtotime("-$months months")));
        $builder->groupBy("DATE_FORMAT(events.end_date, '%Y-%m')");
        $builder->orderBy('month', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    public function getRecentCertificates(int $limit = 10): array
    {
        $builder = $this->db->table('participants');
        $builder->select('participants.*, events.title as event_title, events.end_date, users.name as participant_name, users.email');
        $builder->join('events', 'events.id = participants.event_id');
        $builder->join('users', 'users.id = participants.user_id');
        $builder->where('events.end_date <', date('Y-m-d H:i:s'));
        $builder->orderBy('events.end_date', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }

    // === EXPORT HELPERS ===

    public function getFullEventReport(): array
    {
        $builder = $this->db->table('events');
        $builder->select('
            events.*,
            COUNT(participants.id) as participant_count,
            CASE 
                WHEN events.start_date > NOW() THEN "Upcoming"
                WHEN events.start_date <= NOW() AND events.end_date >= NOW() THEN "Ongoing"
                ELSE "Completed"
            END as status
        ');
        $builder->join('participants', 'participants.event_id = events.id', 'left');
        $builder->groupBy('events.id');
        $builder->orderBy('events.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getFullParticipantReport(): array
    {
        $builder = $this->db->table('participants');
        $builder->select('
            participants.*,
            events.title as event_title,
            events.start_date,
            events.end_date,
            events.location,
            users.name as participant_name,
            users.email,
            CASE 
                WHEN events.end_date < NOW() THEN "Certificate Available"
                ELSE "Event Not Completed"
            END as certificate_status
        ');
        $builder->join('events', 'events.id = participants.event_id');
        $builder->join('users', 'users.id = participants.user_id');
        $builder->orderBy('participants.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    // === SUMMARY STATISTICS ===

    public function getSummaryStats(): array
    {
        $stats = [];
        
        // Total counts
        $stats['total_events'] = $this->db->table('events')->countAllResults();
        $stats['total_participants'] = $this->db->table('participants')->countAllResults();
        $stats['total_users'] = $this->db->table('users')->where('role', 'user')->countAllResults();
        
        // This month stats
        $thisMonth = date('Y-m');
        $stats['events_this_month'] = $this->db->table('events')
            ->where("DATE_FORMAT(created_at, '%Y-%m')", $thisMonth)
            ->countAllResults();
        $stats['participants_this_month'] = $this->db->table('participants')
            ->where("DATE_FORMAT(created_at, '%Y-%m')", $thisMonth)
            ->countAllResults();
        
        // Certificates
        $stats['certificates_issued'] = $this->db->table('participants')
            ->join('events', 'events.id = participants.event_id')
            ->where('events.end_date <', date('Y-m-d H:i:s'))
            ->countAllResults();
        
        return $stats;
    }
}
