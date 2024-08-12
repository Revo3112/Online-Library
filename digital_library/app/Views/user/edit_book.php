<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="/css/coverstyle.css">
<title>Edit Book</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (!session()->has('logged_in') || !session()->get('logged_in')): ?>
    <?php header('Location: ' . base_url('/login'));
    exit(); ?>
<?php endif; ?>
<div class="container">
    <a href="<?= previous_url() ?>" class="btn btn-outline-primary mb-3">
        <i class="ti ti-arrow-left"></i> Back
    </a>
    <?php if (session()->getFlashdata('msg')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('msg'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($book) && is_array($book)): ?>
        <div class="card">
            <div class="card-body">
                <h1 class="fw-semibold">Form Edit Buku</h1>
                <form action="<?= base_url('universal/books/update/' . esc($book['id'])); ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="_method" value="PUT">

                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Buku</label>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('title') ? 'is-invalid' : ''; ?>" id="title" name="title" value="<?= esc($book['title']); ?>" required>
                        <div class="invalid-feedback"><?= isset($validation) ? $validation->getError('title') : ''; ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description"><?= esc($book['description']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            <?php if (isset($categories) && is_array($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= esc($category['id']); ?>" <?= $book['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                        <?= esc($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="quantity" class="form-label">Jumlah</label>
                        <input type="number" min="0" class="form-control <?= isset($validation) && $validation->hasError('quantity') ? 'is-invalid' : ''; ?>" id="quantity" name="quantity" value="<?= old('quantity'); ?>" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('quantity') : ''; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="file_pdf" class="form-label">Upload File Buku (PDF)</label>
                        <input type="file" class="form-control" id="file_pdf" name="file_pdf">
                        <?php if (!empty($book['file_path'])): ?>
                            <small class="form-text text-muted">Current file: <?= esc($book['file_path']); ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Gambar sampul buku</label>
                        <input type="file" class="form-control" id="cover_image" name="cover_image">
                        <?php if (!empty($book['cover_image'])): ?>
                            <img src="/img/<?= esc($book['cover_image']); ?>" alt="Cover Image" class="mt-2" style="width: 100px; height: auto;">
                            <small class="form-text text-muted">Current image: <?= esc($book['cover_image']); ?></small>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <p class="text-danger">Book details not found.</p>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const fileInput = document.querySelector('#cover_image');
    fileInput.addEventListener('change', function(event) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePreview = document.querySelector('img[alt="Cover Image"]');
            imagePreview.src = e.target.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    });
</script>
<?= $this->endSection() ?>