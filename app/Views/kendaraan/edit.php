<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<style>
    body {
        background-color: #f0f4f3;
    }

    .card-custom {
        background-color: #e9f5ee;
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0.75rem 1.5rem rgba(0, 128, 0, 0.1);
        transition: all 0.3s ease;
    }

    .card-custom:hover {
        transform: scale(1.01);
        box-shadow: 0 1rem 2rem rgba(0, 128, 0, 0.15);
    }

    .form-control,
    .form-select {
        border-radius: 0.75rem;
    }

    label {
        font-weight: 600;
        color: #155724;
    }

    h1 {
        color: #1c4532;
        font-weight: bold;
    }

    .btn-primary {
        background-color: #1e7e34;
        border-color: #1e7e34;
        border-radius: 0.5rem;
    }

    .btn-primary:hover {
        background-color: #155724;
        border-color: #155724;
    }

    .btn-secondary {
        border-radius: 0.5rem;
    }
</style>

<div class="container mt-4">
    <div class="card card-custom p-4">
        <h1 class="mb-4"><?= $title; ?></h1>

        <?php if(session()->getFlashdata('errors')) : ?>
            <div class="alert alert-danger rounded">
                <?php foreach(session()->getFlashdata('errors') as $error) : ?>
                    <p class="mb-0"><?= $error ?></p>
                <?php endforeach ?>
            </div>
        <?php endif; ?>

        <form action="/kendaraan/update/<?= $kendaraan['idkendaraan']; ?>" method="post">
            <?= csrf_field(); ?>

            <div class="mb-3">
                <label for="idkendaraan">ID Kendaraan</label>
                <input type="text" class="form-control" id="idkendaraan" name="idkendaraan" value="<?= $kendaraan['idkendaraan']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="nopolisi_kendaraan">Nomor Polisi</label>
                <input type="text" class="form-control" id="nopolisi_kendaraan" name="nopolisi_kendaraan" value="<?= $kendaraan['nopolisi_kendaraan']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="namakendaraan">Nama Kendaraan</label>
                <input type="text" class="form-control" id="namakendaraan" name="namakendaraan" value="<?= $kendaraan['namakendaraan']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="jumlahkursi">Jumlah Kursi</label>
                <input type="number" class="form-control" id="jumlahkursi" name="jumlahkursi" value="<?= $kendaraan['jumlahkursi']; ?>" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
                <a href="/kendaraan" class="btn btn-secondary">🔙 Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>