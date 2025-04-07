<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        /* Styling for the search box */
        #searchInput {
            padding: 10px;
            width: 300px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: purple;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Loading Spinner */
        #loading {
            display: none;
            text-align: center;
            position: absolute;
            height: 100vh;
            width: 100vw;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            flex-direction: column;
        }

        #loadingSpinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid purple;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
            margin-bottom: 20px;
        }

        /* Styling untuk pesan loading */
        #loading p {
            margin-top: 10px;
            /* Menggeser teks sedikit ke bawah */
            font-size: 16px;
            font-weight: bold;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsiveness for smaller screens */
        @media screen and (max-width: 768px) {
            #searchInput {
                width: 100%;
            }

            table {
                font-size: 14px;
            }

            th,
            td {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <h1>Data Parsing</h1>

    <!-- Pencarian -->
    <div>
        <label for="searchInput">Pencarian Kode: </label>
        <input type="text" id="searchInput" placeholder="Cari Kode Produk..." onkeyup="filterData()">
    </div>

    <!-- Loading Spinner -->
    <div id="loading">
        <div id="loadingSpinner"></div>
        <p>Loading data...</p>
    </div>

    <!-- Tabel -->
    <table>
        <thead>
            <tr>
                <th>Kode_Modul</th>
                <th>Kode_Produk</th>
                <th>Perintah</th>
                <th>aktif</th>
                <th>Prioritas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="dataBody"></tbody> <!-- Ubah id untuk referensi tabel -->
    </table>

    <script>
        let allData = []; // Menyimpan seluruh data dari API

        // Define the site URL from PHP
        const siteUrl = "<?= site_url() ?>"; // Ensure this outputs the correct base URL

        // Fungsi untuk memuat data
        function loadData() {
            // Tampilkan loading spinner saat data sedang dimuat
            document.getElementById('loading').style.display = 'flex';

            fetch('http://localhost:3200/getParsings')
                .then((response) => response.json())
                .then((data) => {
                    allData = data; // Simpan data ke dalam array global
                    renderData(data); // Panggil fungsi untuk menampilkan data
                    // Sembunyikan loading spinner setelah data selesai dimuat
                    document.getElementById('loading').style.display = 'none';
                })
                .catch((error) => {
                    console.error('Error fetching data:', error);
                    // Sembunyikan loading spinner jika terjadi error
                    document.getElementById('loading').style.display = 'none';
                });
        }

        // Fungsi untuk merender data ke dalam tabel
        function renderData(data) {
            let output = '';
            data.forEach(el => {
                output += `
                    <tr>
                        <td>${el.kode_modul}</td>
                        <td>${el.kode_produk}</td>
                        <td>${el.perintah}</td>
                        <td>${el.aktif}</td>
                        <td>${el.prioritas}</td>
                        <td>
                            <a href="javascript:void(0)" onclick="confirmDelete('${el.kode_modul}', '${el.kode_produk}')" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                `;
            });
            document.querySelector('#dataBody').innerHTML = output; // Render ke tabel
        }

        // Fungsi untuk filter data berdasarkan kode_modul yang berupa angka
        function filterData() {
            const searchTerm = document.getElementById('searchInput').value; // Ambil nilai input pencarian
            const searchTermNumber = Number(searchTerm); // Konversi input pencarian menjadi angka

            // Cek apakah input bisa dikonversi menjadi angka
            const filteredData = allData.filter(el => {
                const kodeModulNumber = Number(el.kode_modul); // Konversi kode_modul ke angka
                return !isNaN(searchTermNumber) && kodeModulNumber === searchTermNumber; // Bandingkan jika keduanya angka
            });

            renderData(filteredData); // Render data yang sudah difilter
        }

        // Function to handle the delete confirmation
        function confirmDelete(kode_modul, kode_produk) {
            const confirmation = confirm('Apakah Anda yakin ingin menghapus data ini?');

            if (confirmation) {
                // If confirmed, redirect to the delete URL
                window.location.href = `${siteUrl}/parsing/delete/${kode_modul}/${kode_produk}`;
            }
        }

        loadData(); // Panggil fungsi loadData saat halaman pertama kali dimuat
    </script>
</body>

</html>
<?= $this->endSection(); ?>