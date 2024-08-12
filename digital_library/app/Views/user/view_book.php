<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title><?= esc($book['title']); ?> - Details</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (!session()->has('logged_in') || !session()->get('logged_in')): ?>
    <?php header('Location: ' . base_url('/login'));
    exit(); ?>
<?php endif; ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <img src="/img/<?= esc($book['cover_image']); ?>" alt="Book Cover" class="img-fluid">
        </div>
        <div class="col-md-8">
            <h1 class="centered-text"><?= esc($book['title']); ?></h1>
            <p class="centered-text"><?= esc($book['description']); ?></p>
            <div class="text-center">
                <a href="<?= previous_url() ?>" class="btn btn-outline-primary">
                    <i class="ti ti-arrow-left"></i> Back
                </a>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>