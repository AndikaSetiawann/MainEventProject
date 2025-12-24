<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\ParticipantModel;
use App\Models\UserModel;

class Reports extends BaseController
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

    public function index(): string
    {
        // Check admin access
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        // Get dashboard statistics
        $data = [
            'title' => 'Dashboard Reports - BikinEvent.my.id',
            'page' => 'reports/dashboard',
            'stats' => $this->getDashboardStats(),
            'chartData' => $this->getChartData(),
            'recentEvents' => $this->getRecentEvents(),
            'topEvents' => $this->getTopEvents()
        ];

        return view('layout/main', $data);
    }

    public function events(): string
    {
        // Check admin access
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $events = $this->getEventsWithStats();

        $data = [
            'title' => 'Report Event - BikinEvent.my.id',
            'page' => 'reports/events',
            'events' => $events
        ];

        return view('layout/main', $data);
    }

    public function participants(): string
    {
        // Check admin access
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $participants = $this->getParticipantsWithDetails();

        $data = [
            'title' => 'Report Peserta - BikinEvent.my.id',
            'page' => 'reports/participants',
            'participants' => $participants,
            'events' => $this->eventModel->findAll()
        ];

        return view('layout/main', $data);
    }

    public function certificates(): string
    {
        // Check admin access
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $certificates = $this->getCertificatesData();

        $data = [
            'title' => 'Report Sertifikat - BikinEvent.my.id',
            'page' => 'reports/certificates',
            'certificates' => $certificates,
            'stats' => $this->getCertificateStats()
        ];

        return view('layout/main', $data);
    }

    // === PRIVATE METHODS FOR DATA PROCESSING ===

    private function getDashboardStats(): array
    {
        $totalEvents = $this->eventModel->countAllResults();
        $totalParticipants = $this->participantModel->countAllResults();
        $totalUsers = $this->userModel->countAllResults();

        // Count completed events (events that have ended)
        $completedEvents = $this->eventModel
            ->where('end_date <', date('Y-m-d H:i:s'))
            ->countAllResults();

        // Count upcoming events
        $upcomingEvents = $this->eventModel
            ->where('start_date >', date('Y-m-d H:i:s'))
            ->countAllResults();

        // Count certificates issued (participants in completed events)
        $certificatesIssued = $this->participantModel
            ->join('events', 'events.id = participants.event_id')
            ->where('events.end_date <', date('Y-m-d H:i:s'))
            ->countAllResults();

        return [
            'total_events' => $totalEvents,
            'total_participants' => $totalParticipants,
            'total_users' => $totalUsers,
            'completed_events' => $completedEvents,
            'upcoming_events' => $upcomingEvents,
            'certificates_issued' => $certificatesIssued,
            'active_events' => $totalEvents - $completedEvents,
            'completion_rate' => $totalEvents > 0 ? round(($completedEvents / $totalEvents) * 100, 1) : 0
        ];
    }

    private function getChartData(): array
    {
        // Monthly event data for the last 12 months
        $monthlyEvents = [];
        $monthlyParticipants = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $labels[] = date('M Y', strtotime("-$i months"));

            $eventCount = $this->eventModel
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $date)
                ->countAllResults();
            $monthlyEvents[] = $eventCount;

            $participantCount = $this->participantModel
                ->join('events', 'events.id = participants.event_id')
                ->where('DATE_FORMAT(participants.created_at, "%Y-%m")', $date)
                ->countAllResults();
            $monthlyParticipants[] = $participantCount;
        }

        return [
            'labels' => $labels,
            'events' => $monthlyEvents,
            'participants' => $monthlyParticipants
        ];
    }

    private function getRecentEvents(): array
    {
        return $this->eventModel
            ->select('events.*, COUNT(participants.id) as participant_count')
            ->join('participants', 'participants.event_id = events.id', 'left')
            ->groupBy('events.id')
            ->orderBy('events.created_at', 'DESC')
            ->limit(5)
            ->findAll();
    }

    private function getTopEvents(): array
    {
        return $this->eventModel
            ->select('events.*, COUNT(participants.id) as participant_count')
            ->join('participants', 'participants.event_id = events.id', 'left')
            ->groupBy('events.id')
            ->orderBy('participant_count', 'DESC')
            ->limit(5)
            ->findAll();
    }

    private function getEventsWithStats(): array
    {
        return $this->eventModel
            ->select('events.*, COUNT(participants.id) as participant_count')
            ->join('participants', 'participants.event_id = events.id', 'left')
            ->groupBy('events.id')
            ->orderBy('events.created_at', 'DESC')
            ->findAll();
    }

    private function getParticipantsWithDetails(): array
    {
        return $this->participantModel
            ->select('participants.*, events.title as event_title, events.start_date, events.end_date, users.name as participant_name, users.email')
            ->join('events', 'events.id = participants.event_id')
            ->join('users', 'users.id = participants.user_id')
            ->orderBy('participants.created_at', 'DESC')
            ->findAll();
    }

    private function getCertificatesData(): array
    {
        return $this->participantModel
            ->select('participants.*, events.title as event_title, events.end_date, users.name as participant_name, users.email')
            ->join('events', 'events.id = participants.event_id')
            ->join('users', 'users.id = participants.user_id')
            ->where('events.end_date <', date('Y-m-d H:i:s'))
            ->orderBy('events.end_date', 'DESC')
            ->findAll();
    }

    private function getCertificateStats(): array
    {
        $totalCertificates = $this->participantModel
            ->join('events', 'events.id = participants.event_id')
            ->where('events.end_date <', date('Y-m-d H:i:s'))
            ->countAllResults();

        $thisMonthCertificates = $this->participantModel
            ->join('events', 'events.id = participants.event_id')
            ->where('events.end_date <', date('Y-m-d H:i:s'))
            ->where('DATE_FORMAT(events.end_date, "%Y-%m")', date('Y-m'))
            ->countAllResults();

        return [
            'total_certificates' => $totalCertificates,
            'this_month_certificates' => $thisMonthCertificates
        ];
    }

    // === EXPORT METHODS ===

    public function exportEventsExcel()
    {
        // Check admin access
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $events = $this->getEventsWithStats();

        // Simple CSV export (bisa diganti dengan PhpSpreadsheet nanti)
        $filename = 'events_report_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Header CSV
        fputcsv($output, ['No', 'Nama Event', 'Tanggal Mulai', 'Tanggal Selesai', 'Lokasi', 'Jumlah Peserta', 'Status']);

        // Data CSV
        $no = 1;
        foreach ($events as $event) {
            $now = time();
            $startTime = strtotime($event['start_date']);
            $endTime = strtotime($event['end_date']);

            if ($endTime < $now) {
                $status = 'Selesai';
            } elseif ($startTime <= $now && $endTime >= $now) {
                $status = 'Berlangsung';
            } else {
                $status = 'Mendatang';
            }

            fputcsv($output, [
                $no++,
                $event['title'],
                date('d/m/Y H:i', strtotime($event['start_date'])),
                date('d/m/Y H:i', strtotime($event['end_date'])),
                $event['location'],
                $event['participant_count'],
                $status
            ]);
        }

        fclose($output);
        exit;
    }

    public function exportEventsPdf()
    {
        // Check admin access
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $events = $this->getEventsWithStats();

        // Load TCPDF library
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('BikinEvent.my.id');
        $pdf->SetAuthor('BikinEvent.my.id');
        $pdf->SetTitle('Laporan Event - BikinEvent.my.id');
        $pdf->SetSubject('Report Event');

        // Set default header data
        $pdf->SetHeaderData('', 0, 'BikinEvent.my.id', 'Laporan Event - ' . date('d M Y'));

        // Set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'LAPORAN EVENT', 0, 1, 'C');
        $pdf->Ln(5);

        // Table header
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(240, 240, 240);

        $pdf->Cell(10, 8, 'No', 1, 0, 'C', 1);
        $pdf->Cell(50, 8, 'Nama Event', 1, 0, 'C', 1);
        $pdf->Cell(30, 8, 'Tanggal Mulai', 1, 0, 'C', 1);
        $pdf->Cell(30, 8, 'Tanggal Selesai', 1, 0, 'C', 1);
        $pdf->Cell(40, 8, 'Lokasi', 1, 0, 'C', 1);
        $pdf->Cell(20, 8, 'Peserta', 1, 0, 'C', 1);
        $pdf->Cell(20, 8, 'Status', 1, 1, 'C', 1);

        // Table data
        $pdf->SetFont('helvetica', '', 9);
        $no = 1;

        foreach ($events as $event) {
            $now = time();
            $startTime = strtotime($event['start_date']);
            $endTime = strtotime($event['end_date']);

            if ($endTime < $now) {
                $status = 'Selesai';
            } elseif ($startTime <= $now && $endTime >= $now) {
                $status = 'Berlangsung';
            } else {
                $status = 'Mendatang';
            }

            $pdf->Cell(10, 8, $no++, 1, 0, 'C');
            $pdf->Cell(50, 8, substr($event['title'], 0, 25), 1, 0, 'L');
            $pdf->Cell(30, 8, date('d/m/Y', strtotime($event['start_date'])), 1, 0, 'C');
            $pdf->Cell(30, 8, date('d/m/Y', strtotime($event['end_date'])), 1, 0, 'C');
            $pdf->Cell(40, 8, substr($event['location'], 0, 20), 1, 0, 'L');
            $pdf->Cell(20, 8, $event['participant_count'], 1, 0, 'C');
            $pdf->Cell(20, 8, $status, 1, 1, 'C');
        }

        // Summary
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'RINGKASAN:', 0, 1, 'L');

        $pdf->SetFont('helvetica', '', 10);
        $totalEvents = count($events);
        $completedEvents = count(array_filter($events, function ($event) {
            return strtotime($event['end_date']) < time();
        }));
        $upcomingEvents = count(array_filter($events, function ($event) {
            return strtotime($event['start_date']) > time();
        }));
        $totalParticipants = array_sum(array_column($events, 'participant_count'));

        $pdf->Cell(0, 6, 'Total Event: ' . $totalEvents, 0, 1, 'L');
        $pdf->Cell(0, 6, 'Event Selesai: ' . $completedEvents, 0, 1, 'L');
        $pdf->Cell(0, 6, 'Event Mendatang: ' . $upcomingEvents, 0, 1, 'L');
        $pdf->Cell(0, 6, 'Total Peserta: ' . number_format($totalParticipants), 0, 1, 'L');

        // Output PDF
        $filename = 'Laporan_Event_' . date('Y-m-d') . '.pdf';
        return $pdf->Output($filename, 'D');
    }

    public function exportParticipantsExcel()
    {
        // Check admin access
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $participants = $this->getParticipantsWithDetails();

        $filename = 'participants_report_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Header CSV
        fputcsv($output, ['No', 'Nama Peserta', 'Email', 'Event', 'Tanggal Daftar', 'Status Event', 'Status Sertifikat']);

        // Data CSV
        $no = 1;
        foreach ($participants as $participant) {
            $now = time();
            $startTime = strtotime($participant['start_date']);
            $endTime = strtotime($participant['end_date']);

            if ($endTime < $now) {
                $eventStatus = 'Selesai';
                $certStatus = 'Tersedia';
            } elseif ($startTime <= $now && $endTime >= $now) {
                $eventStatus = 'Berlangsung';
                $certStatus = 'Belum Tersedia';
            } else {
                $eventStatus = 'Mendatang';
                $certStatus = 'Belum Tersedia';
            }

            fputcsv($output, [
                $no++,
                $participant['participant_name'],
                $participant['email'],
                $participant['event_title'],
                date('d/m/Y H:i', strtotime($participant['created_at'])),
                $eventStatus,
                $certStatus
            ]);
        }

        fclose($output);
        exit;
    }

    public function exportParticipantsPdf()
    {
        // Check admin access
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $participants = $this->getParticipantsWithDetails();

        // Load TCPDF library
        $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); // Landscape

        // Set document information
        $pdf->SetCreator('BikinEvent.my.id');
        $pdf->SetAuthor('BikinEvent.my.id');
        $pdf->SetTitle('Laporan Peserta - BikinEvent.my.id');
        $pdf->SetSubject('Report Peserta');

        // Set default header data
        $pdf->SetHeaderData('', 0, 'BikinEvent.my.id', 'Laporan Peserta - ' . date('d M Y'));

        // Set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'LAPORAN PESERTA', 0, 1, 'C');
        $pdf->Ln(5);

        // Table header
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(240, 240, 240);

        $pdf->Cell(10, 8, 'No', 1, 0, 'C', 1);
        $pdf->Cell(50, 8, 'Nama Peserta', 1, 0, 'C', 1);
        $pdf->Cell(60, 8, 'Email', 1, 0, 'C', 1);
        $pdf->Cell(60, 8, 'Event', 1, 0, 'C', 1);
        $pdf->Cell(35, 8, 'Tanggal Daftar', 1, 0, 'C', 1);
        $pdf->Cell(30, 8, 'Status Event', 1, 0, 'C', 1);
        $pdf->Cell(30, 8, 'Sertifikat', 1, 1, 'C', 1);

        // Table data
        $pdf->SetFont('helvetica', '', 8);
        $no = 1;

        foreach ($participants as $participant) {
            $now = time();
            $startTime = strtotime($participant['start_date']);
            $endTime = strtotime($participant['end_date']);

            if ($endTime < $now) {
                $eventStatus = 'Selesai';
                $certStatus = 'Tersedia';
            } elseif ($startTime <= $now && $endTime >= $now) {
                $eventStatus = 'Berlangsung';
                $certStatus = 'Belum';
            } else {
                $eventStatus = 'Mendatang';
                $certStatus = 'Belum';
            }

            $pdf->Cell(10, 8, $no++, 1, 0, 'C');
            $pdf->Cell(50, 8, substr($participant['participant_name'], 0, 25), 1, 0, 'L');
            $pdf->Cell(60, 8, substr($participant['email'], 0, 30), 1, 0, 'L');
            $pdf->Cell(60, 8, substr($participant['event_title'], 0, 30), 1, 0, 'L');
            $pdf->Cell(35, 8, date('d/m/Y', strtotime($participant['created_at'])), 1, 0, 'C');
            $pdf->Cell(30, 8, $eventStatus, 1, 0, 'C');
            $pdf->Cell(30, 8, $certStatus, 1, 1, 'C');
        }

        // Summary
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'RINGKASAN:', 0, 1, 'L');

        $pdf->SetFont('helvetica', '', 10);
        $totalParticipants = count($participants);
        $certificatesAvailable = count(array_filter($participants, function ($participant) {
            return strtotime($participant['end_date']) < time();
        }));

        $pdf->Cell(0, 6, 'Total Peserta: ' . number_format($totalParticipants), 0, 1, 'L');
        $pdf->Cell(0, 6, 'Sertifikat Tersedia: ' . number_format($certificatesAvailable), 0, 1, 'L');
        $pdf->Cell(0, 6, 'Sertifikat Belum Tersedia: ' . number_format($totalParticipants - $certificatesAvailable), 0, 1, 'L');

        // Output PDF
        $filename = 'Laporan_Peserta_' . date('Y-m-d') . '.pdf';
        return $pdf->Output($filename, 'D');
    }

    public function exportCertificatesExcel()
    {
        // Check admin access
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $certificates = $this->getCertificatesData();

        $filename = 'certificates_report_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Header CSV
        fputcsv($output, ['No', 'Nama Peserta', 'Email', 'Event', 'Tanggal Event Selesai', 'Status Sertifikat']);

        // Data CSV
        $no = 1;
        foreach ($certificates as $certificate) {
            fputcsv($output, [
                $no++,
                $certificate['participant_name'],
                $certificate['email'],
                $certificate['event_title'],
                date('d/m/Y', strtotime($certificate['end_date'])),
                'Tersedia'
            ]);
        }

        fclose($output);
        exit;
    }

    public function exportCertificatesPdf()
    {
        // Check admin access
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $certificates = $this->getCertificatesData();

        // Load TCPDF library
        $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); // Landscape

        // Set document information
        $pdf->SetCreator('BikinEvent.my.id');
        $pdf->SetAuthor('BikinEvent.my.id');
        $pdf->SetTitle('Laporan Sertifikat - BikinEvent.my.id');
        $pdf->SetSubject('Report Sertifikat');

        // Set default header data
        $pdf->SetHeaderData('', 0, 'BikinEvent.my.id', 'Laporan Sertifikat - ' . date('d M Y'));

        // Set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'LAPORAN SERTIFIKAT', 0, 1, 'C');
        $pdf->Ln(5);

        // Table header
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(240, 240, 240);

        $pdf->Cell(10, 8, 'No', 1, 0, 'C', 1);
        $pdf->Cell(60, 8, 'Nama Peserta', 1, 0, 'C', 1);
        $pdf->Cell(70, 8, 'Email', 1, 0, 'C', 1);
        $pdf->Cell(70, 8, 'Event', 1, 0, 'C', 1);
        $pdf->Cell(35, 8, 'Tanggal Selesai', 1, 0, 'C', 1);
        $pdf->Cell(30, 8, 'Status', 1, 1, 'C', 1);

        // Table data
        $pdf->SetFont('helvetica', '', 9);
        $no = 1;

        foreach ($certificates as $certificate) {
            $pdf->Cell(10, 8, $no++, 1, 0, 'C');
            $pdf->Cell(60, 8, substr($certificate['participant_name'], 0, 30), 1, 0, 'L');
            $pdf->Cell(70, 8, substr($certificate['email'], 0, 35), 1, 0, 'L');
            $pdf->Cell(70, 8, substr($certificate['event_title'], 0, 35), 1, 0, 'L');
            $pdf->Cell(35, 8, date('d/m/Y', strtotime($certificate['end_date'])), 1, 0, 'C');
            $pdf->Cell(30, 8, 'Tersedia', 1, 1, 'C');
        }

        // Summary
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'RINGKASAN:', 0, 1, 'L');

        $pdf->SetFont('helvetica', '', 10);
        $totalCertificates = count($certificates);
        $thisMonth = count(array_filter($certificates, function ($cert) {
            return date('Y-m', strtotime($cert['end_date'])) === date('Y-m');
        }));

        $pdf->Cell(0, 6, 'Total Sertifikat Diterbitkan: ' . number_format($totalCertificates), 0, 1, 'L');
        $pdf->Cell(0, 6, 'Sertifikat Bulan Ini: ' . number_format($thisMonth), 0, 1, 'L');
        $pdf->Cell(0, 6, 'Tanggal Laporan: ' . date('d F Y'), 0, 1, 'L');

        // Output PDF
        $filename = 'Laporan_Sertifikat_' . date('Y-m-d') . '.pdf';
        return $pdf->Output($filename, 'D');
    }
}
