<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-success text-white rounded-top-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Cetak Tiket</h4>
                <a href="<?= base_url("laporan/tiket/pdf/{$pemesanan['idpemesanan']}") ?>" class="btn btn-light rounded-3 shadow-sm">
                    <i class="fas fa-file-pdf me-1"></i> Download PDF
                </a>
            </div>
        </div>
        <div class="card-body bg-light rounded-bottom-4">
            <?= $this->include('laporan/pdf_tiket') ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>