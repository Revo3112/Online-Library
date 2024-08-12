<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('layouts/head') ?>
    <?= $this->renderSection('head') ?>
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar -->
        <?= $this->include('layouts/sidebar') ?>
        <div class="body-wrapper">
            <div class="container-fluid d-flex flex-wrap" style="min-height: 100vh;">
                <!-- Main content -->
                <div class="w-100">
                    <?= $this->renderSection('content') ?>
                </div>

                <div class="align-self-end w-100">
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?= $this->include('scripts/admin') ?>

    <!-- Extra scripts -->
    <?= $this->renderSection('scripts') ?>
</body>

</html>