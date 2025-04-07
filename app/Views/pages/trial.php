<?= $this->extend("layout/template"); ?>

<?= $this->section('content'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<h2>Form Data Diri</h2>

<!-- Menampilkan pesan flash (success / error) -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<form method="POST" action="/pages/trial">
    <?= csrf_field() ?>

    <!-- Pasangan 1: Nama dan Email -->
    <div class="form-group row">
        <label for="nama1" class="col-sm-2 col-form-label">Nama</label>
        <div class="col-sm-7">
            <input type="text" class="form-control <?= session('validation') && session('validation')->hasError('nama') ? 'is-invalid' : ''; ?>" id="nama1" name="nama[]" autofocus value="<?= old('nama1'); ?>" required>
            <div class="invalid-feedback">
                <?= session('validation') ? session('validation')->getError('nama') : ''; ?>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="email1" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-7">
            <input type="email" class="form-control <?= session('validation') && session('validation')->hasError('email') ? 'is-invalid' : ''; ?>" id="email1" name="email[]" value="<?= old('email1'); ?>" required>
            <div class="invalid-feedback">
                <?= session('validation') ? session('validation')->getError('email') : ''; ?>
            </div>
        </div>
    </div>

    <!-- Pasangan 2: Nama dan Email -->
    <div class="form-group row">
        <label for="nama2" class="col-sm-2 col-form-label">Nama</label>
        <div class="col-sm-7">
            <input type="text" class="form-control <?= session('validation') && session('validation')->hasError('nama') ? 'is-invalid' : ''; ?>" id="nama2" name="nama[]" value="<?= old('nama2'); ?>" required>
            <div class="invalid-feedback">
                <?= session('validation') ? session('validation')->getError('nama') : ''; ?>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="email2" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-7">
            <input type="email" class="form-control <?= session('validation') && session('validation')->hasError('email') ? 'is-invalid' : ''; ?>" id="email2" name="email[]" value="<?= old('email2'); ?>" required>
            <div class="invalid-feedback">
                <?= session('validation') ? session('validation')->getError('email') : ''; ?>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

</html>

<?= $this->endSection(); ?>