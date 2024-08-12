<?php

$itemPerPage = $itemPerPage ?? 10;
$currentPage = $currentPage ?? 1;

$i = 1 + ($itemPerPage * ($currentPage - 1));
?>

<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Kategori</title>
<link rel="stylesheet" href="/css/coverstyle.css">
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

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-2">
            <h1 class="fw-semibold">Data Kategori</h1>
            <div>
                <a href="<?= base_url('universal/kategori/new'); ?>" class="btn btn-primary">
                    <i class="ti ti-plus"></i>
                    Tambah Kategori
                </a>
            </div>
        </div>
        <div class="overflow-x-scroll">
            <table class="table table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kategori</th>
                        <th scope="col" class="text-center">Jumlah buku</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php if (!empty($categories) && is_array($categories)) : ?>
                        <?php foreach ($categories as $key => $category) : ?>
                            <tr>
                                <th scope="row"><?= $i++; ?></th>
                                <td>
                                    <a href="<?= base_url("universal/kategori/" . urlencode($category['name'])); ?>" class="text-primary-emphasis text-decoration-underline">
                                        <b><?= esc($category['name']); ?></b>
                                    </a>
                                </td>
                                <td class="text-center"><?= esc($category['book_count']); ?></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="<?= base_url("universal/kategori/" . urlencode($category['name']) . "/edit"); ?>" class="btn btn-primary mb-2">
                                            <i class="ti ti-edit"></i>
                                            Edit
                                        </a>
                                        <?php
                                        // Periksa peran pengguna
                                        $role = session()->get('role');
                                        if ($role === 'admin') {
                                            // Hapus berdasarkan nama untuk admin
                                            $deleteUrl = base_url("universal/kategori/" . urlencode($category['name']) . "/delete");
                                        } else {
                                            // Hapus berdasarkan ID untuk user
                                            $deleteUrl = base_url("universal/kategori/" . $category['id'] . "/delete");
                                        }
                                        ?>
                                        <form action="<?= $deleteUrl; ?>" method="post">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin?');">
                                                <i class="ti ti-trash"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No categories available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>