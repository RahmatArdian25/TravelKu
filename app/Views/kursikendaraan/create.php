<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-success text-white rounded-top-4">
            <h4 class="mb-0">Tambah Kursi Kendaraan</h4>
        </div>
        <div class="card-body bg-light rounded-bottom-4">
            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="alert alert-danger">
                    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                        <p class="mb-0"><?= $error ?></p>
                    <?php endforeach ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <form action="<?= base_url('kursikendaraan/save') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="idkendaraan" class="form-label">Kendaraan</label>
                    <select name="idkendaraan" id="idkendaraan" class="form-select rounded-3 <?= ($validation->hasError('idkendaraan')) ? 'is-invalid' : '' ?>">
                        <option value="">Pilih Kendaraan</option>
                        <?php foreach($kendaraan as $k) : ?>
                            <option value="<?= $k['idkendaraan'] ?>" <?= old('idkendaraan') == $k['idkendaraan'] ? 'selected' : '' ?> data-jumlahkursi="<?= $k['jumlahkursi'] ?>">
                                <?= $k['namakendaraan'] ?> (<?= $k['nopolisi_kendaraan'] ?>) - <?= $k['jumlahkursi'] ?> kursi
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= $validation->getError('idkendaraan') ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="nomorkursi" class="form-label">Nomor Kursi</label>
                    <input type="number" name="nomorkursi" id="nomorkursi"
                        class="form-control rounded-3 <?= ($validation->hasError('nomorkursi')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('nomorkursi') ?>"
                        min="1">
                    <small class="text-muted">Maksimal kursi: <span id="maxSeatDisplay">-</span></small>
                    <div class="invalid-feedback">
                        <?= $validation->getError('nomorkursi') ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="jadwalberangkat" class="form-label">Jadwal Berangkat</label>
                    <select name="jadwalberangkat" class="form-select rounded-3 <?= ($validation->hasError('jadwalberangkat')) ? 'is-invalid' : '' ?>">
                        <option value="">Pilih Jadwal</option>
                        <option value="pagi" <?= old('jadwalberangkat') == 'pagi' ? 'selected' : '' ?>>Pagi</option>
                        <option value="siang" <?= old('jadwalberangkat') == 'siang' ? 'selected' : '' ?>>Siang</option>
                    </select>
                    <div class="invalid-feedback">
                        <?= $validation->getError('jadwalberangkat') ?>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('kursikendaraan') ?>" class="btn btn-outline-secondary rounded-3">Batal</a>
                    <button type="submit" class="btn btn-success rounded-3 shadow">Simpan</button>
                </div>
            </form>
        </div>
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

<?= $this->endSection() ?>