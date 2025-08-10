<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-success text-white rounded-top-4">
            <h4 class="mb-0">Tambah User</h4>
        </div>
        <div class="card-body bg-light rounded-bottom-4">
            <!-- Tambahkan alert untuk pesan flash data -->
            <?php if (session()->getFlashdata('message')) : ?>
                <div class="alert alert-<?= session()->getFlashdata('message_type') ?> alert-dismissible fade show">
                    <?= session()->getFlashdata('message') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>

            <form action="<?= base_url('user/save') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Nama User -->
                <div class="mb-3">
                    <label for="nama_user" class="form-label">Nama User <span class="text-danger">*</span></label>
                    <input type="text" name="nama_user" 
                        class="form-control rounded-3 <?= ($validation->hasError('nama_user')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('nama_user') ?>"
                        required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('nama_user') ?>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" 
                        class="form-control rounded-3 <?= ($validation->hasError('email')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('email') ?>"
                        required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('email') ?>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" 
                        class="form-control rounded-3 <?= ($validation->hasError('password')) ? 'is-invalid' : '' ?>"
                        required
                        minlength="6">
                    <div class="invalid-feedback">
                        <?= $validation->getError('password') ?>
                    </div>
                    <small class="text-muted">Minimal 6 karakter</small>
                </div>

                <!-- No HP -->
                <div class="mb-3">
                    <label for="nohp" class="form-label">No HP <span class="text-danger">*</span></label>
                    <input type="tel" name="nohp" 
                        class="form-control rounded-3 <?= ($validation->hasError('nohp')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('nohp') ?>"
                        required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('nohp') ?>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select rounded-3 <?= ($validation->hasError('status')) ? 'is-invalid' : '' ?>" required>
                        <option value="">Pilih Status</option>
                        <option value="penumpang" <?= old('status') == 'penumpang' ? 'selected' : '' ?>>Penumpang</option>
                        <option value="admin" <?= old('status') == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="pimpinan" <?= old('status') == 'pimpinan' ? 'selected' : '' ?>>Pimpinan</option>
                        <option value="supir" <?= old('status') == 'supir' ? 'selected' : '' ?>>Supir</option>
                    </select>
                    <div class="invalid-feedback">
                        <?= $validation->getError('status') ?>
                    </div>
                </div>

                <!-- No SIM -->
                <div class="mb-3" id="nosim-field" style="display: none;">
                    <label for="nosim" class="form-label">No SIM <span class="text-danger">*</span></label>
                    <input type="text" name="nosim" 
                        class="form-control rounded-3 <?= ($validation->hasError('nosim')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('nosim') ?>">
                    <div class="invalid-feedback">
                        <?= $validation->getError('nosim') ?>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= base_url('user') ?>" class="btn btn-outline-secondary rounded-3">Batal</a>
                    <button type="submit" class="btn btn-success rounded-3 shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Tampilkan field No SIM hanya ketika status Supir dipilih
    document.querySelector('select[name="status"]').addEventListener('change', function() {
        const nosimField = document.getElementById('nosim-field');
        if (this.value === 'supir') {
            nosimField.style.display = 'block';
            nosimField.querySelector('input').setAttribute('required', '');
        } else {
            nosimField.style.display = 'none';
            nosimField.querySelector('input').removeAttribute('required');
        }
    });

    // Set initial state based on old value
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.querySelector('select[name="status"]');
        if (statusSelect.value === 'supir') {
            document.getElementById('nosim-field').style.display = 'block';
        }
    });
</script>

<?= $this->endSection() ?>