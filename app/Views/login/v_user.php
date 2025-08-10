<?= $this->extend('layout/menu')?>
<?= $this->section('isi')?>

<head>
    <!-- DataTables -->
    <link href="<?= base_url()?>/assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="<?= base_url()?>/assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <!-- Required datatable js -->
    <script src="<?= base_url()?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url()?>/assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
</head>
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group float-right">
                <ol class="breadcrumb hide-phone p-0 m-0">
                    <li class="breadcrumb-item"><a href="<?= site_url('Home')?>">Beranda</a></li>
                    <li class="breadcrumb-item active">User</li>
                </ol>
            </div>
            <h4 class="page-title">User</h4>
        </div>
    </div>
</div>
<!-- end page title end breadcrumb -->

<div class="row">
    <div class="col-12 ">
        <div class="card m-b-30">
            <div class="card-body">
                <table class="table" id="my-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>E-mail</th>
                            <th>Nama pengguna</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 0;
                            foreach($user as $val){
                                if($val['level']=='1'){
                                    $status = 'Bendahara';
                                }else if ($val['level']=='2'){
                                    $status = 'Sekretaris';
                                }else if ($val['level']=='3'){
                                    $status = 'Jamaah';
                                }else if ($val['level']=='4'){
                                     $status = 'Ketua';
                                }
                            $no++;?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= $val['nama_user'] ?></td>
                            <td><?= $val['email'] ?></td>
                            <td><?= $val['nama'] ?></td>
                            <td><?= $status ?></td>
                            <td>
                                <form action="User/admin" method="post">
                                    <button type="button" class="btn btn-danger btn-sm btn-delete"
                                        data-id_user="<?= $val['id_user'];?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <?php  if($val['level']=='2'){?>
                                    <input type="hidden" value="<?= $val['id_user'] ?>" name="Admin">
                                    <button type="submit" class="btn btn-info btn-sm">Jadikan Admin
                                        <i class="mdi mdi-account-check"></i>
                                    </button>
                                    <?php } ?>
                                </form>

                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
<!-- MODAL HAPUS -->
<form action="/User/delete" method="post">
    <div class="modal" tabindex="-1" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Menghapus user beserta data transaksi pemesanan</h5>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="iduser" class="idpemesanan">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Yes</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- END MODAL HAPUS -->

<script type="text/javascript">
$('.btn-delete').on('click', function() {
    const id = $(this).data('id_user');
    $('.idpemesanan').val(id);
    $('#deleteModal').modal('show');
});

$(document).ready(function() {
    $('#my-table').DataTable();
});

$('#mainTable').editableTableWidget().numericInputExample().find('td:first').focus();
</script>
<?= $this->endSection('')?>