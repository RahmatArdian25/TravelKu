 <script src="<?php echo base_url() ?>/assets/js/core/jquery-3.7.1.min.js"></script>
 <script src="<?php echo base_url() ?>/assets/js/core/popper.min.js"></script>
 <script src="<?php echo base_url() ?>/assets/js/core/bootstrap.min.js"></script>

 <!-- jQuery Scrollbar -->
 <script src="<?php echo base_url() ?>/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
 <!-- Moment JS -->
 <script src="<?php echo base_url() ?>/assets/js/plugin/moment/moment.min.js"></script>

 <!-- Chart JS -->
 <script src="<?php echo base_url() ?>/assets/js/plugin/chart.js/chart.min.js"></script>

 <!-- jQuery Sparkline -->
 <script src="<?php echo base_url() ?>/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

 <!-- Chart Circle -->
 <script src="<?php echo base_url() ?>/assets/js/plugin/chart-circle/circles.min.js"></script>

 <!-- Datatables -->
 <script src="<?php echo base_url() ?>/assets/js/plugin/datatables/datatables.min.js"></script>

 <!-- Bootstrap Notify -->
 <script src="<?php echo base_url() ?>/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

 <!-- jQuery Vector Maps -->
 <script src="<?php echo base_url() ?>/assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
 <script src="<?php echo base_url() ?>/assets/js/plugin/jsvectormap/world.js"></script>

 <!-- Sweet Alert -->
 <script src="<?php echo base_url() ?>/assets/js/plugin/sweetalert/sweetalert.min.js"></script>

 <!-- Kaiadmin JS -->
 <script src="<?php echo base_url() ?>/assets/js/kaiadmin.min.js"></script>

 <!-- Kaiadmin DEMO methods, don't include it in your project! -->
 <script src="<?php echo base_url() ?>/assets/js/setting-demo2.js"></script>

 <!-- SweetAlert dari flashdata -->
 <script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (session()->getFlashdata('success')): ?>
    swal({
        title: "Sukses!",
        text: "<?php echo session()->getFlashdata('success') ?>",
        icon: "success",
        buttons: {
            confirm: {
                text: "Mantap!",
                className: "btn btn-success",
            },
        },
    });
    <?php endif; ?>

    <?php if (session()->getFlashdata('info')): ?>
    swal({
        title: "Info",
        text: "<?php echo session()->getFlashdata('info') ?>",
        icon: "info",
        buttons: {
            confirm: {
                text: "Siap!",
                className: "btn btn-primary",
            },
        },
    });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    swal({
        title: "Oops!",
        text: "<?php echo session()->getFlashdata('error') ?>",
        icon: "error",
        buttons: {
            confirm: {
                text: "Mengerti!",
                className: "btn btn-danger",
            },
        },
    });
    <?php endif; ?>
});
$(document).ready(function() {
    $('#basic-datatables').DataTable();
});
 </script>