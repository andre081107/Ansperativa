<?= $this->extend("layout/template"); ?>

<?= $this->section('content'); ?>
<h2>Daftar Mahasiswa dan Mata Kuliah</h2>
<table>
    <tr>
        <th>ID Mahasiswa</th>
        <th>Nama Mahasiswa</th>
        <th>Mata Kuliah</th>
    </tr>
    <?php foreach ($data as $row): ?>
    <tr>
        <td><?= $row->id_mahasiswa; ?></td>
        <td><?= $row->nama; ?></td>
        <td><?= $row->mata_kuliah; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?= $this->endSection(); ?>