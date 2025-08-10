<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-success text-white rounded-top-4">
            <h4 class="mb-0">Tambah Kendaraan</h4>
        </div>
        <div class="card-body bg-light rounded-bottom-4">
            <form action="<?= base_url('kendaraan/save') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Nomor Polisi -->
                <div class="mb-3">
                    <label for="nopolisi_kendaraan" class="form-label">Nomor Polisi</label>
                    <input type="text" name="nopolisi_kendaraan" 
                        class="form-control rounded-3 <?= ($validation->hasError('nopolisi_kendaraan')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('nopolisi_kendaraan') ?>">
                    <div class="invalid-feedback">
                        <?= $validation->getError('nopolisi_kendaraan') ?>
                    </div>
                </div>

                <!-- Nama Kendaraan -->
                <div class="mb-3">
                    <label for="namakendaraan" class="form-label">Nama Kendaraan</label>
                    <input type="text" name="namakendaraan" 
                        class="form-control rounded-3 <?= ($validation->hasError('namakendaraan')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('namakendaraan') ?>">
                    <div class="invalid-feedback">
                        <?= $validation->getError('namakendaraan') ?>
                    </div>
                </div>

                <!-- Jumlah Kursi -->
                <div class="mb-3">
                    <label for="jumlahkursi" class="form-label">Jumlah Kursi</label>
                    <input type="number" name="jumlahkursi" 
                        class="form-control rounded-3 <?= ($validation->hasError('jumlahkursi')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('jumlahkursi') ?>">
                    <div class="invalid-feedback">
                        <?= $validation->getError('jumlahkursi') ?>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('kendaraan') ?>" class="btn btn-outline-secondary rounded-3">Batal</a>
                    <button type="submit" class="btn btn-success rounded-3 shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>