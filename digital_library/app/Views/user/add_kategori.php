<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="/css/coverstyle.css">
<title>Tambah Kategori</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (!session()->has('logged_in') || !session()->get('logged_in')): ?>
    <?php header('Location: ' . base_url('/login'));
    exit(); ?>
<?php endif; ?>
<a href="<?= base_url('universal/kategori'); ?>" class="btn btn-outline-primary mb-3">
    <i class="ti ti-arrow-left"></i>
    Kembali
</a>

<?php if (session()->getFlashdata('msg')) : ?>
    <div class="pb-2">
        <div class="alert <?= session()->getFlashdata('error') ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('msg') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <h1 class="fw-semibold">Tambah Kategori</h1>
        <form action="<?= base_url('universal/kategori/create'); ?>" method="post">
            <?= csrf_field(); ?>
            <div class="my-3">
                <label for="category" class="form-label">Nama kategori</label>
                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('category') ? 'is-invalid' : ''; ?>" id="category" name="category" value="<?= old('category'); ?>" required>
                <div class="invalid-feedback">
                    <?= isset($validation) ? $validation->getError('category') : ''; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>