<?= $this->extend("layout/template"); ?>
<?= $this->section('content'); ?>

<h2 class="">Target Product Peformance Matrix </h2>
<?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success" role="alert">
        <?= session()->getFlashdata('message') ?>
    </div>
<?php endif; ?>
<form action="<?= base_url('/peformance') ?>" method="post" class="mb-5">
    <div class="row m-2">
        <div class="col-3">
            <label for="jumlahTransaksi" class="form-label">Jumlah Transaksi</label>
            <input type="number" class="form-control" id="jumlahTransaksi" name="transaksi" required>
        </div>
        <div class="col-3">
            <label for="profit" class="form-label">Profit %</label>
            <input type="number" class="form-control" id="profit" name="profit" required>
        </div>
        <div class="col-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select id="kategori" class="form-select" name="kategori">
                <option value="Winning">Winning </option>
                <option value="Loser">Loser </option>
            </select>
        </div>
        <div class="col-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</form>
<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Jumlah Transaksi</th>
            <th>Profit %</th>
            <th>Kategori</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($targets as $target): ?>
            <tr>
                <td><?= $target['amount'] ?></td>
                <td><?= $target['profit'] ?></td>
                <td><?= $target['category'] ?></td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>


<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>

<?= $this->endSection(); ?>