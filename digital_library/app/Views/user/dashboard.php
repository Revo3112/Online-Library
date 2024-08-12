<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="/css/coverstyle.css">
<link rel="stylesheet" href="/css/dashboardstyle.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (!session()->has('logged_in') || !session()->get('logged_in')): ?>
    <?php header('Location: ' . base_url('/login'));
    exit(); ?>
<?php endif; ?>

<div class='row'>
    <div class='col-md-12'>
        <h1>Dashboard</h1>
    </div>
</div>

<div class='row'>
    <div class='col-md-12'>
        <h2>Welcome, <?= esc($dashboard_data['nama']); ?></h2>
    </div>
</div>
<div class='row' id="item">
    <!-- Books -->
    <div class="col-lg-3 col-sm-6">
        <a href="<?= base_url('universal/books'); ?>">
            <div class="card">
                <div class="card-body text-center">
                    <div class="icon mb-2">
                        <i class="ti ti-book"></i>
                    </div>
                    <div class="info">
                        <h3><?= esc(count($dashboard_data['books'])); ?> Buku</h3>
                        <p>Total buku yang tersedia</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- Categories -->
    <div class="col-lg-3 col-sm-6">
        <a href="<?= base_url('universal/kategori'); ?>">
            <div class="card">
                <div class="card-body text-center">
                    <div class="icon mb-2">
                        <i class="ti ti-tag"></i>
                    </div>
                    <div class="info">
                        <h3><?= esc(count($dashboard_data['categories'])); ?> Kategori</h3>
                        <p>Total kategori yang tersedia</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- Last Book Edit -->
    <div class="col-lg-3 col-sm-6">
        <a href="<?= base_url('universal/books'); ?>">
            <div class="card">
                <div class="card-body text-center">
                    <div class="icon mb-2">
                        <i class="ti ti-alarm-clock"></i>
                    </div>
                    <div class="info">
                        <?php if (!empty($dashboard_data['last_book'])): ?>
                            <h3>
                                <?= esc($dashboard_data['last_book']['title']); ?>
                                <br>
                                <span>(<?= date('d M Y, H:i', strtotime($dashboard_data['last_book']['updated_at'])); ?>)</span>
                            </h3>
                        <?php else: ?>
                            <p>No Recent Edits</p>
                        <?php endif; ?>
                        <p>Terakhir di edit</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class='row' id="user-and-export">
    <div class="col-lg-3 col-sm-6">
        <a href="<?= base_url('universal/books/export'); ?>">
            <div class="card">
                <div class="card-body text-center">
                    <div class="icon mb-2">
                        <i class="ti ti-export"></i>
                    </div>
                    <div class="info">
                        <h3>Export to PDF</h3>
                        <p>Ekspor data buku ke PDF</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- Export to Excel -->
    <div class="col-lg-3 col-sm-6">
        <a href="<?= base_url('universal/books/export_excel'); ?>">
            <div class="card">
                <div class="card-body text-center">
                    <div class="icon mb-2">
                        <i class="ti ti-file"></i>
                    </div>
                    <div class="info">
                        <h3>Export to Excel</h3>
                        <p>Ekspor data buku ke Excel</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <?php if (isset($dashboard_data['user_count'])): ?>
        <div class="col-lg-3 col-sm-6">
            <a href="<?= base_url('admin/users'); ?>">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon mb-2">
                            <i class="ti ti-user"></i>
                        </div>
                        <div class="info">
                            <h3><?= esc($dashboard_data['user_count']); ?> Users</h3>
                            <p>Registered users</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>