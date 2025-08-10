<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<style>
    body {
        background-color: #f5f7f6;
        font-family: 'Segoe UI', sans-serif;
    }
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 80, 50, 0.2);
    }
    .btn-primary {
        background-color: #256d3d;
        border: none;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #1e5a32;
    }
    .btn-warning, .btn-danger {
        border-radius: 8px;
    }
    .btn-warning:hover {
        opacity: 0.9;
    }
    .btn-danger:hover {
        opacity: 0.9;
    }
    h1 {
        color: #256d3d;
        font-weight: bold;
        margin-bottom: 20px;
    }
    table thead {
        background-color: #256d3d;
        color: white;
    }
    table tbody tr:hover {
        background-color: #eef8f0;
    }
    table {
        border-radius: 15px !important;
        overflow: hidden;
    }
    .badge-penumpang {
        background-color: #6c757d;
    }
    .badge-admin {
        background-color: #dc3545;
    }
    .badge-pimpinan {
        background-color: #fd7e14;
    }
    .badge-supir {
        background-color: #0d6efd;
    }
</style>

<div class="container">
    <h1 class="mt-4"><i class="fas fa-users me-2"></i><?= $title; ?></h1>
    
    <?php if(session()->getFlashdata('message')) : ?>
        <div class="alert alert-success rounded-pill px-4 py-2 shadow-sm"><?= session()->getFlashdata('message'); ?></div>
    <?php endif; ?>
    
    <a href="/user/create" class="btn btn-primary mb-3 shadow-sm rounded-pill px-4">
        <i class="fas fa-user-plus me-1"></i> Tambah User
    </a>
    
    <div class="card mb-5">
        <div class="card-body">
            <div class="table-responsive">
                <table id="userTable" class="table table-bordered table-striped table-hover rounded">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama User</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Status</th>
                            <th>No SIM</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($user as $user) : ?>
                        <tr>
                            <td><?= $user['id_user']; ?></td>
                            <td><?= $user['nama_user']; ?></td>
                            <td><?= $user['email']; ?></td>
                            <td><?= $user['nohp']; ?></td>
                            <td>
                                <span class="badge rounded-pill badge-<?= strtolower($user['status']) ?>">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </td>
                            <td><?= $user['nosim'] ?? '-'; ?></td>
                            <td class="text-center">
                                <a href="/user/edit/<?= $user['id_user']; ?>" class="btn btn-warning btn-sm me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="/user/delete/<?= $user['id_user']; ?>" method="post" class="d-inline">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTables Bootstrap 4 JS -->
<script>
    $(document).ready(function () {
        $('#userTable').DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>
<?= $this->endSection(); ?>