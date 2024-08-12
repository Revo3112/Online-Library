<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="/css/coverstyle.css">
<title><?= esc($title) ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (!session()->has('logged_in') || !session()->get('logged_in')): ?>
    <?php header('Location: ' . base_url('/login'));
    exit(); ?>
<?php endif; ?>
<?php if (session()->getFlashdata('msg')) : ?>
    <div class="pb-2">
        <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('msg') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>
<div class="container">
    <div class="row mb-3">
        <div class="col-md-9">
            <h1 class="fw-semibold">Buku dalam Kategori: <?= esc($categoryName); ?></h1>
            <form action="<?= base_url('universal/books/search2/' . urlencode($categoryName)); ?>" method="get" class="mt-3">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search books..." name="search" value="<?= esc($searchTerm ?? '') ?>">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form>
        </div>
        <div class="col-md-3 d-flex justify-content-end">
            <a href="<?= base_url('universal/kategori'); ?>" class="btn btn-outline-primary" style="height: fit-content;">
                <i class="ti ti-arrow-left"></i> Kembali ke Daftar Kategori
            </a>
        </div>
    </div>
    <div class="container mt-4">
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Sampul</th>
                            <th scope="col">Judul Buku</th>
                            <th scope="col">Penulis</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($books)) : ?>
                            <?php foreach ($books as $index => $book) : ?>
                                <tr>
                                    <th scope="row"><?= $index + 1 ?></th>
                                    <td>
                                        <img src="<?= base_url('img/' . $book['cover_image']); ?>" alt="Sampul Buku" class="sampul">
                                    </td>
                                    <td><?= esc($book['title']); ?></td>
                                    <td><?= esc($book['author_name']); ?></td>
                                    <td>
                                        <a href="<?= base_url('universal/books/view/' . $book['id']); ?>" class="btn btn-info btn-sm">Lihat</a>
                                        <?php if ($role === 'admin' || $book['created_by'] == $userId) : ?>
                                            <a href="<?= base_url('universal/books/edit/' . $book['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="<?= base_url('universal/books/delete/' . $book['id']); ?>" method="post" style="display:inline;">
                                                <?= csrf_field(); ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin?');">Hapus</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5">Tidak ada buku dalam kategori ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->endSection() ?>