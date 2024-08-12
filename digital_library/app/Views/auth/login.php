<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('css/loginstyle.css') ?>" rel="stylesheet">
</head>

<body class="font-poppins">
    <div class="container-fluid">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h1 class="text-center mb-4">Masuk</h1>
                        <p class="text-center">
                            <span style="color: black; font-size: 1.5em;">Perpustakaan</span>
                            <span style="color: #0D6EFD; font-size: 1.5em;">Online</span>
                        </p>


                        <?php if (session()->getFlashdata('msg')) : ?>
                            <div class="pb-2">
                                <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
                                    <?= session()->getFlashdata('msg') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form action="<?= site_url('auth/login') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="LOGIN" value="LOGIN">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="identifier" name="identifier" placeholder="Masukkan username atau email Anda" value="<?= old('identifier'); ?>">
                                <?php if (isset($validation) && $validation->hasError('identifier')) : ?>
                                    <div class="alert alert-danger mt-2">
                                        <?= $validation->getError('identifier'); ?>
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
                            <button type="submit" class="btn btn-primary btn-lg text-center" style="width: 100%;">Masuk</button>
                            <?php if (isset($validation) && $validation->hasError('login')) : ?>
                                <div class="alert alert-danger mt-2">
                                    <?= $validation->getError('login'); ?>
                                </div>
                            <?php endif; ?>
                        </form>


                        <div class="d-flex justify-content-between mt-3">
                            <a href="<?= site_url('auth/passwordreset') ?>" class="text-decoration-none">Lupa password?</a>
                        </div>
                    </div>
                    <div class="card-footer bg-white text-center custom-rounded-bottom">
                        <p class="mb-0">Baru di komunitas kami? <a href="<?= site_url('register') ?>" class="text-decoration-none">Buat akun</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-8 col-sm-10">
                <img src="<?= base_url('assets/login/undraw_remotely 1.png') ?>" alt="frame" class="img-fluid">
            </div>
        </div>
    </div>
</body>

</html>