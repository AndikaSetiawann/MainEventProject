<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-certificate text-primary me-2"></i> Sertifikat</h5>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('/certificates/download/' . $certificate['id']) ?>" class="btn btn-success">
                                <i class="fas fa-download me-2"></i> Download
                            </a>
                            <a href="<?= base_url('/certificates/print/' . $certificate['id']) ?>" class="btn btn-primary" target="_blank">
                                <i class="fas fa-print me-2"></i> Cetak
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="certificate-container">
                        <div class="certificate">
                            <div class="certificate-header">
                                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo" class="logo">
                                <h1>SERTIFIKAT</h1>
                                <p class="certificate-number">No: <?= str_pad($certificate['id'], 6, '0', STR_PAD_LEFT) ?>/<?= date('Y') ?></p>
                            </div>
                            
                            <div class="certificate-body">
                                <p class="certificate-text">
                                    Diberikan kepada:
                                </p>
                                <h2 class="participant-name"><?= esc($certificate['participant_name']) ?></h2>
                                <p class="certificate-text">
                                    Atas partisipasinya sebagai peserta dalam acara:
                                </p>
                                <h3 class="event-name"><?= esc($certificate['event_name']) ?></h3>
                                <p class="event-details">
                                    <?= date('d F Y', strtotime($certificate['event_date'])) ?> | <?= esc($certificate['event_location']) ?>
                                </p>
                            </div>
                            
                            <div class="certificate-footer">
                                <div class="signatures">
                                    <div class="signature">
                                        <div class="signature-box">
                                            <img src="<?= base_url('public/images/signature.png') ?>" alt="Tanda Tangan" class="signature-img">
                                        </div>
                                        <p class="signature-name">Alvin Alfandy</p>
                                        <p class="signature-title">Penyelenggara</p>
                                    </div>
                                    <div class="signature">
                                        <div class="signature-box">
                                            <img src="<?= base_url('public/images/signature.png') ?>" alt="Tanda Tangan" class="signature-img">
                                        </div>
                                        <p class="signature-name">Ded Bruyne</p>
                                        <p class="signature-title">Ketua</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .certificate-container {
        background: #fff;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    .certificate {
        border: 2px solid #2c3e50;
        padding: 3rem;
        position: relative;
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        min-height: 800px;
        display: flex;
        flex-direction: column;
    }

    .certificate::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        bottom: 20px;
        border: 1px solid #2c3e50;
        pointer-events: none;
    }

    .certificate-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .certificate-header .logo {
        width: 120px;
        margin-bottom: 1rem;
    }

    .certificate-header h1 {
        font-size: 2.5rem;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .certificate-number {
        color: #666;
        font-size: 1.1rem;
    }

    .certificate-body {
        text-align: center;
        margin-bottom: 3rem;
        flex-grow: 1;
    }

    .certificate-text {
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 1rem;
    }

    .participant-name {
        font-size: 2rem;
        color: #2c3e50;
        margin: 1.5rem 0;
        font-weight: 700;
    }

    .event-name {
        font-size: 1.5rem;
        color: #2c3e50;
        margin: 1rem 0;
        font-weight: 600;
    }

    .event-details {
        color: #666;
        font-size: 1.1rem;
    }

    .certificate-footer {
        text-align: center;
        margin-top: auto;
        padding-top: 4rem;
    }

    .signatures {
        display: flex;
        justify-content: space-around;
        padding: 0 4rem;
    }

    .signature {
        display: inline-block;
        text-align: center;
        width: 200px;
    }

    .signature-box {
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .signature-img {
        max-width: 150px;
        max-height: 80px;
        object-fit: contain;
    }

    .signature-name {
        font-size: 1.2rem;
        color: #2c3e50;
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .signature-title {
        color: #666;
        font-size: 1rem;
    }

    @media print {
        .certificate-container {
            padding: 0;
            box-shadow: none;
        }

        .certificate {
            border: none;
            padding: 0;
        }

        .certificate::before {
            display: none;
        }

        .btn {
            display: none;
        }

        .certificate-footer {
            margin-top: 4rem;
        }

        .signature-box {
            height: 100px;
        }

        .signature-img {
            max-width: 150px;
            max-height: 80px;
        }
    }
</style>
<?= $this->endSection() ?> 