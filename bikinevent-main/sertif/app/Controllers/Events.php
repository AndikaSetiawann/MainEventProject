<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\ParticipantModel;

class Events extends BaseController
{
    private function checkAdmin()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            session()->destroy();
            return redirect()->to('/login');
        }
    }

    public function index(): string
    {
        $eventModel = new EventModel();
        $participantModel = new ParticipantModel();

        if (session()->get('role') === 'admin') {
            $this->checkAdmin();
            $events = $eventModel->findAll();

            // Fetch participant count for each event
            foreach ($events as &$event) {
                $event['current_participants'] = (new ParticipantModel())->where('event_id', $event['id'])->countAllResults();
            }
            unset($event); // Unset reference to last element

            $data = [
                'title' => 'Kelola Event - BikinEvent.my.id',
                'page' => 'events/index',
                'events' => $events,
            ];

            return view('layout/main', $data);
        } else {
            $events = $eventModel->findAll();

            // Fetch participant count for each event
            foreach ($events as &$event) {
                $event['current_participants'] = (new ParticipantModel())->where('event_id', $event['id'])->countAllResults();
            }
            unset($event); // Unset reference to last element

            $registeredEventIds = $participantModel
                ->where('user_id', session()->get('id'))
                ->findColumn('event_id');

            $data = [
                'title' => 'Daftar Event - BikinEvent.my.id',
                'page' => 'events/participant_index',
                'events' => $events,
                'registeredEventIds' => $registeredEventIds ?? [],
            ];

            return view('layout/main', $data);
        }
    }

    public function create(): string
    {
        $this->checkAdmin();
        $data = [
            'title' => 'Tambah Event - BikinEvent.my.id',
            'page' => 'events/create',
        ];

        return view('layout/main', $data);
    }

    public function store()
    {
        $this->checkAdmin();

        $rules = [
            'title'        => 'required|min_length[3]|max_length[255]',
            'description'  => 'required',
            'start_date'   => 'required|valid_date',
            'end_date'     => 'required|valid_date',
            'location'     => 'required|min_length[3]|max_length[255]',
            'max_participants' => 'permit_empty|is_natural', // Allow empty or natural number
        ];

        $validation =  \Config\Services::validation();
        $validation->setRules($rules);

        if (! $validation->withRequest($this->request)->run()) {
            return redirect()->to('/events/create')->withInput()->with('errors', $validation->getErrors());
        }

        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');

        if (strtotime($endDate) <= strtotime($startDate)) {
            return redirect()->to('/events/create')->withInput()->with('errors', ['end_date' => 'The End Date must be after the Start Date']);
        }

        $model = new EventModel();

        // Handle file uploads
        $institutionLogo = $this->request->getFile('institution_logo');
        $organizerSignature = $this->request->getFile('organizer_signature');

        $logoFileName = null;
        $signatureFileName = null;

        // Upload institution logo
        if ($institutionLogo && $institutionLogo->isValid() && !$institutionLogo->hasMoved()) {
            $logoFileName = $institutionLogo->getRandomName();
            $institutionLogo->move(WRITEPATH . '../public/uploads/institutions/', $logoFileName);
        }

        // Upload organizer signature
        if ($organizerSignature && $organizerSignature->isValid() && !$organizerSignature->hasMoved()) {
            $signatureFileName = $organizerSignature->getRandomName();
            $organizerSignature->move(WRITEPATH . '../public/uploads/signatures/', $signatureFileName);
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'location' => $this->request->getPost('location'),
            'max_participants' => $this->request->getPost('max_participants') === '' ? null : $this->request->getPost('max_participants'),
            'institution_name' => $this->request->getPost('institution_name'),
            'organizer_name' => $this->request->getPost('organizer_name'),
            'organizer_role' => $this->request->getPost('organizer_role'),
            'certificate_number' => $this->request->getPost('certificate_number'),
            'institution_logo' => $logoFileName,
            'organizer_signature' => $signatureFileName,
        ];

        $model->insert($data);

        return redirect()->to('/events')->with('success', 'Event berhasil ditambahkan');
    }

    public function edit(int $id): \CodeIgniter\HTTP\RedirectResponse|string
    {
        $this->checkAdmin();

        $model = new EventModel();
        $event = $model->find($id);

        if (! $event) {
            return redirect()->to('/events')->with('error', 'Event tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Event: ' . $event['title'],
            'page' => 'events/edit',
            'event' => $event, // Pass the event data
        ];

        return view('layout/main', $data);
    }

    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $this->checkAdmin();

        $rules = [
            'title'        => 'required|min_length[3]|max_length[255]',
            'description'  => 'required',
            'start_date'   => 'required|valid_date',
            'end_date'     => 'required|valid_date',
            'location'     => 'required|min_length[3]|max_length[255]',
            'max_participants' => 'permit_empty|is_natural', // Allow empty or natural number
        ];

        $validation =  \Config\Services::validation();
        $validation->setRules($rules);

        if (! $validation->withRequest($this->request)->run()) {
            return redirect()->to('/events/edit/' . $id)->withInput()->with('errors', $this->validator->getErrors());
        }

        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');

        if (strtotime($endDate) <= strtotime($startDate)) {
            return redirect()->to('/events/edit/' . $id)->withInput()->with('errors', ['end_date' => 'The End Date must be after the Start Date']);
        }

        $model = new EventModel();
        $event = $model->find($id);

        if (! $event) {
            return redirect()->to('/events')->with('error', 'Event tidak ditemukan.');
        }

        // Handle file uploads
        $institutionLogo = $this->request->getFile('institution_logo');
        $organizerSignature = $this->request->getFile('organizer_signature');

        $logoFileName = $event['institution_logo']; // Keep existing if no new upload
        $signatureFileName = $event['organizer_signature']; // Keep existing if no new upload

        // Upload new institution logo if provided
        if ($institutionLogo && $institutionLogo->isValid() && !$institutionLogo->hasMoved()) {
            // Delete old logo if exists
            if (!empty($event['institution_logo']) && file_exists(WRITEPATH . '../public/uploads/institutions/' . $event['institution_logo'])) {
                unlink(WRITEPATH . '../public/uploads/institutions/' . $event['institution_logo']);
            }
            $logoFileName = $institutionLogo->getRandomName();
            $institutionLogo->move(WRITEPATH . '../public/uploads/institutions/', $logoFileName);
        }

        // Upload new organizer signature if provided
        if ($organizerSignature && $organizerSignature->isValid() && !$organizerSignature->hasMoved()) {
            // Delete old signature if exists
            if (!empty($event['organizer_signature']) && file_exists(WRITEPATH . '../public/uploads/signatures/' . $event['organizer_signature'])) {
                unlink(WRITEPATH . '../public/uploads/signatures/' . $event['organizer_signature']);
            }
            $signatureFileName = $organizerSignature->getRandomName();
            $organizerSignature->move(WRITEPATH . '../public/uploads/signatures/', $signatureFileName);
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'location' => $this->request->getPost('location'),
            'max_participants' => $this->request->getPost('max_participants') === '' ? null : $this->request->getPost('max_participants'),
            'institution_name' => $this->request->getPost('institution_name'),
            'organizer_name' => $this->request->getPost('organizer_name'),
            'organizer_role' => $this->request->getPost('organizer_role'),
            'certificate_number' => $this->request->getPost('certificate_number'),
            'institution_logo' => $logoFileName,
            'organizer_signature' => $signatureFileName,
        ];

        $model->update($id, $data);

        return redirect()->to('/events')->with('success', 'Event berhasil diupdate');
    }

    public function delete(int $id)
    {
        $this->checkAdmin();

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/no_access');
        }

        $model = new EventModel();
        $event = $model->find($id);

        if (! $event) {
            return redirect()->to('/events')->with('error', 'Event tidak ditemukan.');
        }

        $model->delete($id);
        return redirect()->to('/events')->with('success', 'Event berhasil dihapus.');
    }

    public function view(int $id): \CodeIgniter\HTTP\RedirectResponse|string
    {
        $model = new EventModel();
        $event = $model->find($id);

        if (! $event) {
            return redirect()->to('/events')->with('error', 'Event tidak ditemukan.');
        }

        $event['current_participants'] = (new ParticipantModel())->where('event_id', $event['id'])->countAllResults(); // Get current participants

        $data = [
            'title' => 'Detail Event: ' . $event['title'],
            'page' => 'events/view',
            'event' => $event, // Pass the event data (now includes current_participants)
        ];

        return view('layout/main', $data);
    }

    public function participants(int $id): string
    {
        $model = new ParticipantModel();
        $participants = $model->where('event_id', $id)->findAll();

        $data = [
            'title' => 'Daftar Peserta - BikinEvent.my.id',
            'page' => 'events/participants',
            'participants' => $participants,
        ];

        return view('layout/main', $data);
    }

    public function participantIndex(): string
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $eventModel = new EventModel();
        $participantModel = new ParticipantModel();

        $events = $eventModel->findAll();

        // Fetch participant count for each event
        foreach ($events as &$event) {
            $event['current_participants'] = $participantModel->where('event_id', $event['id'])->countAllResults();
        }
        unset($event);

        $registeredEventIds = $participantModel
            ->where('user_id', session()->get('id'))
            ->findColumn('event_id');

        $data = [
            'title' => 'Daftar Event - BikinEvent.my.id',
            'page' => 'events/participant_index',
            'events' => $events,
            'registeredEventIds' => $registeredEventIds ?? [],
        ];

        return view('layout/main', $data);
    }

    public function register(?int $id = null): \CodeIgniter\HTTP\RedirectResponse|string
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if ($id === null) {
            // Display the general registration page with a list of events
            $eventModel = new EventModel();
            $events = $eventModel->findAll();

            $data = [
                'title' => 'Daftar Event - BikinEvent.my.id',
                'page' => 'events/register',
                'events' => $events, // Pass all events to the view
            ];
            return view('layout/main', $data);
        } else {
            // Handle registration for a specific event
            $model = new EventModel();
            $event = $model->find($id);

            if (! $event) {
                return redirect()->to('/events')->with('error', 'Event tidak ditemukan.');
            }

            $data = [
                'title' => 'Registrasi Event: ' . $event['title'],
                'page' => 'events/register',
                'event' => $event, // Pass the specific event data
                'event_id' => $id, // Pass the event ID
            ];

            return view('layout/main', $data);
        }
    }

    public function certificate(): string
    {
        $participantModel = new ParticipantModel();
        $userId = session()->get('id');

        // Hanya tampilkan daftar event untuk download sertifikat
        $registeredEvents = $participantModel
            ->select('events.*')
            ->join('events', 'events.id = participants.event_id')
            ->where('participants.user_id', $userId)
            ->get()
            ->getResultArray();

        $currentTime = date('Y-m-d H:i:s');
        log_message('debug', 'Current Time for Certificate Query: ' . $currentTime);
        log_message('debug', 'SQL Query for All Registered Events: ' . $participantModel->getLastQuery());

        // Tambahkan status 'is_completed' ke setiap event
        foreach ($registeredEvents as &$event) {
            $event['is_completed'] = (strtotime($event['end_date']) < strtotime($currentTime));
            log_message('debug', 'Event: ' . $event['title'] . ', EndDate: ' . $event['end_date'] . ', IsCompleted: ' . ($event['is_completed'] ? 'true' : 'false'));
        }
        unset($event); // Unset reference to last element

        $data = [
            'title' => 'Download Sertifikat',
            'page' => 'events/certificate_selection',
            'events' => $registeredEvents,
        ];

        return view('layout/main', $data);
    }

    // FUNGSI BARU UNTUK DOWNLOAD PDF
    public function downloadCertificate(int $id)
    {
        $eventModel = new EventModel();
        $participantModel = new ParticipantModel();
        $userId = session()->get('id');

        $event = $eventModel->find($id);
        if (!$event) {
            return redirect()->to('/events')->with('error', 'Event tidak ditemukan.');
        }

        $isRegistered = $participantModel->where('event_id', $id)->where('user_id', $userId)->countAllResults(false) > 0;
        if (!$isRegistered) {
            return redirect()->to('/events')->with('error', 'Anda tidak terdaftar di event ini.');
        }

        if (strtotime($event['end_date']) > time()) {
            return redirect()->to('/events')->with('error', 'Sertifikat hanya tersedia setelah event selesai.');
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);

        // Create new PDF document
        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('BikinEvent.my.id');
        $pdf->SetAuthor('BikinEvent.my.id');
        $pdf->SetTitle('Sertifikat Keikutsertaan - ' . $event['title']);

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set auto page breaks enabled, but set the bottom margin to 0 for full control
        $pdf->SetAutoPageBreak(false, 0);

        // Add a page
        $pdf->AddPage();

        // Set content area margins (effectively the internal padding of the certificate)
        $pageWidth = $pdf->getPageWidth();
        $pageHeight = $pdf->getPageHeight();

        // === ENHANCED BACKGROUND DECORATIVE ELEMENTS ===

        // 1. Elegant gradient background effect
        $this->addGradientBackground($pdf, $pageWidth, $pageHeight);

        // 2. Enhanced corner ornaments (batik-inspired)
        $this->addCornerOrnaments($pdf, $pageWidth, $pageHeight);

        // 3. Sophisticated watermark pattern
        $this->addWatermarkPattern($pdf, $pageWidth, $pageHeight);

        // 4. Decorative side borders with Indonesian motifs
        $this->addSideBorders($pdf, $pageWidth, $pageHeight);

        // 5. NEW: Elegant frame decorations
        $this->addFrameDecorations($pdf, $pageWidth, $pageHeight);

        // 6. NEW: Traditional pattern borders
        $this->addTraditionalBorders($pdf, $pageWidth, $pageHeight);

        // Main border (outermost) - Dark blue/grey
        $borderMargin = 8; // 8mm from edge of A4
        $pdf->SetLineStyle(array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(44, 62, 80))); // Dark Blue/Grey
        $pdf->Rect($borderMargin, $borderMargin, $pageWidth - (2 * $borderMargin), $pageHeight - (2 * $borderMargin));

        // Accent border (inside main border) - Gold
        $accentBorderMargin = $borderMargin + 6; // 6mm inside main border
        $pdf->SetLineStyle(array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(212, 175, 55))); // Gold
        $pdf->Rect($accentBorderMargin, $accentBorderMargin, $pageWidth - (2 * $accentBorderMargin), $pageHeight - (2 * $accentBorderMargin));

        // Content Area
        $contentX = $accentBorderMargin + 5; // 5mm inside accent border
        $contentY = $accentBorderMargin + 5; // 5mm inside accent border
        $contentWidth = $pageWidth - (2 * $contentX);
        $contentHeight = $pageHeight - (2 * $contentY);

        // Institution Logo
        if (!empty($event['institution_logo']) && file_exists(FCPATH . 'uploads/institutions/' . $event['institution_logo'])) {
            $logoPath = FCPATH . 'uploads/institutions/' . $event['institution_logo'];
            $pdf->Image($logoPath, $pageWidth / 2 - 12, $contentY + 5, 24, 0, '', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->SetY($contentY + 30);
        } else {
            $pdf->SetY($contentY + 8);
        }

        // Institution Name
        if (!empty($event['institution_name'])) {
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->SetTextColor(44, 62, 80);
            $pdf->Cell(0, 6, strtoupper($event['institution_name']), 0, 1, 'C');
            $pdf->SetY($pdf->GetY() + 4);
        }

        // Title with enhanced decorative elements
        $titleY = $pdf->GetY();
        $this->addTitleDecorations($pdf, $pageWidth, $titleY);

        $pdf->SetFont('helvetica', 'B', 28);
        $pdf->SetTextColor(212, 175, 55); // Gold color
        $pdf->Cell(0, 8, 'SERTIFIKAT', 0, 1, 'C');

        // Enhanced decorative line under title
        $lineY = $pdf->GetY() + 2;
        $this->addDecorativeLine($pdf, $pageWidth, $lineY);
        $pdf->SetY($pdf->GetY() + 8);

        // Certificate Number
        if (!empty($event['certificate_number'])) {
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(127, 140, 141);
            $pdf->Cell(0, 6, 'No. Sertifikat: ' . $event['certificate_number'], 0, 1, 'C');
            $pdf->SetY($pdf->GetY() + 3);
        }

        // Given to text
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(52, 73, 94); // Darker Grey
        $pdf->SetY($pdf->GetY() + 8);
        $pdf->Cell(0, 6, 'Diberikan kepada:', 0, 1, 'C');

        // Participant Name
        $pdf->SetFont('helvetica', 'B', 22);
        $pdf->SetTextColor(231, 76, 60); // Red
        $pdf->SetY($pdf->GetY() + 4);
        $pdf->Cell(0, 8, strtoupper($user['name']), 0, 1, 'C');
        // Decorative underline for name
        $nameWidth = $pdf->GetStringWidth(strtoupper($user['name']));
        $pdf->SetFont('helvetica', 'B', 22); // Reset font for proper width calculation
        $nameWidth = $pdf->GetStringWidth(strtoupper($user['name']));
        $pdf->SetLineStyle(array('width' => 1.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(212, 175, 55)));
        $pdf->Line($pageWidth / 2 - $nameWidth / 2, $pdf->GetY() - 2, $pageWidth / 2 + $nameWidth / 2, $pdf->GetY() - 2);

        // For participation text
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(52, 73, 94); // Darker Grey
        $pdf->SetY($pdf->GetY() + 8);
        $pdf->Cell(0, 6, 'Atas partisipasinya sebagai peserta dalam:', 0, 1, 'C');

        // Event Title
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(44, 62, 80); // Dark Blue/Grey
        $pdf->SetY($pdf->GetY() + 4);
        $pdf->MultiCell(0, 6, strtoupper($event['title']), 0, 'C', 0, 1, '', '', true, 0, false, true, 0, 'T', false);

        // Event Description
        if (!empty($event['description'])) {
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(52, 73, 94);
            $pdf->SetY($pdf->GetY() + 4);
            $pdf->MultiCell(0, 4, $event['description'], 0, 'C', 0, 1, '', '', true, 0, false, true, 0, 'T', false);
        }

        // Event Details
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(127, 140, 141); // Grey
        $pdf->SetY($pdf->GetY() + 6);
        $pdf->Cell(0, 5, 'Tanggal: ' . date('d F Y', strtotime($event['end_date'])), 0, 1, 'C');
        $pdf->Cell(0, 5, 'Lokasi: ' . htmlspecialchars($event['location']), 0, 1, 'C');

        // Signature Section - Professional layout with proper positioning
        $signatureBlockWidth = 80; // Wider block for better layout
        $signatureStartY = $pageHeight - $accentBorderMargin - 55; // Fixed position from bottom
        $signatureX = $contentX + $contentWidth - $signatureBlockWidth - 10; // Right aligned with margin

        // Date and location
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(44, 62, 80);
        $pdf->SetXY($signatureX, $signatureStartY);
        $pdf->Cell($signatureBlockWidth, 5, htmlspecialchars($event['location']) . ', ' . date('d F Y', strtotime($event['end_date'])), 0, 1, 'C');

        // Role text
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor(44, 62, 80);
        $pdf->SetXY($signatureX, $pdf->GetY() + 3);
        $organizerRole = !empty($event['organizer_role']) ? $event['organizer_role'] : 'Ketua Penyelenggara';
        $pdf->Cell($signatureBlockWidth, 5, $organizerRole, 0, 1, 'C');

        // Signature image - ABOVE the name line
        $signatureY = $pdf->GetY() + 5;
        if (!empty($event['organizer_signature']) && file_exists(FCPATH . 'uploads/signatures/' . $event['organizer_signature'])) {
            $signaturePath = FCPATH . 'uploads/signatures/' . $event['organizer_signature'];

            // Get image dimensions to maintain aspect ratio
            $imageInfo = getimagesize($signaturePath);
            if ($imageInfo) {
                $imgWidth = $imageInfo[0];
                $imgHeight = $imageInfo[1];
                $aspectRatio = $imgWidth / $imgHeight;

                // Set max dimensions
                $maxWidth = 35;
                $maxHeight = 20;

                // Calculate final dimensions maintaining aspect ratio
                if ($aspectRatio > ($maxWidth / $maxHeight)) {
                    // Image is wider
                    $finalWidth = $maxWidth;
                    $finalHeight = $maxWidth / $aspectRatio;
                } else {
                    // Image is taller
                    $finalHeight = $maxHeight;
                    $finalWidth = $maxHeight * $aspectRatio;
                }

                // Center the signature image
                $imgX = $signatureX + ($signatureBlockWidth / 2) - ($finalWidth / 2);
                $pdf->Image($signaturePath, $imgX, $signatureY, $finalWidth, $finalHeight, '', '', '', false, 300, '', false, false, 0, false, false, false);
                $pdf->SetY($signatureY + $finalHeight + 3);
            } else {
                // Fallback if can't get image info
                $pdf->Image($signaturePath, $signatureX + ($signatureBlockWidth / 2) - 15, $signatureY, 30, 0, '', '', '', false, 300, '', false, false, 0, false, false, false);
                $pdf->SetY($signatureY + 20);
            }
        } else {
            // Space for manual signature
            $pdf->SetY($signatureY + 20);
        }

        // Underline for name
        $pdf->SetLineStyle(array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(44, 62, 80)));
        $pdf->Line($signatureX + 10, $pdf->GetY(), $signatureX + $signatureBlockWidth - 10, $pdf->GetY());

        // Name text
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor(44, 62, 80);
        $pdf->SetXY($signatureX, $pdf->GetY() + 3);
        $organizerName = !empty($event['organizer_name']) ? $event['organizer_name'] : 'Nama Ketua Penyelenggara';
        $pdf->Cell($signatureBlockWidth, 5, $organizerName, 0, 1, 'C');

        // Clean any output that might corrupt PDF
        ob_clean();

        // Output PDF
        $filename = 'Sertifikat_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $event['title']) . '_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $user['name']) . '.pdf';
        $pdf->Output($filename, 'D');

        exit;
    }

    public function previewCertificateById($eventId)
    {
        // Check admin access
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $eventModel = new EventModel();

        // Get event data
        $event = $eventModel->find($eventId);
        if (!$event) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Event tidak ditemukan');
        }

        // Create dummy participant data for preview
        $dummyUser = [
            'id' => 999,
            'name' => 'Contoh Peserta',
            'email' => 'contoh@bikinevent.my.id'
        ];

        // Generate preview certificate using existing method
        return $this->generateCertificateForPreview($event, $dummyUser);
    }

    private function generateCertificateForPreview($event, $user)
    {
        // Load TCPDF library
        $pdf = new \TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('BikinEvent.my.id');
        $pdf->SetAuthor('BikinEvent.my.id');
        $pdf->SetTitle('Preview Sertifikat - ' . $event['title']);
        $pdf->SetSubject('Certificate Preview');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false, 0);

        // Add a page
        $pdf->AddPage();

        // Set content area margins
        $pageWidth = $pdf->getPageWidth();
        $pageHeight = $pdf->getPageHeight();

        // === BACKGROUND DECORATIVE ELEMENTS ===

        // 1. Subtle gradient background effect
        $pdf->SetFillColor(252, 252, 255);
        $pdf->Rect(0, 0, $pageWidth, $pageHeight, 'F');

        // 2. Corner ornaments (batik-inspired)
        $this->addCornerOrnaments($pdf, $pageWidth, $pageHeight);

        // 3. Watermark pattern
        $this->addWatermarkPattern($pdf, $pageWidth, $pageHeight);

        // 4. Decorative side borders
        $this->addSideBorders($pdf, $pageWidth, $pageHeight);

        // Main border (outermost) - Dark blue/grey
        $borderMargin = 8;
        $pdf->SetLineStyle(array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(44, 62, 80)));
        $pdf->Rect($borderMargin, $borderMargin, $pageWidth - (2 * $borderMargin), $pageHeight - (2 * $borderMargin));

        // Accent border (inside main border) - Gold
        $accentBorderMargin = $borderMargin + 6;
        $pdf->SetLineStyle(array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(212, 175, 55)));
        $pdf->Rect($accentBorderMargin, $accentBorderMargin, $pageWidth - (2 * $accentBorderMargin), $pageHeight - (2 * $accentBorderMargin));

        // Content Area
        $contentX = $accentBorderMargin + 5;
        $contentY = $accentBorderMargin + 5;
        $contentWidth = $pageWidth - (2 * $contentX);

        // Institution Logo (if available)
        if (!empty($event['institution_logo'])) {
            $logoPath = FCPATH . 'uploads/institutions/' . $event['institution_logo'];
            if (file_exists($logoPath)) {
                $pdf->Image($logoPath, $contentX + 10, $contentY + 10, 25, 25, '', '', '', false, 300, '', false, false, 0);
            }
        }

        // Institution Name
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(44, 62, 80);
        $pdf->SetXY($contentX + 45, $contentY + 15);
        $pdf->Cell(0, 8, strtoupper($event['institution_name'] ?? 'BIKINEVENT.MY.ID'), 0, 1, 'L');

        // Institution Details
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->SetXY($contentX + 45, $contentY + 25);
        $pdf->Cell(0, 5, $event['organizer_name'] ?? 'Penyelenggara Event', 0, 1, 'L');

        // Title with enhanced decorative elements
        $pdf->SetY($contentY + 50);
        $titleY = $pdf->GetY();
        $this->addTitleDecorations($pdf, $pageWidth, $titleY);

        $pdf->SetFont('helvetica', 'B', 28);
        $pdf->SetTextColor(212, 175, 55);
        $pdf->Cell(0, 8, 'SERTIFIKAT', 0, 1, 'C');

        // Enhanced decorative line under title
        $lineY = $pdf->GetY() + 2;
        $this->addDecorativeLine($pdf, $pageWidth, $lineY);
        $pdf->SetY($pdf->GetY() + 8);

        // Certificate text
        $pdf->SetFont('helvetica', '', 14);
        $pdf->SetTextColor(44, 62, 80);
        $pdf->Cell(0, 8, 'Diberikan kepada:', 0, 1, 'C');
        $pdf->Ln(5);

        // Participant name with PREVIEW label
        $pdf->SetFont('helvetica', 'B', 24);
        $pdf->SetTextColor(212, 175, 55);
        $pdf->Cell(0, 12, strtoupper($user['name'] . ' (PREVIEW)'), 0, 1, 'C');
        $pdf->Ln(5);

        // Event participation text
        $pdf->SetFont('helvetica', '', 12);
        $pdf->SetTextColor(44, 62, 80);
        $pdf->Cell(0, 6, 'Atas partisipasinya dalam kegiatan:', 0, 1, 'C');
        $pdf->Ln(3);

        // Event title
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(44, 62, 80);
        $pdf->Cell(0, 10, '"' . strtoupper($event['title']) . '"', 0, 1, 'C');
        $pdf->Ln(3);

        // Event details
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(100, 100, 100);

        // Date
        $eventDate = date('d', strtotime($event['end_date'])) . ' ' .
            $this->getIndonesianMonth(date('n', strtotime($event['end_date']))) . ' ' .
            date('Y', strtotime($event['end_date']));
        $pdf->Cell(0, 6, 'yang diselenggarakan pada tanggal ' . $eventDate, 0, 1, 'C');

        // Location
        $pdf->Cell(0, 6, 'di ' . $event['location'], 0, 1, 'C');
        $pdf->Ln(15);

        // Signatures section
        $signatureY = $pdf->GetY();
        $leftSignatureX = $contentX + 50;
        $rightSignatureX = $contentX + $contentWidth - 100;

        // Left signature (Chairman)
        $pdf->SetXY($leftSignatureX, $signatureY);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(50, 6, $event['location'] . ', ' . $eventDate, 0, 1, 'C');

        // Chairman signature image
        if (!empty($event['chairman_signature'])) {
            $chairmanSigPath = FCPATH . 'uploads/signatures/' . $event['chairman_signature'];
            if (file_exists($chairmanSigPath)) {
                $pdf->Image($chairmanSigPath, $leftSignatureX + 5, $signatureY + 8, 40, 20, '', '', '', false, 300, '', false, false, 0);
            }
        }

        $pdf->SetXY($leftSignatureX, $signatureY + 30);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetTextColor(44, 62, 80);
        $pdf->Cell(50, 6, $event['chairman_name'] ?? 'Ketua Panitia', 0, 1, 'C');
        $pdf->SetXY($leftSignatureX, $signatureY + 36);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(50, 6, 'Ketua Panitia', 0, 1, 'C');

        // Right signature (Organizer)
        $pdf->SetXY($rightSignatureX, $signatureY);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(50, 6, '', 0, 1, 'C');

        // Organizer signature image
        if (!empty($event['organizer_signature'])) {
            $organizerSigPath = FCPATH . 'uploads/signatures/' . $event['organizer_signature'];
            if (file_exists($organizerSigPath)) {
                $pdf->Image($organizerSigPath, $rightSignatureX + 5, $signatureY + 8, 40, 20, '', '', '', false, 300, '', false, false, 0);
            }
        }

        $pdf->SetXY($rightSignatureX, $signatureY + 30);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetTextColor(44, 62, 80);
        $pdf->Cell(50, 6, $event['organizer_name'] ?? 'Penyelenggara', 0, 1, 'C');
        $pdf->SetXY($rightSignatureX, $signatureY + 36);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(50, 6, 'Penyelenggara', 0, 1, 'C');

        // Preview watermark
        $pdf->SetAlpha(0.3);
        $pdf->SetFont('helvetica', 'B', 60);
        $pdf->SetTextColor(255, 0, 0);
        $pdf->SetXY(0, $pageHeight / 2 - 20);
        $pdf->Cell($pageWidth, 20, 'PREVIEW', 0, 0, 'C');
        $pdf->SetAlpha(1);

        // Set proper headers for PDF display
        $filename = 'Preview_Sertifikat_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $event['title']) . '.pdf';

        // Clear any previous output
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Set headers for PDF display in browser
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        // Output PDF
        $pdf->Output($filename, 'I'); // 'I' = display in browser
        exit;
    }

    private function getIndonesianMonth($monthNumber)
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $months[$monthNumber] ?? '';
    }

    // New method for certificate preview
    public function previewCertificate()
    {
        $request = \Config\Services::request();
        $data = $request->getPost();

        // Prepare event data for the certificate view
        $eventData = [
            'title'             => $data['title'] ?? 'Judul Event Preview',
            'description'       => $data['description'] ?? 'Deskripsi Event Preview',
            'start_date'        => $data['start_date'] ?? date('Y-m-d H:i:s'),
            'end_date'          => $data['end_date'] ?? date('Y-m-d H:i:s'),
            'location'          => $data['location'] ?? 'Lokasi Preview',
            'institution_name'  => $data['institution_name'] ?? 'NAMA INSTANSI PREVIEW',
            'certificate_number' => $data['certificate_number'] ?? '000/PREVIEW/TAHUN',
            'organizer_name'    => $data['organizer_name'] ?? 'Nama Ketua Pelaksana Preview',
            'organizer_role'    => $data['organizer_role'] ?? 'Jabatan Ketua Pelaksana Preview',
            'institution_logo'  => base_url('/images/placeholder-logo.png'), // Default placeholder
            'organizer_signature' => base_url('/images/placeholder-qr.png'), // Default placeholder
        ];

        // Define upload path for temporary preview files
        $uploadPath = FCPATH . 'uploads/temp/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Handle logo upload for preview
        $logoFile = $this->request->getFile('institution_logo');
        if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
            try {
                $logoName = $logoFile->getRandomName();
                $logoFile->move($uploadPath, $logoName);
                $eventData['institution_logo'] = base_url('uploads/temp/' . $logoName);
            } catch (\Exception $e) {
                log_message('error', 'Preview logo upload failed: ' . $e->getMessage());
            }
        }

        // Handle signature upload for preview
        $signatureFile = $this->request->getFile('organizer_signature');
        if ($signatureFile && $signatureFile->isValid() && !$signatureFile->hasMoved()) {
            try {
                $signatureName = $signatureFile->getRandomName();
                $signatureFile->move($uploadPath, $signatureName);
                $eventData['organizer_signature'] = base_url('uploads/temp/' . $signatureName);
            } catch (\Exception $e) {
                log_message('error', 'Preview signature upload failed: ' . $e->getMessage());
            }
        }

        // Dummy user data for preview (assuming 'name' is the only needed field from user)
        $userData = [
            'name' => 'NAMA PESERTA DEMO', // A generic name for preview
        ];

        $viewData = [
            'event' => $eventData,
            'user' => $userData,
        ];

        // Load and return the certificate view HTML
        return view('pages/events/certificate_print', $viewData);
    }

    // === DECORATIVE METHODS FOR BEAUTIFUL CERTIFICATE ===

    private function addCornerOrnaments($pdf, $pageWidth, $pageHeight)
    {
        // Batik-inspired corner ornaments
        $pdf->SetDrawColor(212, 175, 55); // Gold color
        $pdf->SetLineWidth(0.5);

        $ornamentSize = 15;
        $margin = 12;

        // Top-left ornament
        $this->drawBatikOrnament($pdf, $margin, $margin, $ornamentSize);

        // Top-right ornament
        $this->drawBatikOrnament($pdf, $pageWidth - $margin - $ornamentSize, $margin, $ornamentSize);

        // Bottom-left ornament
        $this->drawBatikOrnament($pdf, $margin, $pageHeight - $margin - $ornamentSize, $ornamentSize);

        // Bottom-right ornament
        $this->drawBatikOrnament($pdf, $pageWidth - $margin - $ornamentSize, $pageHeight - $margin - $ornamentSize, $ornamentSize);
    }

    private function drawBatikOrnament($pdf, $x, $y, $size)
    {
        // Create intricate batik-like pattern
        $centerX = $x + $size / 2;
        $centerY = $y + $size / 2;
        $radius = $size / 3;

        // Central circle
        $pdf->Circle($centerX, $centerY, $radius / 2, 'D');

        // Surrounding petals
        for ($i = 0; $i < 8; $i++) {
            $angle = $i * 45 * M_PI / 180;
            $petalX = $centerX + cos($angle) * $radius;
            $petalY = $centerY + sin($angle) * $radius;
            $pdf->Circle($petalX, $petalY, $radius / 4, 'D');
        }

        // Outer decorative lines
        for ($i = 0; $i < 4; $i++) {
            $angle = $i * 90 * M_PI / 180;
            $startX = $centerX + cos($angle) * $radius * 0.7;
            $startY = $centerY + sin($angle) * $radius * 0.7;
            $endX = $centerX + cos($angle) * $radius * 1.3;
            $endY = $centerY + sin($angle) * $radius * 1.3;
            $pdf->Line($startX, $startY, $endX, $endY);
        }
    }

    private function addWatermarkPattern($pdf, $pageWidth, $pageHeight)
    {
        // Subtle watermark pattern across the certificate
        $pdf->SetAlpha(0.03); // Very transparent
        $pdf->SetDrawColor(212, 175, 55); // Gold
        $pdf->SetLineWidth(0.2);

        // Create diamond/rhombus pattern
        $spacing = 20;
        for ($x = 0; $x < $pageWidth; $x += $spacing) {
            for ($y = 0; $y < $pageHeight; $y += $spacing) {
                $this->drawDiamond($pdf, $x, $y, 8);
            }
        }

        $pdf->SetAlpha(1); // Reset transparency
    }

    private function drawDiamond($pdf, $x, $y, $size)
    {
        $halfSize = $size / 2;
        $points = array(
            $x,
            $y - $halfSize,      // Top
            $x + $halfSize,
            $y,      // Right
            $x,
            $y + $halfSize,      // Bottom
            $x - $halfSize,
            $y       // Left
        );

        $pdf->Polygon($points, 'D');
    }

    private function addSideBorders($pdf, $pageWidth, $pageHeight)
    {
        // Decorative side borders with Indonesian-inspired patterns
        $pdf->SetDrawColor(212, 175, 55); // Gold
        $pdf->SetLineWidth(0.3);

        $borderWidth = 8;
        $leftX = 20;
        $rightX = $pageWidth - 20 - $borderWidth;

        // Left border pattern
        $this->drawVerticalPattern($pdf, $leftX, 30, $borderWidth, $pageHeight - 60);

        // Right border pattern
        $this->drawVerticalPattern($pdf, $rightX, 30, $borderWidth, $pageHeight - 60);

        // Top and bottom decorative lines
        $this->drawHorizontalPattern($pdf, 30, 25, $pageWidth - 60, 5);
        $this->drawHorizontalPattern($pdf, 30, $pageHeight - 30, $pageWidth - 60, 5);
    }

    private function drawVerticalPattern($pdf, $x, $y, $width, $height)
    {
        $spacing = 8;
        $currentY = $y;

        while ($currentY < $y + $height - $spacing) {
            // Draw traditional Indonesian motif elements
            $centerX = $x + $width / 2;

            // Small decorative elements
            $pdf->Circle($centerX, $currentY, 1, 'D');
            $pdf->Line($x + 2, $currentY + 2, $x + $width - 2, $currentY + 2);
            $pdf->Line($x + 2, $currentY + 4, $x + $width - 2, $currentY + 4);

            $currentY += $spacing;
        }
    }

    private function drawHorizontalPattern($pdf, $x, $y, $width, $height)
    {
        $spacing = 12;
        $currentX = $x;

        while ($currentX < $x + $width - $spacing) {
            // Traditional pattern elements
            $centerY = $y + $height / 2;

            // Small decorative shapes
            $pdf->Rect($currentX, $y + 1, 2, $height - 2, 'D');
            $pdf->Circle($currentX + 4, $centerY, 1, 'D');
            $pdf->Line($currentX + 6, $y + 1, $currentX + 8, $y + $height - 1);

            $currentX += $spacing;
        }
    }

    private function addTitleDecorations($pdf, $pageWidth, $titleY)
    {
        // Add decorative flourishes around the title
        $pdf->SetDrawColor(212, 175, 55); // Gold
        $pdf->SetLineWidth(0.8);

        $centerX = $pageWidth / 2;
        $decorSize = 25;

        // Left side flourish
        $leftX = $centerX - 80;
        $this->drawFlourish($pdf, $leftX, $titleY + 4, $decorSize, 'left');

        // Right side flourish
        $rightX = $centerX + 80;
        $this->drawFlourish($pdf, $rightX, $titleY + 4, $decorSize, 'right');
    }

    private function drawFlourish($pdf, $x, $y, $size, $direction)
    {
        // Create elegant flourish design
        $factor = ($direction === 'left') ? -1 : 1;

        // Main curved line
        $pdf->SetLineWidth(1.2);
        $startX = $x;
        $endX = $x + ($factor * $size);
        $controlX = $x + ($factor * $size / 2);
        $controlY = $y - 8;

        // Draw curved line using multiple small segments
        $prevX = $startX;
        $prevY = $y;

        for ($i = 1; $i <= 20; $i++) {
            $t = $i / 20;
            $currentX = (1 - $t) * (1 - $t) * $startX + 2 * (1 - $t) * $t * $controlX + $t * $t * $endX;
            $currentY = (1 - $t) * (1 - $t) * $y + 2 * (1 - $t) * $t * $controlY + $t * $t * $y;

            $pdf->Line($prevX, $prevY, $currentX, $currentY);

            $prevX = $currentX;
            $prevY = $currentY;
        }

        // Add small decorative elements
        $pdf->SetLineWidth(0.5);
        for ($i = 0; $i < 3; $i++) {
            $dotX = $x + ($factor * ($i + 1) * 8);
            $dotY = $y + ($i * 2);
            $pdf->Circle($dotX, $dotY, 0.8, 'D');
        }
    }

    private function addDecorativeLine($pdf, $pageWidth, $lineY)
    {
        // Create an ornate decorative line
        $centerX = $pageWidth / 2;
        $lineLength = 60;
        $startX = $centerX - $lineLength / 2;
        $endX = $centerX + $lineLength / 2;

        // Main line
        $pdf->SetLineWidth(2);
        $pdf->SetDrawColor(212, 175, 55); // Gold
        $pdf->Line($startX + 15, $lineY, $endX - 15, $lineY);

        // Decorative ends
        $pdf->SetLineWidth(1);

        // Left end decoration
        $this->drawLineEndDecoration($pdf, $startX, $lineY, 'left');

        // Right end decoration
        $this->drawLineEndDecoration($pdf, $endX, $lineY, 'right');

        // Center ornament
        $this->drawCenterOrnament($pdf, $centerX, $lineY);
    }

    private function drawLineEndDecoration($pdf, $x, $y, $direction)
    {
        $factor = ($direction === 'left') ? 1 : -1;

        // Create decorative end with curves
        $pdf->Line($x, $y, $x + ($factor * 8), $y - 3);
        $pdf->Line($x, $y, $x + ($factor * 8), $y + 3);
        $pdf->Line($x + ($factor * 8), $y - 3, $x + ($factor * 15), $y);
        $pdf->Line($x + ($factor * 8), $y + 3, $x + ($factor * 15), $y);

        // Small decorative circle
        $pdf->Circle($x + ($factor * 12), $y, 1.5, 'D');
    }

    private function drawCenterOrnament($pdf, $x, $y)
    {
        // Central decorative element
        $pdf->SetLineWidth(0.8);

        // Diamond shape
        $size = 4;
        $points = array(
            $x,
            $y - $size,      // Top
            $x + $size,
            $y,      // Right
            $x,
            $y + $size,      // Bottom
            $x - $size,
            $y       // Left
        );
        $pdf->Polygon($points, 'D');

        // Inner circle
        $pdf->Circle($x, $y, 2, 'D');

        // Radiating lines
        for ($i = 0; $i < 4; $i++) {
            $angle = $i * 90 * M_PI / 180;
            $startX = $x + cos($angle) * 3;
            $startY = $y + sin($angle) * 3;
            $endX = $x + cos($angle) * 6;
            $endY = $y + sin($angle) * 6;
            $pdf->Line($startX, $startY, $endX, $endY);
        }
    }

    // === NEW ENHANCED DECORATIVE METHODS ===

    private function addGradientBackground($pdf, $pageWidth, $pageHeight)
    {
        // Create elegant gradient effect with multiple layers
        for ($i = 0; $i < 5; $i++) {
            $alpha = 0.02 + ($i * 0.005); // Very subtle gradient
            $color = 250 - ($i * 2); // Slight color variation

            $pdf->SetAlpha($alpha);
            $pdf->SetFillColor($color, $color, 255);
            $pdf->Rect($i * 2, $i * 2, $pageWidth - ($i * 4), $pageHeight - ($i * 4), 'F');
        }
        $pdf->SetAlpha(1); // Reset transparency
    }

    private function addFrameDecorations($pdf, $pageWidth, $pageHeight)
    {
        // Elegant frame decorations at corners and sides
        $pdf->SetDrawColor(212, 175, 55); // Gold
        $pdf->SetLineWidth(0.8);

        $frameSize = 20;
        $margin = 15;

        // Top-left frame
        $this->drawFrameCorner($pdf, $margin, $margin, $frameSize, 'top-left');

        // Top-right frame
        $this->drawFrameCorner($pdf, $pageWidth - $margin - $frameSize, $margin, $frameSize, 'top-right');

        // Bottom-left frame
        $this->drawFrameCorner($pdf, $margin, $pageHeight - $margin - $frameSize, $frameSize, 'bottom-left');

        // Bottom-right frame
        $this->drawFrameCorner($pdf, $pageWidth - $margin - $frameSize, $pageHeight - $margin - $frameSize, $frameSize, 'bottom-right');

        // Side decorations
        $this->addSideDecorations($pdf, $pageWidth, $pageHeight);
    }

    private function drawFrameCorner($pdf, $x, $y, $size, $position)
    {
        $pdf->SetLineWidth(1.2);

        switch ($position) {
            case 'top-left':
                // Elegant corner design
                $pdf->Line($x, $y + $size, $x, $y + $size / 3);
                $pdf->Line($x, $y + $size / 3, $x + $size / 3, $y);
                $pdf->Line($x + $size / 3, $y, $x + $size, $y);

                // Decorative curves
                $this->drawDecorativeCurve($pdf, $x + $size / 2, $y + $size / 2, $size / 3, 'top-left');
                break;

            case 'top-right':
                $pdf->Line($x + $size, $y + $size, $x + $size, $y + $size / 3);
                $pdf->Line($x + $size, $y + $size / 3, $x + $size * 2 / 3, $y);
                $pdf->Line($x + $size * 2 / 3, $y, $x, $y);

                $this->drawDecorativeCurve($pdf, $x + $size / 2, $y + $size / 2, $size / 3, 'top-right');
                break;

            case 'bottom-left':
                $pdf->Line($x, $y, $x, $y + $size * 2 / 3);
                $pdf->Line($x, $y + $size * 2 / 3, $x + $size / 3, $y + $size);
                $pdf->Line($x + $size / 3, $y + $size, $x + $size, $y + $size);

                $this->drawDecorativeCurve($pdf, $x + $size / 2, $y + $size / 2, $size / 3, 'bottom-left');
                break;

            case 'bottom-right':
                $pdf->Line($x + $size, $y, $x + $size, $y + $size * 2 / 3);
                $pdf->Line($x + $size, $y + $size * 2 / 3, $x + $size * 2 / 3, $y + $size);
                $pdf->Line($x + $size * 2 / 3, $y + $size, $x, $y + $size);

                $this->drawDecorativeCurve($pdf, $x + $size / 2, $y + $size / 2, $size / 3, 'bottom-right');
                break;
        }
    }

    private function drawDecorativeCurve($pdf, $x, $y, $radius, $position)
    {
        // Add small decorative elements
        $pdf->SetLineWidth(0.6);

        // Small circles and lines for decoration
        $pdf->Circle($x, $y, $radius / 4, 'D');

        // Radiating lines based on position
        for ($i = 0; $i < 4; $i++) {
            $angle = ($i * 45) * M_PI / 180;
            $startX = $x + cos($angle) * $radius / 3;
            $startY = $y + sin($angle) * $radius / 3;
            $endX = $x + cos($angle) * $radius / 2;
            $endY = $y + sin($angle) * $radius / 2;
            $pdf->Line($startX, $startY, $endX, $endY);
        }
    }

    private function addSideDecorations($pdf, $pageWidth, $pageHeight)
    {
        // Elegant side decorations
        $pdf->SetLineWidth(0.5);
        $pdf->SetDrawColor(212, 175, 55); // Gold

        $midHeight = $pageHeight / 2;
        $midWidth = $pageWidth / 2;

        // Left side decoration
        $this->drawSideOrnament($pdf, 25, $midHeight, 'left');

        // Right side decoration
        $this->drawSideOrnament($pdf, $pageWidth - 25, $midHeight, 'right');

        // Top center decoration
        $this->drawTopBottomOrnament($pdf, $midWidth, 25, 'top');

        // Bottom center decoration
        $this->drawTopBottomOrnament($pdf, $midWidth, $pageHeight - 25, 'bottom');
    }

    private function drawSideOrnament($pdf, $x, $y, $side)
    {
        $size = 8;
        $factor = ($side === 'left') ? 1 : -1;

        // Create elegant side ornament
        for ($i = 0; $i < 3; $i++) {
            $offsetY = ($i - 1) * $size;
            $currentY = $y + $offsetY;

            // Diamond shape
            $pdf->Line($x, $currentY - $size / 2, $x + ($factor * $size), $currentY);
            $pdf->Line($x + ($factor * $size), $currentY, $x, $currentY + $size / 2);
            $pdf->Line($x, $currentY + $size / 2, $x - ($factor * $size / 2), $currentY);
            $pdf->Line($x - ($factor * $size / 2), $currentY, $x, $currentY - $size / 2);

            // Small decorative dot
            $pdf->Circle($x, $currentY, 1, 'F');
        }
    }

    private function drawTopBottomOrnament($pdf, $x, $y, $position)
    {
        $size = 10;
        $factor = ($position === 'top') ? 1 : -1;

        // Create elegant top/bottom ornament
        for ($i = 0; $i < 3; $i++) {
            $offsetX = ($i - 1) * $size;
            $currentX = $x + $offsetX;

            // Leaf-like pattern
            $pdf->Line($currentX - $size / 2, $y, $currentX, $y + ($factor * $size));
            $pdf->Line($currentX, $y + ($factor * $size), $currentX + $size / 2, $y);
            $pdf->Line($currentX + $size / 2, $y, $currentX, $y - ($factor * $size / 2));
            $pdf->Line($currentX, $y - ($factor * $size / 2), $currentX - $size / 2, $y);

            // Small decorative circle
            $pdf->Circle($currentX, $y, 1.5, 'D');
        }
    }

    private function addTraditionalBorders($pdf, $pageWidth, $pageHeight)
    {
        // Traditional Indonesian-inspired border patterns
        $pdf->SetDrawColor(212, 175, 55); // Gold
        $pdf->SetLineWidth(0.4);

        $borderWidth = 12;
        $margin = 35;

        // Top border pattern
        $this->drawTraditionalPattern($pdf, $margin, $margin - 5, $pageWidth - (2 * $margin), $borderWidth, 'horizontal');

        // Bottom border pattern
        $this->drawTraditionalPattern($pdf, $margin, $pageHeight - $margin - 5, $pageWidth - (2 * $margin), $borderWidth, 'horizontal');

        // Left border pattern
        $this->drawTraditionalPattern($pdf, $margin - 5, $margin + 15, $borderWidth, $pageHeight - (2 * $margin) - 30, 'vertical');

        // Right border pattern
        $this->drawTraditionalPattern($pdf, $pageWidth - $margin - 5, $margin + 15, $borderWidth, $pageHeight - (2 * $margin) - 30, 'vertical');
    }

    private function drawTraditionalPattern($pdf, $x, $y, $width, $height, $orientation)
    {
        if ($orientation === 'horizontal') {
            // Horizontal traditional pattern
            $spacing = 15;
            $currentX = $x;

            while ($currentX < $x + $width - $spacing) {
                // Traditional motif elements
                $this->drawTraditionalMotif($pdf, $currentX, $y + $height / 2, 6, 'horizontal');
                $currentX += $spacing;
            }
        } else {
            // Vertical traditional pattern
            $spacing = 15;
            $currentY = $y;

            while ($currentY < $y + $height - $spacing) {
                // Traditional motif elements
                $this->drawTraditionalMotif($pdf, $x + $width / 2, $currentY, 6, 'vertical');
                $currentY += $spacing;
            }
        }
    }

    private function drawTraditionalMotif($pdf, $x, $y, $size, $orientation)
    {
        // Traditional Indonesian motif
        $pdf->SetLineWidth(0.3);

        if ($orientation === 'horizontal') {
            // Horizontal motif
            $pdf->Line($x - $size, $y, $x + $size, $y);
            $pdf->Line($x, $y - $size / 2, $x, $y + $size / 2);

            // Decorative ends
            $pdf->Circle($x - $size, $y, $size / 4, 'D');
            $pdf->Circle($x + $size, $y, $size / 4, 'D');

            // Central diamond
            $pdf->Line($x - $size / 2, $y - $size / 3, $x, $y);
            $pdf->Line($x, $y, $x + $size / 2, $y - $size / 3);
            $pdf->Line($x + $size / 2, $y - $size / 3, $x, $y - $size / 2);
            $pdf->Line($x, $y - $size / 2, $x - $size / 2, $y - $size / 3);
        } else {
            // Vertical motif
            $pdf->Line($x, $y - $size, $x, $y + $size);
            $pdf->Line($x - $size / 2, $y, $x + $size / 2, $y);

            // Decorative ends
            $pdf->Circle($x, $y - $size, $size / 4, 'D');
            $pdf->Circle($x, $y + $size, $size / 4, 'D');

            // Central diamond
            $pdf->Line($x - $size / 3, $y - $size / 2, $x, $y);
            $pdf->Line($x, $y, $x - $size / 3, $y + $size / 2);
            $pdf->Line($x - $size / 3, $y + $size / 2, $x - $size / 2, $y);
            $pdf->Line($x - $size / 2, $y, $x - $size / 3, $y - $size / 2);
        }
    }
}
