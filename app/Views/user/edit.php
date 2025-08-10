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
        border: 1px solid #ced4da;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1e7e34;
        box-shadow: 0 0 0 0.25rem rgba(30, 126, 52, 0.25);
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .is-invalid:focus {
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }

    label {
        font-weight: 600;
        color: #155724;
    }

    .required-field::after {
        content: " *";
        color: #dc3545;
    }

    h1 {
        color: #1c4532;
        font-weight: bold;
    }

    .btn-primary {
        background-color: #1e7e34;
        border-color: #1e7e34;
        border-radius: 0.5rem;
        padding: 0.5rem 1.5rem;
    }

    .btn-primary:hover {
        background-color: #155724;
        border-color: #155724;
    }

    .btn-secondary {
        border-radius: 0.5rem;
        padding: 0.5rem 1.5rem;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
    }

    .alert {
        border-radius: 0.75rem;
    }
</style>

<div class="container mt-4">
    <div class="card card-custom p-4">
        <h1 class="mb-4"><?= $title; ?></h1>

        <?php if(session()->getFlashdata('success')) : ?>
            <div class="alert alert-success rounded">
                <?= session()->getFlashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('errors')) : ?>
            <div class="alert alert-danger rounded">
                <ul class="mb-0">
                    <?php foreach(session()->getFlashdata('errors') as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/user/update/<?= $user['id_user']; ?>" method="post">
            <?= csrf_field(); ?>

            <div class="mb-3">
                <label for="id_user" class="form-label">ID User</label>
                <input type="text" class="form-control" id="id_user" value="<?= $user['id_user']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="nama_user" class="form-label required-field">Nama User</label>
                <input type="text" class="form-control <?= ($validation->hasError('nama_user')) ? 'is-invalid' : '' ?>" 
                    id="nama_user" name="nama_user" 
                    value="<?= old('nama_user', $user['nama_user']); ?>" required>
                <div class="invalid-feedback">
                    <?= $validation->getError('nama_user'); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label required-field">Email</label>
                <input type="email" class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : '' ?>" 
                    id="email" name="email" 
                    value="<?= old('email', $user['email']); ?>" required>
                <div class="invalid-feedback">
                    <?= $validation->getError('email'); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : '' ?>" 
                    id="password" name="password" 
                    placeholder="Biarkan kosong jika tidak ingin mengubah">
                <div class="invalid-feedback">
                    <?= $validation->getError('password'); ?>
                </div>
                <small class="text-muted">Minimal 6 karakter</small>
            </div>

            <div class="mb-3">
                <label for="nohp" class="form-label required-field">No HP</label>
                <input type="tel" class="form-control <?= ($validation->hasError('nohp')) ? 'is-invalid' : '' ?>" 
                    id="nohp" name="nohp" 
                    value="<?= old('nohp', $user['nohp']); ?>" required>
                <div class="invalid-feedback">
                    <?= $validation->getError('nohp'); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label required-field">Status</label>
                <select class="form-select <?= ($validation->hasError('status')) ? 'is-invalid' : '' ?>" 
                    id="status" name="status" required>
                    <option value="">Pilih Status</option>
                    <option value="penumpang" <?= old('status', $user['status']) == 'penumpang' ? 'selected' : '' ?>>Penumpang</option>
                    <option value="admin" <?= old('status', $user['status']) == 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="pimpinan" <?= old('status', $user['status']) == 'pimpinan' ? 'selected' : '' ?>>Pimpinan</option>
                    <option value="supir" <?= old('status', $user['status']) == 'supir' ? 'selected' : '' ?>>Supir</option>
                </select>
                <div class="invalid-feedback">
                    <?= $validation->getError('status'); ?>
                </div>
            </div>

            <div class="mb-3" id="nosim-field" style="<?= old('status', $user['status']) != 'supir' ? 'display: none;' : '' ?>">
                <label for="nosim" class="form-label">No SIM</label>
                <input type="text" class="form-control <?= ($validation->hasError('nosim')) ? 'is-invalid' : '' ?>" 
                    id="nosim" name="nosim" 
                    value="<?= old('nosim', $user['nosim'] ?? ''); ?>">
                <div class="invalid-feedback">
                    <?= $validation->getError('nosim'); ?>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
                <a href="/user" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Tampilkan field No SIM hanya ketika status Supir dipilih
    document.getElementById('status').addEventListener('change', function() {
        const nosimField = document.getElementById('nosim-field');
        if (this.value === 'supir') {
            nosimField.style.display = 'block';
            nosimField.querySelector('input').setAttribute('required', '');
        } else {
            nosimField.style.display = 'none';
            nosimField.querySelector('input').removeAttribute('required');
        }
    });
</script>

<?= $this->endSection(); ?>