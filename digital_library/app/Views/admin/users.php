<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="/css/coverstyle.css">
<title>User Management</title>
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
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h1>User Management</h1>
        </div>
        <div class="col-md-6 text-end">
            <form action="<?= base_url('admin/searchuser'); ?>" method="get" class="d-flex">
                <input type="text" class="form-control me-2" placeholder="Search users..." name="search" value="<?= esc($searchTerm ?? ''); ?>">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </form>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Book Count</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)) : ?>
                <?php foreach ($users as $index => $user) : ?>
                    <tr>
                        <td><?= esc($user['id']); ?></td>
                        <td><?= esc($user['username']); ?></td> <!-- Use 'username' -->
                        <td><?= esc($user['email']); ?></td>
                        <td><?= esc($user['book_count']); ?></td>
                        <td>
                            <form action="<?= base_url('admin/delete/' . $user['id']); ?>" method="post" style="display:inline;">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin?');">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>