<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="/css/coverstyle.css">
<title>Tambah Buku</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (!session()->has('logged_in') || !session()->get('logged_in')): ?>
    <?php header('Location: ' . base_url('/login'));
    exit(); ?>
<?php endif; ?>
<a href="<?= base_url('universal/books'); ?>" class="btn btn-outline-primary mb-3">
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
        <h1 class="fw-semibold">Form Tambah Buku</h1>
        <form action="<?= base_url('universal/books/add'); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3 p-3">
                    <label for="cover_image" class="d-block" style="cursor: pointer;">
                        <div class="d-flex justify-content-center bg-light overflow-hidden h-100 position-relative">
                            <img id="bookCoverPreview" src="<?= base_url('img/default_cover.jpg'); ?>" alt="" height="300" class="z-1">
                            <p class="position-absolute top-50 start-50 translate-middle z-0">Pilih sampul</p>
                        </div>
                    </label>
                </div>
                <div class="col-12 col-md-6 col-lg-8 col-xl-9">
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Gambar sampul buku</label>
                        <input class="form-control <?= isset($validation) && $validation->hasError('cover_image') ? 'is-invalid' : ''; ?>" type="file" id="cover_image" name="cover_image" onchange="previewImage()" accept="image/jpeg,image/png,image/jpg">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('cover_image') : ''; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul buku</label>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('title') ? 'is-invalid' : ''; ?>" id="title" name="title" value="<?= old('title'); ?>" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('title') : ''; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select <?= isset($validation) && $validation->hasError('category_id') ? 'is-invalid' : ''; ?>" id="category_id" name="category_id" required>
                            <option value="">--Pilih kategori--</option>
                            <?php if (isset($categories) && (is_iterable($categories) || is_object($categories))) : ?>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?= esc($category['id']); ?>" <?= old('category_id') == $category['id'] ? 'selected' : ''; ?>><?= esc($category['name']); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('category_id') : ''; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('description') ? 'is-invalid' : ''; ?>" id="description" name="description"><?= old('description'); ?></textarea>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('description') : ''; ?>
                        </div>
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
                        <input class="form-control <?= isset($validation) && $validation->hasError('file_pdf') ? 'is-invalid' : ''; ?>" type="file" id="file_pdf" name="file_pdf" accept="application/pdf" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('file_pdf') : ''; ?>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function previewImage() {
        const fileInput = document.querySelector('#cover_image');
        const imagePreview = document.querySelector('#bookCoverPreview');

        const reader = new FileReader();
        reader.readAsDataURL(fileInput.files[0]);

        reader.onload = function(e) {
            imagePreview.src = e.target.result;
        };
    }
</script>
<?= $this->endSection() ?>