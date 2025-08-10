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
</style>

<div class="container">
    <h1 class="mt-4"><i class="fas fa-chair me-2"></i><?= $title; ?></h1>
    
    <?php if(session()->getFlashdata('message')) : ?>
        <div class="alert alert-success rounded-pill px-4 py-2 shadow-sm"><?= session()->getFlashdata('message'); ?></div>
    <?php endif; ?>
    
    <a href="/kursikendaraan/create" class="btn btn-primary mb-3 shadow-sm rounded-pill px-4">
        <i class="fas fa-plus-circle me-1"></i> Tambah Kursi Kendaraan
    </a>
    
    <div class="card mb-5">
        <div class="card-body">
            <div class="table-responsive">
                <table id="kursiKendaraanTable" class="table table-bordered table-striped table-hover rounded">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kendaraan</th>
                            <th>Nomor Kursi</th>
                            <th>Jadwal Berangkat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($kursiKendaraan as $kk) : ?>
                        <tr>
                            <td><?= $kk['idkursi']; ?></td>
                            <td><?= $kk['namakendaraan']; ?></td>
                            <td><?= $kk['nomorkursi']; ?></td>
                            <td><?= ucfirst($kk['jadwalberangkat']); ?></td>
                            <td class="text-center">
                                <a href="/kursikendaraan/edit/<?= $kk['idkursi']; ?>" class="btn btn-warning btn-sm me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="/kursikendaraan/delete/<?= $kk['idkursi']; ?>" method="post" class="d-inline">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
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
        $('#kursiKendaraanTable').DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>
<?= $this->endSection(); ?>