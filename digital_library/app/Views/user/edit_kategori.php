<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Edit Kategori</title>
<link rel="stylesheet" href="/css/coverstyle.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (!session()->has('logged_in') || !session()->get('logged_in')): ?>
    <?php header('Location: ' . base_url('/login'));
    exit(); ?>
<?php endif; ?>
<div class="container">
    <a href="<?= previous_url() ?>" class="btn btn-outline-primary mb-3">
        <i class="ti ti-arrow-left"></i>
        Kembali
    </a>

    <?php if (session()->getFlashdata('msg')) : ?>
        <div class="pb-2">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('msg') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <h1 class="fw-semibold">Edit Kategori</h1>
            <form action="<?= base_url('universal/kategori/' . (session()->get('role') === 'admin' ? urlencode($category['name']) : $category['id']) . '/update'); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="my-3">
                    <label for="category" class="form-label">Nama kategori</label>
                    <input type="text" class="form-control <?php if (isset($validation) && $validation->hasError('category')) : ?>is-invalid<?php endif ?>" id="category" name="category" value="<?= old('category', $category['name']); ?>" required>
                    <div class="invalid-feedback">
                        <?= isset($validation) ? $validation->getError('category') : ''; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>