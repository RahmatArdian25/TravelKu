<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-success text-white rounded-top-4">
            <h4 class="mb-0">Tambah Rute</h4>
        </div>
        <div class="card-body bg-light rounded-bottom-4">
            <form action="<?= base_url('rute/save') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Asal -->
                <div class="mb-3">
                    <label for="asal" class="form-label">Asal <span class="text-danger">*</span></label>
                    <input type="text" name="asal" 
                        class="form-control rounded-3 <?= ($validation->hasError('asal')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('asal') ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('asal') ?>
                    </div>
                </div>

                <!-- Tujuan -->
                <div class="mb-3">
                    <label for="tujuan" class="form-label">Tujuan <span class="text-danger">*</span></label>
                    <input type="text" name="tujuan" 
                        class="form-control rounded-3 <?= ($validation->hasError('tujuan')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('tujuan') ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('tujuan') ?>
                    </div>
                </div>

                <!-- Harga -->
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                    <input type="number" name="harga" 
                        class="form-control rounded-3 <?= ($validation->hasError('harga')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('harga') ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('harga') ?>
                    </div>
                </div>

                <!-- Kendaraan -->
                <div class="mb-3">
                    <label for="idkendaraan" class="form-label">Kendaraan <span class="text-danger">*</span></label>
                    <select name="idkendaraan" 
                        class="form-select rounded-3 <?= ($validation->hasError('idkendaraan')) ? 'is-invalid' : '' ?>" required>
                        <option value="">Pilih Kendaraan</option>
                        <?php foreach ($kendaraans as $kendaraan) : ?>
                            <option value="<?= $kendaraan['idkendaraan'] ?>" <?= old('idkendaraan') == $kendaraan['idkendaraan'] ? 'selected' : '' ?>>
                                <?= $kendaraan['namakendaraan'] ?> (<?= $kendaraan['nopolisi_kendaraan'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= $validation->getError('idkendaraan') ?>
                    </div>
                </div>

                <!-- Jadwal Berangkat -->
                <div class="mb-3">
                    <label for="jadwalberangkat" class="form-label">Jadwal Berangkat <span class="text-danger">*</span></label>
                    <select name="jadwalberangkat" 
                        class="form-select rounded-3 <?= ($validation->hasError('jadwalberangkat')) ? 'is-invalid' : '' ?>" required>
                        <option value="">Pilih Jadwal</option>
                        <option value="pagi" <?= old('jadwalberangkat') == 'pagi' ? 'selected' : '' ?>>Pagi</option>
                        <option value="siang" <?= old('jadwalberangkat') == 'siang' ? 'selected' : '' ?>>Siang</option>
                        <option value="malam" <?= old('jadwalberangkat') == 'malam' ? 'selected' : '' ?>>Malam</option>
                    </select>
                    <div class="invalid-feedback">
                        <?= $validation->getError('jadwalberangkat') ?>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('rute') ?>" class="btn btn-outline-secondary rounded-3">Batal</a>
                    <button type="submit" class="btn btn-success rounded-3 shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>