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
        
        <?php if(session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger rounded">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="/kursikendaraan/update/<?= $kursiKendaraan['idkursi']; ?>" method="post">
            <?= csrf_field(); ?>

            <div class="mb-3">
                <label for="idkendaraan">Kendaraan</label>
                <select name="idkendaraan" id="idkendaraan" class="form-select <?= ($validation->hasError('idkendaraan')) ? 'is-invalid' : '' ?>">
                    <?php foreach($kendaraan as $k) : ?>
                        <option value="<?= $k['idkendaraan'] ?>" 
                            <?= (old('idkendaraan', $kursiKendaraan['idkendaraan']) == $k['idkendaraan']) ? 'selected' : '' ?>
                            data-jumlahkursi="<?= $k['jumlahkursi'] ?>">
                            <?= $k['namakendaraan'] ?> (<?= $k['nopolisi_kendaraan'] ?>) - <?= $k['jumlahkursi'] ?> kursi
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                    <?= $validation->getError('idkendaraan') ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="nomorkursi">Nomor Kursi</label>
                <input type="number" class="form-control <?= ($validation->hasError('nomorkursi')) ? 'is-invalid' : '' ?>" 
                    id="nomorkursi" name="nomorkursi" 
                    value="<?= old('nomorkursi', $kursiKendaraan['nomorkursi']); ?>" 
                    min="1" required>
                <small class="text-muted">Maksimal kursi: <span id="maxSeatDisplay">-</span></small>
                <div class="invalid-feedback">
                    <?= $validation->getError('nomorkursi') ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="jadwalberangkat">Jadwal Berangkat</label>
                <select name="jadwalberangkat" class="form-select <?= ($validation->hasError('jadwalberangkat')) ? 'is-invalid' : '' ?>">
                    <option value="pagi" <?= old('jadwalberangkat', $kursiKendaraan['jadwalberangkat']) == 'pagi' ? 'selected' : '' ?>>Pagi</option>
                    <option value="siang" <?= old('jadwalberangkat', $kursiKendaraan['jadwalberangkat']) == 'siang' ? 'selected' : '' ?>>Siang</option>
                </select>
                <div class="invalid-feedback">
                    <?= $validation->getError('jadwalberangkat') ?>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
                <a href="/kursikendaraan" class="btn btn-secondary">🔙 Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kendaraanSelect = document.getElementById('idkendaraan');
    const maxSeatDisplay = document.getElementById('maxSeatDisplay');
    const nomorKursiInput = document.getElementById('nomorkursi');
    
    function updateMaxSeat() {
        const selectedOption = kendaraanSelect.options[kendaraanSelect.selectedIndex];
        const jumlahKursi = selectedOption.getAttribute('data-jumlahkursi');
        
        maxSeatDisplay.textContent = jumlahKursi || '-';
        
        if (jumlahKursi) {
            nomorKursiInput.setAttribute('max', jumlahKursi);
            if (parseInt(nomorKursiInput.value) > parseInt(jumlahKursi)) {
                nomorKursiInput.value = jumlahKursi;
            }
        } else {
            nomorKursiInput.removeAttribute('max');
        }
    }
    
    kendaraanSelect.addEventListener('change', updateMaxSeat);
    
    // Initialize on load
    updateMaxSeat();
});
</script>

<?= $this->endSection(); ?>