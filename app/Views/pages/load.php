<?= $this->extend("layout/template"); ?>

<?= $this->section('content'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
</head>

<h1><?= esc($title) ?></h1>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?= esc($success); ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= esc($error); ?></div>
<?php endif; ?>

<?php if (isset($data)): ?>
    <!-- Menampilkan nama file Excel -->
    <h3>Nama File: <?= esc($file_name) ?></h3>

    <h3>Data dari File Excel:</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <?php foreach ($data[0] as $header): ?>
                    <th><?= esc($header) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            // Menampilkan data tanpa header (jika diperlukan)
            $dataWithoutHeader = array_slice($data, 1); // Mengambil data mulai dari baris kedua
            foreach ($dataWithoutHeader as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo '<td>' . esc($cell) . '</td>';
                }
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- Button to go back to the "parsing" page with smaller size and centered -->
<div class="text-center">
    <a href="/pages/parsing" class="btn btn-primary btn-sm">Kembali ke Halaman Parsing</a>
</div>

</html>

<?= $this->endSection(); ?>