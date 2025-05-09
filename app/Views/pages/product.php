<?= $this->extend("layout/template"); ?>

<?= $this->section('content'); ?>
<h2>Tabel Transaksi Produk</h2>

<!-- Date Range and Category Filters -->
<div class="date-range-container">
    <!-- Date Range Picker -->
    <div class="form-group">
        <?php foreach ($targets as $target): ?>
            <input hidden type="text" name="<?= $target['category']; ?>" value="<?= $target['amount'] . ';' . $target['category'] . ';' . $target['profit']; ?>" id="<?= $target['category']; ?>">
        <?php endforeach; ?>
        <label for="dateRangePicker">Pilih Rentang Tanggal:</label>
        <input  type="text" id="dateRangePicker" name="daterange" />
    </div>

    <!-- First Category Dropdown (Single Select) -->
    <div class="form-group">
        <label for="selected">Pilih Kategori:</label>
        <select name="selected" id="category">
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
            <option value="transfer">Transfer</option>
        </select>
    </div>
</div>

<!-- Data Table -->
<table id="myTable" class="table tablestriped">
    <thead>
        <tr>
            <th rowspan="2">Product</th>
            <th rowspan="2">Jumlah Transaksi</th>
            <th colspan="2" class="text-center" data-dt-order="disable"><b>Harga Beli</b></th>
            <th colspan="2" class="text-center" data-dt-order="disable"><b>Harga Jual</b> </th>
            <th colspan="2" class="text-center" data-dt-order="disable"><b>Profit</b></th>
            <th colspan="2" class="text-center" data-dt-order="disable">% Profit</th>
            <th colspan="2" class="text-center" data-dt-order="disable">Margin</th>
        </tr>
        <tr>
            <th>Satuan</th>
            <th>Total</th>
            <th>Satuan</th>
            <th>Total</th>
            <th>Satuan</th>
            <th>Total</th>
            <th>Satuan</th>
            <th>Total</th>
            <th>Toko</th>
            <th>Server</th>
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

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
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
    function loadData(startDate = '', endDate = '', category = '', secondCategory = '', target = '40;winning;15') {
        let urlTransaction = 'http://localhost:3100/getTransaksi'; // Ensure this is correct

        const data = {
            start_date: startDate,
            end_date: endDate,
            category: category,
            second_category: secondCategory, // Add second category as a parameter
            target: target, // Add second category as a parameter

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
                rderCellsTop: true,
                fixedHeader: true,
                // ordering: true,
                order: [],
            });
        }

        tableTransaction.clear();

        // Filter out rows where jumlah_transaksi is not greater than comparison_result
        // const filteredData = data.filter(item => item.jumlah_transaksi > item.comparison_result);

        tableTransaction.rows.add(data.map(item => {
            const kode_produk = item.kode_produk;
            const jumlah_transaksi = item.jumlah_transaksi;

            const toRupiah = (value) => new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0
            }).format(value);

            const harga_beli = toRupiah(item.harga_beli);
            const beli_total = toRupiah(item.beli_total);
            const harga_jual = toRupiah(item.harga_jual);
            const jual_total = toRupiah(item.jual_total);
            const profit_item = toRupiah(item.profit);
            const profit_total = toRupiah(item.profit_total);
            const margin_toko = toRupiah(item.profit_total - item.komisi_total);
            const margin_server = toRupiah(item.komisi_total);
            const percent_item = Math.round(((item.profit / item.harga_beli) * 100) * 100) / 100;
            const percent_total = Math.round(((item.profit_total / item.beli_total) * 100) * 100) / 100;
    
            return [
                kode_produk,
                jumlah_transaksi,
                harga_beli,
                beli_total,
                harga_jual,
                jual_total,
                profit_item,
                profit_total,
                percent_item,
                percent_total,
                margin_toko,
                margin_server,
            ]
        }));
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
            const selectedCategory = $('#category').val();
            const secondSelectedCategory = $('#secondSelect').val(); // Get second category 
            const targetWinning = $('#Winning').val();
            const targetLoser = $('#Loser').val();
            let target = 'kosong';

            if (selectedCategory == 'loser') {
                target = targetLoser;
            } else {
                target = targetWinning;
            }

            loadData(startDate, endDate, selectedCategory, secondSelectedCategory, target); // Pass both categories
            loadTabel(startDate, endDate, selectedCategory, secondSelectedCategory);
        });

        // When the first category is changed
        $('#dateRangePicker').val('');
        $('#category').on('change', function() {
            const selectedCategory = $(this).val();
            const secondSelectedCategory = $('#secondSelect').val(); // Get second category value
            const dateRange = $('#dateRangePicker').val().split(' - ');
            const startDate = dateRange[0];
            const endDate = dateRange[1];
            const targetWinning = $('#Winning').val();
            const targetLoser = $('#Loser').val();
            let target = 'kosong';

            if (selectedCategory == 'loser') {
                target = targetLoser;
            } else {
                target = targetWinning;
            }
            console.log(target);


            loadData(startDate, endDate, selectedCategory, secondSelectedCategory, target);
            loadTabel(startDate, endDate, selectedCategory, secondSelectedCategory);
        });


        // When the second category is changed (multiple selection)
        $('#secondSelect').on('change', function() {
            const selectedCategory = $('#category').val();
            const secondSelectedCategory = $(this).val();
            const dateRange = $('#dateRangePicker').val().split(' - ');
            const startDate = dateRange[0];
            const endDate = dateRange[1];
            const targetWinning = $('#Winning').val();
            const targetLoser = $('#Loser').val();
            let target = 'kosong';

            if (selectedCategory == 'loser') {
                target = targetLoser;
            } else {
                target = targetWinning;
            }
            loadData(startDate, endDate, selectedCategory, secondSelectedCategory, target);
        });
    });
</script>

<?= $this->endSection(); ?>