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

        <form action="/rute/update/<?= $rute['idrute']; ?>" method="post">
            <?= csrf_field(); ?>

            <div class="mb-3">
                <label for="idrute">ID Rute</label>
                <input type="text" class="form-control" id="idrute" name="idrute" value="<?= $rute['idrute']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="asal">Asal <span class="text-danger">*</span></label>
                <input type="text" class="form-control <?= ($validation->hasError('asal')) ? 'is-invalid' : '' ?>" 
                    id="asal" name="asal" value="<?= old('asal', $rute['asal']); ?>" required>
                <div class="invalid-feedback">
                    <?= $validation->getError('asal') ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="tujuan">Tujuan <span class="text-danger">*</span></label>
                <input type="text" class="form-control <?= ($validation->hasError('tujuan')) ? 'is-invalid' : '' ?>" 
                    id="tujuan" name="tujuan" value="<?= old('tujuan', $rute['tujuan']); ?>" required>
                <div class="invalid-feedback">
                    <?= $validation->getError('tujuan') ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="harga">Harga <span class="text-danger">*</span></label>
                <input type="number" class="form-control <?= ($validation->hasError('harga')) ? 'is-invalid' : '' ?>" 
                    id="harga" name="harga" value="<?= old('harga', $rute['harga']); ?>" required>
                <div class="invalid-feedback">
                    <?= $validation->getError('harga') ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="idkendaraan">Kendaraan <span class="text-danger">*</span></label>
                <select name="idkendaraan" class="form-select <?= ($validation->hasError('idkendaraan')) ? 'is-invalid' : '' ?>" required>
                    <option value="">Pilih Kendaraan</option>
                    <?php foreach ($kendaraans as $kendaraan) : ?>
                        <option value="<?= $kendaraan['idkendaraan'] ?>" <?= old('idkendaraan', $rute['idkendaraan'] ?? '') == $kendaraan['idkendaraan'] ? 'selected' : '' ?>>
                            <?= $kendaraan['namakendaraan'] ?> (<?= $kendaraan['nopolisi_kendaraan'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                    <?= $validation->getError('idkendaraan') ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="jadwalberangkat">Jadwal Berangkat <span class="text-danger">*</span></label>
                <select name="jadwalberangkat" class="form-select <?= ($validation->hasError('jadwalberangkat')) ? 'is-invalid' : '' ?>" required>
                    <option value="pagi" <?= old('jadwalberangkat', $rute['jadwalberangkat']) == 'pagi' ? 'selected' : '' ?>>Pagi</option>
                    <option value="siang" <?= old('jadwalberangkat', $rute['jadwalberangkat']) == 'siang' ? 'selected' : '' ?>>Siang</option>
                    <option value="malam" <?= old('jadwalberangkat', $rute['jadwalberangkat']) == 'malam' ? 'selected' : '' ?>>Malam</option>
                </select>
                <div class="invalid-feedback">
                    <?= $validation->getError('jadwalberangkat') ?>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
                <a href="/rute" class="btn btn-secondary">🔙 Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>