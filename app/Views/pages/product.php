<?= $this->extend("layout/template"); ?>

<?= $this->section('content'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Link ke CSS DataTables versi 2.2.2 -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <!-- Sertakan CSS untuk Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <style>
        .form-group {
            display: flex;
            align-items: center;
            margin-top: 30px;
            gap: 10px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input {
            width: 160px;
            margin-right: 50px;
        }

        .form-group select {
            width: 180px;
            /* margin-left: 20px; */
        }

        .date-range-container {
            display: flex;
            justify-content: space-evenly;
            width: 100%;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            margin-right: 20px;
            margin-top: 10px;
        }

        h2 {
            margin-left: 20px;
            margin-top: 10px;
        }

        /* Style for the second table (3x3) */
        #totalTransactions {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        #totalTransactions th,
        #totalTransactions td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Tabel Transaksi Produk</h2>

    <!-- Date Range and Category Filters -->
    <div class="date-range-container">
        <!-- Date Range Picker -->
        <div class="form-group">
            <label for="dateRangePicker">Pilih Rentang Tanggal:</label>
            <input type="text" id="dateRangePicker" name="daterange" />
        </div>

        <!-- First Category Dropdown (Single Select) -->
        <div class="form-group">
            <label for="selected">Pilih Kategori:</label>
            <select name="selected" id="selected">
                <option value="category">Category</option>
                <option value="winning">Winning</option>
                <option value="traffic">Traffic</option>
                <option value="profit">Profit</option>
                <option value="loser">Loser</option>
            </select>
        </div>

        <!-- Second Category Dropdown (Multiple Select) -->
        <div class="form-group">
            <label for="secondSelect">Filter Kecuali:</label>
            <select name="secondSelect" id="secondSelect" class="js-example-basic-multiple-limit" multiple="multiple">
                <option value="pulsa">Pulsa</option>
                <option value="ewalet">E-walet</option>
                <option value="tagihan">Tagihan</option>
                <option value="game">Game</option>
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <table id="myTable" class="display">
        <thead>
            <tr>
                <th>Product</th>
                <th>Jumlah Transaksi</th>
                <th>Harga Jual</th>
                <th>Harga Beli</th>
                <th>Profit</th>
                <th>% Profit</th>
            </tr>
        </thead>
        <tbody id="dataBody"></tbody>
    </table>

    <!-- Additional Table (3x3) -->
    <table id="totalTransactions">
        <thead>
            <tr>
                <th></th>
                <th>Jumlah Transaksi</th>
                <th>Profit</th>
            </tr>
        </thead>
        <tbody id="tabelBody"></tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        let allData = [];
        let tableTransaction;
        let tableSummary;

        function loadTabel(startDate = '', endDate = '', category = '') {
            let urlSummary = 'http://localhost:3100/getTabel';

            const data = {
                start_date: startDate,
                end_date: endDate,
                category: category
            };

            fetch(urlSummary, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
                .then(data => {
                    // allData = data;
                    renderTabel(data);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        function renderTabel(data) {
            console.log(data)
            if (!tableSummary) {
                tableSummary = $('#totalTransactions').DataTable({
                    ordering: true,
                    order: [],
                });
            }

            tableSummary.clear();

            // Filter out rows where jumlah_transaksi is not greater than comparison_result
            // const filteredData = data.filter(item => item.jumlah_transaksi > item.comparison_result);

            tableSummary.rows.add(data.flatMap(item => [
                ['Toko', item.Jumlah_Transaksi, item.Laba],
                ['Server', item.Jumlah_Transaksi, item.Komisi]
            ]));
            tableSummary.draw();
        }


        // Function to load data with default parameters (empty filters)
        function loadData(startDate = '', endDate = '', category = '', secondCategory = '') {
            let urlTransaction = 'http://localhost:3100/getTransaksi'; // Ensure this is correct

            const data = {
                start_date: startDate,
                end_date: endDate,
                category: category,
                second_category: secondCategory // Add second category as a parameter
            };

            fetch(urlTransaction, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    allData = data;
                    renderData(data);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        function renderData(data) {
            if (!tableTransaction) {
                tableTransaction = $('#myTable').DataTable({
                    ordering: true,
                    order: [],
                });
            }

            tableTransaction.clear();

            // Filter out rows where jumlah_transaksi is not greater than comparison_result
            // const filteredData = data.filter(item => item.jumlah_transaksi > item.comparison_result);

            tableTransaction.rows.add(data.map(item => [
                item.kode_produk,
                item.jumlah_transaksi,
                item.harga,
                item.harga_beli,
                item.selisih,
                (Math.round((item.selisih / item.harga_beli) * 100) / 100)
            ]));
            tableTransaction.draw();
        }

        $(document).ready(function() {
            // Initialize the Select2 for the second dropdown (Multiple select)
            $(".js-example-basic-multiple-limit").select2({
                // maximumSelectionLength: 2,
                placeholder: "Pilih Filter",
                allowClear: true
            });

            // Ensure the default selection is properly displayed
            $('#secondSelect').val(['category']); // Set default value for second select        
            $('#secondSelect').trigger('change'); // Trigger change event to update Select2

            // Call loadData with no filters to get the default data
            loadData();
            loadTabel();
            // test();

            // Initialize Date Range Picker
            $('#dateRangePicker').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                },
                opens: 'center',
            }, function(start, end, label) {
                const startDate = start.format('YYYY-MM-DD');
                const endDate = end.format('YYYY-MM-DD');
                const selectedCategory = $('#selected').val();
                const secondSelectedCategory = $('#secondSelect').val(); // Get second category value
                loadData(startDate, endDate, selectedCategory, secondSelectedCategory); // Pass both categories
                loadTabel(startDate, endDate, selectedCategory, secondSelectedCategory);
            });

            // When the first category is changed
            $('#selected').on('change', function() {
                const selectedCategory = $(this).val();
                const secondSelectedCategory = $('#secondSelect').val(); // Get second category value
                const dateRange = $('#dateRangePicker').val().split(' - ');
                const startDate = dateRange[0];
                const endDate = dateRange[1];
                loadData(startDate, endDate, selectedCategory, secondSelectedCategory);
                loadTabel(startDate, endDate, selectedCategory, secondSelectedCategory);
            });

            // When the second category is changed (multiple selection)
            $('#secondSelect').on('change', function() {
                const selectedCategory = $('#selected').val();
                const secondSelectedCategory = $(this).val();
                const dateRange = $('#dateRangePicker').val().split(' - ');
                const startDate = dateRange[0];
                const endDate = dateRange[1];
                loadData(startDate, endDate, selectedCategory, secondSelectedCategory);
            });
        });
    </script>

</body>

</html>
<?= $this->endSection(); ?>