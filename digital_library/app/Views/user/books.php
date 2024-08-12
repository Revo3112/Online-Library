<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
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
<div class="container">
    <div class="row mb-3">
        <div class="col-md-9">
            <h1>Selamat datang di Halaman Buku</h1>
            <form action="<?= base_url('universal/books/search'); ?>" method="get" class="mt-3">
                <div class="input-group mb-6">
                    <input type="text" class="form-control" placeholder="Search books..." name="search">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form>
        </div>
        <div class="col-md-3 d-flex justify-content-end">
            <a href="<?= base_url('universal/books/add'); ?>" class="btn btn-primary me-2" style="height: min-content;">Tambah Buku</a>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Kategori
                </button>
                <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                    <li><a class="dropdown-item" href="<?= site_url('universal/books') ?>">All Categories</a></li>
                    <?php if (isset($categories) && is_array($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <li><a class="dropdown-item" href="<?= site_url('universal/books/' . $category['id']) ?>"><?= esc($category['name']) ?></a></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Sampul</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (isset($books) && !empty($books)): ?>
                            <?php foreach ($books as $index => $b): ?>
                                <tr>
                                    <td><?= $index + 1; ?></td>
                                    <td>
                                        <a href="<?= base_url('universal/books/view/' . esc($b->id)); ?>">
                                            <img src="/img/<?= esc($b->cover_image); ?>" class="sampul" alt="Book Cover">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('universal/serveFile/' . esc($b->file_path)); ?>" download="<?= esc($b->title); ?>.pdf">
                                            <?= esc($b->title); ?>
                                        </a>
                                        <br>Author: <?= esc($b->author_name); ?>
                                    </td>
                                    <td><?= esc($b->category_name); ?></td>
                                    <td><?= esc($b->quantity); ?></td>
                                    <td>
                                        <a href="<?= base_url("universal/books/edit/" . esc($b->id)); ?>" class="btn btn-primary">
                                            <i class="ti ti-edit"></i> Edit
                                        </a>
                                        <button class="btn btn-danger" onclick="deleteBook(<?= esc($b->id); ?>)">Delete</button>

                                        <script>
                                            function deleteBook(bookId) {
                                                if (confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
                                                    fetch('<?= base_url('universal/books/delete'); ?>/' + bookId, {
                                                            method: 'POST',
                                                            headers: {
                                                                'Content-Type': 'application/x-www-form-urlencoded',
                                                                'X-Requested-With': 'XMLHttpRequest',
                                                                'X-CSRF-TOKEN': '<?= csrf_hash(); ?>' // Ensure CSRF token is included
                                                            }
                                                        })
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            if (data.success) {
                                                                location.reload(); // Reload the page to reflect changes
                                                            } else {
                                                                alert('Failed to delete book: ' + data.message);
                                                            }
                                                        })
                                                        .catch(error => console.error('Error:', error));
                                                }
                                            }
                                        </script>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No books available.</td>
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