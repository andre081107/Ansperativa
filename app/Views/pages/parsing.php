<?= $this->extend("layout/template"); ?>

<?= $this->section('content'); ?>
<form action="/pages/parsing" method="post" enctype="multipart/form-data">
    <?= csrf_field(); ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger" id="error-message">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p><?= esc($error) ?></p>
            <?php endforeach; ?>
        </div>

        <script>
            setTimeout(function() {
                const errorMessage = document.getElementById('error-message');
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
            }, 5000); // Pesan error akan hilang setelah 5 detik
        </script>
    <?php endif; ?>

    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }

        .container {
            text-align: center;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        #progress-bar {
            width: 100%;
            height: 30px;
            background-color: #e0e0e0;
            border-radius: 5px;
            margin-top: 20px;
            display: none;
        }

        #progress {
            height: 100%;
            width: 0;
            background-color: #4caf50;
            border-radius: 5px;
        }

        input[type="file"] {
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #4caf50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>

    <div class="container">
        <h2>Upload File dengan Progress</h2>
        <label for="file">Pilih File untuk Diunggah:</label><br>
        <input type="file" name="excel_file" id="excel_file" class="<?= isset($validation) && $validation->hasError('excel_file') ? 'is-invalid' : ''; ?>"><br>
        <button type="submit">Upload</button>
        <div id="progress-bar">
            <div id="progress"></div>
        </div>
    </div>
</form>
<?= $this->endSection(); ?>