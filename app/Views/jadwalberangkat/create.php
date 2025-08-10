<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-success text-white rounded-top-4">
            <h4 class="mb-0">Tambah Jadwal Berangkat</h4>
        </div>
        <div class="card-body bg-light rounded-bottom-4">
            <form action="<?= base_url('jadwalberangkat/store') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Supir -->
                <div class="mb-3">
                    <label for="iduser" class="form-label">Supir</label>
                    <select name="iduser" class="form-select rounded-3 <?= ($validation->hasError('iduser')) ? 'is-invalid' : '' ?>">
                        <option value="">Pilih Supir</option>
                        <?php foreach($supir as $s) : ?>
                            <option value="<?= $s['id_user'] ?>" <?= old('iduser') == $s['id_user'] ? 'selected' : '' ?>>
                                <?= $s['nama_user'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= $validation->getError('iduser') ?>
                    </div>
                </div>

                <!-- Kendaraan -->
                <div class="mb-3">
                    <label for="idkendaraan" class="form-label">Kendaraan</label>
                    <select name="idkendaraan" class="form-select rounded-3 <?= ($validation->hasError('idkendaraan')) ? 'is-invalid' : '' ?>">
                        <option value="">Pilih Kendaraan</option>
                        <?php foreach($kendaraan as $k) : ?>
                            <option value="<?= $k['idkendaraan'] ?>" <?= old('idkendaraan') == $k['idkendaraan'] ? 'selected' : '' ?>>
                                <?= $k['namakendaraan'] ?> (<?= $k['nopolisi_kendaraan'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= $validation->getError('idkendaraan') ?>
                    </div>
                </div>

                <!-- Rute -->
                <div class="mb-3">
                    <label for="idrute" class="form-label">Rute</label>
                    <select name="idrute" class="form-select rounded-3 <?= ($validation->hasError('idrute')) ? 'is-invalid' : '' ?>">
                        <option value="">Pilih Rute</option>
                        <?php foreach($rute as $r) : ?>
                            <option value="<?= $r['idrute'] ?>" <?= old('idrute') == $r['idrute'] ? 'selected' : '' ?>>
                                <?= $r['asal'] ?> - <?= $r['tujuan'] ?> (<?= $r['jadwalberangkat'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= $validation->getError('idrute') ?>
                    </div>
                </div>

                <!-- Tanggal -->
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" 
                        class="form-control rounded-3 <?= ($validation->hasError('tanggal')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('tanggal') ?>">
                    <div class="invalid-feedback">
                        <?= $validation->getError('tanggal') ?>
                    </div>
                </div>

                <!-- Jam -->
                <div class="mb-3">
                    <label for="jam" class="form-label">Jam</label>
                    <input type="time" name="jam" 
                        class="form-control rounded-3 <?= ($validation->hasError('jam')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('jam') ?>">
                    <div class="invalid-feedback">
                        <?= $validation->getError('jam') ?>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('jadwalberangkat') ?>" class="btn btn-outline-secondary rounded-3">Batal</a>
                    <button type="submit" class="btn btn-success rounded-3 shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>