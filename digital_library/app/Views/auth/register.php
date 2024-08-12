<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('css/registerstyle.css') ?>" rel="stylesheet">
</head>

<body class="font-poppins">
    <div class="container">
        <div class="row align-items-center vh-100">
            <div class="col-md-6">
                <div class="card border-0 shadow card-custom">
                    <div class="card-body">
                        <div class="text-left" style="margin-bottom: 5px;">
                            <p class="fs-1">Unleash Your Creativity with Us!</p>
                            <p class="fs-4">Access to Thousands of Books</p>
                        </div>
                        <?php if (session()->getFlashdata('msg')) : ?>
                            <div class="pb-2">
                                <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
                                    <?= session()->getFlashdata('msg') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form action="<?= site_url('auth/register') ?>" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="REGISTER" value="REGISTER">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan email Anda" value="<?= old('email'); ?>">
                                <?php if (isset($validation) && $validation->hasError('email')) : ?>
                                    <div class="alert alert-danger mt-2">
                                        <?= $validation->getError('email'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username Anda" value="<?= old('username'); ?>">
                                <?php if (isset($validation) && $validation->hasError('username')) : ?>
                                    <div class="alert alert-danger mt-2">
                                        <?= $validation->getError('username'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password Anda">
                                <?php if (isset($validation) && $validation->hasError('password')) : ?>
                                    <div class="alert alert-danger mt-2">
                                        <?= $validation->getError('password'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi password Anda">
                                <?php if (isset($validation) && $validation->hasError('confirm_password')) : ?>
                                    <div class="alert alert-danger mt-2">
                                        <?= $validation->getError('confirm_password'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button type="submit" name="register" class="btn btn-primary w-100">Daftar</button>
                            <?php if (isset($validation) && $validation->hasError('register')) : ?>
                                <div class="alert alert-danger mt-2">
                                    <?= $validation->getError('register'); ?>
                                </div>
                            <?php endif; ?>
                        </form>
                        <div class="text-center mt-3">
                            <p>Dengan mendaftar, Anda menyetujui <a href="#">Ketentuan Penggunaan</a> dan <a href="#">Kebijakan Privasi</a>.</p>
                            <a href="<?= site_url('login') ?>" class="btn btn-outline-primary">Kembali ke Login</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 no-padding">
                <img src="<?= base_url('assets/register/10586 1.png') ?>" alt="rectangle" class="img-fluid">
            </div>
        </div>
    </div>
</body>

</html>