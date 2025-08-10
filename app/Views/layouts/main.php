<?php echo $this->include('layouts/heading'); ?>

<head>
    <!-- ... tag lainnya ... -->
    <style>
         .kursi-select option[disabled] {
        color: #ccc;
        background-color: #f8f9fa;
    }
    .kursi-select option[selected] {
        font-weight: bold;
    }
        .badge-penumpang {
            background-color: #6c757d;
            color: white;
        }
        .badge-admin {
            background-color: #dc3545;
            color: white;
        }
        .badge-pimpinan {
            background-color: #fd7e14;
            color: white;
        }
        .badge-supir {
            background-color: #0d6efd;
            color: white;
        }
        .kursi-btn {
    min-width: 50px;
    margin: 2px;
}

.kursi-btn.disabled {
    cursor: not-allowed;
}

.penumpang-group {
    border: 1px solid #dee2e6;
    border-radius: 5px;
}

.penumpang-group .card-header {
    background-color: #f8f9fa;
}
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php echo $this->include('layouts/sidebar') ?>
        <!-- End Sidebar -->

        <div class="main-panel">
            <?php echo $this->include('layouts/header') ?>

            <!-- main content -->
            <div class="container">
                <div class="page-inner">
                    <?php echo $this->renderSection('content') ?>
                </div>
            </div>
            <!-- end main content -->

            <!-- footer -->

            <?php echo $this->include('layouts/footer') ?>
            <!-- end footer -->
        </div>
        <!-- Custom template | don't include it in your project! -->
        <div class="custom-template">
            <div class="title">Settings</div>
            <div class="custom-content">
                <div class="switcher">
                    <div class="switch-block">
                        <h4>Logo Header</h4>
                        <div class="btnSwitch">
                            <button type="button" class=" selected changeLogoHeaderColor" data-color="dark"></button>
                            <button type="button" class="selected changeLogoHeaderColor" data-color="blue"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="purple"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="light-blue"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="green"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="orange"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="red"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="white"></button>
                            <br />
                            <button type="button" class="changeLogoHeaderColor" data-color="dark2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="blue2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="purple2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="light-blue2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="green2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="orange2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="red2"></button>
                        </div>
                    </div>
                    <div class="switch-block">
                        <h4>Navbar Header</h4>
                        <div class="btnSwitch">
                            <button type="button" class="changeTopBarColor" data-color="dark"></button>
                            <button type="button" class="changeTopBarColor" data-color="blue"></button>
                            <button type="button" class="changeTopBarColor" data-color="purple"></button>
                            <button type="button" class="changeTopBarColor" data-color="light-blue"></button>
                            <button type="button" class="changeTopBarColor" data-color="green"></button>
                            <button type="button" class="changeTopBarColor" data-color="orange"></button>
                            <button type="button" class="changeTopBarColor" data-color="red"></button>
                            <button type="button" class="changeTopBarColor" data-color="white"></button>
                            <br />
                            <button type="button" class="changeTopBarColor" data-color="dark2"></button>
                            <button type="button" class="selected changeTopBarColor" data-color="blue2"></button>
                            <button type="button" class="changeTopBarColor" data-color="purple2"></button>
                            <button type="button" class="changeTopBarColor" data-color="light-blue2"></button>
                            <button type="button" class="changeTopBarColor" data-color="green2"></button>
                            <button type="button" class="changeTopBarColor" data-color="orange2"></button>
                            <button type="button" class="changeTopBarColor" data-color="red2"></button>
                        </div>
                    </div>
                    <div class="switch-block">
                        <h4>Sidebar</h4>
                        <div class="btnSwitch">
                            <button type="button" class="selected changeSideBarColor" data-color="white"></button>
                            <button type="button" class="changeSideBarColor" data-color="dark"></button>
                            <button type="button" class="changeSideBarColor" data-color="dark2"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom-toggle">
                <i class="icon-settings"></i>
            </div>
        </div>
        <!-- End Custom template -->
    </div>

    <!--   Core JS Files   -->
    <?php echo $this->include('layouts/js') ?>

</body>

</html>