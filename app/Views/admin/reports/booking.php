<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="mb-3 mb-sm-0">
        <h1 class="h3 mb-0 text-gray-800">Laporan Booking</h1>
        <p class="mb-0 text-black">Laporan data booking lengkap</p>
    </div>
    <div>
        <a href="<?= site_url('admin/reports/booking/print') ?>" id="printBtn" target="_blank" class="btn btn-primary btn-sm w-100">
            <i class="bi bi-printer me-1"></i> Cetak
        </a>
    </div>
</div>

<!-- Filter Form -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Data</h6>
    </div>
    <div class="card-body">
        <form id="filterForm">
            <div class="row mb-2">
                <div class="col-lg-6 col-md-8 mb-3 mb-md-0">
                    <div class="form-group">
                        <label for="singleDate">Tanggal</label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="singleDate" name="single_date" value="<?= isset($_GET['single_date']) ? $_GET['single_date'] : '' ?>">
                            <button type="button" id="filterBtn" class="btn btn-primary">
                                <i class="bi bi-filter me-1"></i> Filter
                            </button>
                            <button type="button" id="resetBtn" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Reset
                            </button>
                            <!--
                            <button type="button" id="todayBtn" class="btn btn-success">
                                <i class="bi bi-calendar-check me-1"></i> Hari Ini
                            </button>
                            <button type="button" id="showAllBtn" class="btn btn-info">
                                <i class="bi bi-list me-1"></i> Tampilkan Semua
                            </button>
                            -->
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Alert untuk pesan -->
<div id="alertMessage" class="alert alert-success alert-dismissible fade show d-none" role="alert">
    <span id="alertText"></span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Booking</h6>
                <div id="loadingIndicator" class="spinner-border spinner-border-sm text-primary d-none" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="card-body">
                <!-- Instruksi untuk memilih filter -->
                <div id="instructionMessage" class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i> Silakan pilih filter tanggal terlebih dahulu untuk menampilkan data booking.
                </div>

                <!-- Konten tabel yang akan ditampilkan setelah data dimuat -->
                <div id="tableContent" style="display: none;">
                    <!-- Pencarian Sederhana -->
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari data...">
                                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="d-flex justify-content-start justify-content-md-end">
                                <select id="entriesPerPage" class="form-select form-select-sm me-2" style="width: auto; display: none;">
                                    <option value="10">10 entri</option>
                                    <option value="25">25 entri</option>
                                    <option value="50">50 entri</option>
                                    <option value="100">100 entri</option>
                                </select>
                                <!-- <span class="mt-1" id="tableInfo">Menampilkan 0 data</span> -->
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="bookingTable" width="100%" cellspacing="0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%" class="sortable" data-sort="no">No</th>
                                    <th class="sortable" data-sort="kdbooking">Kode Booking</th>
                                    <th class="sortable" data-sort="pelanggan">Nama Pelanggan</th>
                                    <!-- <th class="sortable" data-sort="tanggal">Tanggal</th> -->
                                    <th class="sortable" data-sort="paket">Nama Paket</th>
                                    <th class="sortable" data-sort="harga">Harga Paket</th>
                                    <!-- <th class="sortable" data-sort="total">Total Bayar</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Tabel body akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Info jumlah data -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <p id="tableInfo" class="text-center">Menampilkan 0 data</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Variabel untuk menyimpan data tabel
        var tableData = [];
        var sortColumn = 'no';
        var sortDirection = 'asc';

        console.log("Document ready, initializing...");

        // Styling untuk kolom sortable
        $('.sortable').css('cursor', 'pointer');
        $('.sortable').append(' <span class="sort-icon"></span>');

        // Cek apakah ada parameter filter di URL
        var urlParams = new URLSearchParams(window.location.search);
        var startDateParam = urlParams.get('start_date');
        var endDateParam = urlParams.get('end_date');
        var singleDateParam = urlParams.get('single_date');

        console.log("URL parameters:", startDateParam, endDateParam, singleDateParam);

        // Jika ada parameter, gunakan untuk filter
        if (startDateParam || endDateParam || singleDateParam) {
            console.log("Loading data with URL parameters");
            loadBookingData(startDateParam, endDateParam, singleDateParam);
        } else {
            // Jika tidak ada parameter dan tidak ada data yang ditampilkan, muat semua data
            <?php if (empty($_GET['start_date']) && empty($_GET['end_date']) && empty($_GET['single_date']) && empty($bookings)): ?>
                console.log("No data displayed yet, showing initial message");
                // Tidak perlu memuat data, tampilkan pesan instruksi
            <?php else: ?>
                console.log("Loading all data on initial page load");
                loadBookingData('', '', '');
            <?php endif; ?>
        }

        // Event listener untuk sorting
        $('.sortable').on('click', function() {
            var column = $(this).data('sort');

            // Jika mengklik kolom yang sama, balik arah sorting
            if (sortColumn === column) {
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                sortColumn = column;
                sortDirection = 'asc';
            }

            // Update tampilan ikon sort
            $('.sort-icon').html('');
            var icon = sortDirection === 'asc' ? '&#9650;' : '&#9660;';
            $(this).find('.sort-icon').html(icon);

            // Render ulang tabel
            renderTable();
        });

        // Event listener untuk pencarian
        $('#searchBtn').on('click', function() {
            renderTable();
        });

        $('#searchInput').on('keyup', function(e) {
            if (e.key === 'Enter') {
                renderTable();
            }
        });

        // Fungsi untuk memuat data booking dengan AJAX
        function loadBookingData(startDate, endDate, singleDate) {
            $('#loadingIndicator').removeClass('d-none');
            console.log("Loading booking data with startDate:", startDate, "endDate:", endDate, "singleDate:", singleDate);

            // Reset tableData
            tableData = [];

            $.ajax({
                url: '<?= site_url('admin/reports/booking/getData') ?>',
                type: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    single_date: singleDate
                },
                dataType: 'json',
                success: function(response) {
                    console.log("AJAX response received:", response);

                    if (response.success) {
                        // Reset data tabel
                        tableData = [];
                        console.log("Cleared tableData");

                        // Menyimpan booking yang sudah diproses untuk menghindari duplikat
                        var processedBookings = {};

                        if (response.data && Array.isArray(response.data) && response.data.length > 0) {
                            console.log("Processing data array response with", response.data.length, "bookings");

                            var no = 1;
                            response.data.forEach(function(booking) {
                                // Skip jika booking sudah diproses sebelumnya
                                if (processedBookings[booking.kdbooking]) {
                                    return;
                                }

                                // Tandai booking ini sudah diproses
                                processedBookings[booking.kdbooking] = true;

                                // Gabungkan semua paket dalam booking ini
                                var paketNames = [];
                                if (booking.details && Array.isArray(booking.details)) {
                                    booking.details.forEach(function(detail) {
                                        // Gabungkan nama paket dengan deskripsi
                                        var paketInfo = detail.nama_paket;
                                        if (detail.deskripsi) {
                                            paketInfo += " (" + detail.deskripsi + ")";
                                        }
                                        paketNames.push(paketInfo);
                                    });
                                }

                                // Ambil tanggal dari detail pertama
                                var tanggal = booking.details && booking.details.length > 0 ?
                                    formatDate(booking.details[0].tgl) : '';

                                // Hitung total harga dari detail booking
                                var totalHarga = 0;
                                if (booking.details && Array.isArray(booking.details)) {
                                    booking.details.forEach(function(detail) {
                                        totalHarga += parseFloat(detail.harga || 0);
                                    });
                                }

                                // Tambahkan data ke tableData
                                tableData.push({
                                    no: no++,
                                    kdbooking: booking.kdbooking,
                                    pelanggan: booking.nama_lengkap,
                                    // tanggal: tanggal,
                                    paket: paketNames.join(", "),
                                    harga: 'Rp ' + formatNumber(totalHarga),
                                    total: 'Rp ' + formatNumber(booking.total)
                                });
                            });
                        }

                        console.log("Final tableData:", tableData);

                        // Render tabel dengan data baru
                        renderTable();

                        // Tampilkan pesan sukses
                        showAlert('success', response.message);

                        // Perbarui URL cetak
                        updatePrintUrl(startDate, endDate, singleDate);

                        // Tampilkan konten tabel dan sembunyikan pesan instruksi
                        if (tableData.length > 0) {
                            $('#instructionMessage').hide();
                            $('#tableContent').show();
                        } else {
                            // Jika tidak ada data, tampilkan pesan
                            $('#instructionMessage').html('<i class="bi bi-info-circle me-2"></i> Tidak ada data booking yang ditemukan. Silakan coba filter dengan kriteria berbeda.');
                            $('#instructionMessage').show();
                            $('#tableContent').hide();
                        }
                    } else {
                        console.error("Error in response:", response.message);
                        showAlert('danger', response.message || 'Terjadi kesalahan saat memuat data');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", status, error);
                    showAlert('danger', 'Terjadi kesalahan pada server: ' + error);
                },
                complete: function() {
                    $('#loadingIndicator').addClass('d-none');
                }
            });
        }

        // Fungsi untuk merender tabel
        function renderTable() {
            try {
                console.log("Rendering table...");
                console.log("tableData:", tableData);

                // Pastikan tableData adalah array
                if (!Array.isArray(tableData)) {
                    console.error('tableData bukan array');
                    tableData = [];
                }

                // Filter data dengan cara yang sangat aman
                var filteredData = [];
                var searchTerm = $('#searchInput').val() || '';
                searchTerm = searchTerm.toString().toLowerCase();

                for (var i = 0; i < tableData.length; i++) {
                    var item = tableData[i];
                    if (!item) continue;

                    // Jika tidak ada pencarian, tambahkan semua item
                    if (!searchTerm) {
                        filteredData.push(item);
                        continue;
                    }

                    // Jika ada pencarian, cek setiap properti
                    var match = false;
                    for (var key in item) {
                        if (!item.hasOwnProperty(key)) continue;

                        var value = item[key];
                        if (value === null || value === undefined) continue;

                        var strValue = '';
                        try {
                            strValue = String(value).toLowerCase();
                        } catch (e) {
                            console.error("Error converting value to string:", e);
                            continue;
                        }

                        if (strValue.indexOf(searchTerm) !== -1) {
                            match = true;
                            break;
                        }
                    }

                    if (match) {
                        filteredData.push(item);
                    }
                }

                // Sorting sangat sederhana tanpa toLowerCase
                if (sortColumn) {
                    filteredData.sort(function(a, b) {
                        try {
                            if (!a || !b) return 0;

                            var aVal = a[sortColumn];
                            var bVal = b[sortColumn];

                            // Handle null/undefined values
                            if (aVal === null || aVal === undefined) aVal = '';
                            if (bVal === null || bVal === undefined) bVal = '';

                            // Convert to appropriate types for comparison
                            if (sortColumn === 'no') {
                                // For numeric columns
                                aVal = parseInt(String(aVal).replace(/[^\d]/g, '')) || 0;
                                bVal = parseInt(String(bVal).replace(/[^\d]/g, '')) || 0;
                            } else if (sortColumn === 'total') {
                                // For currency columns
                                aVal = parseFloat(String(aVal).replace(/[^\d.,]/g, '').replace(/\./g, '').replace(',', '.')) || 0;
                                bVal = parseFloat(String(bVal).replace(/[^\d.,]/g, '').replace(/\./g, '').replace(',', '.')) || 0;
                            } else if (sortColumn === 'tanggal') {
                                // For date columns
                                var aDate = parseDateString(String(aVal));
                                var bDate = parseDateString(String(bVal));
                                aVal = aDate ? aDate.getTime() : 0;
                                bVal = bDate ? bDate.getTime() : 0;
                            } else {
                                // For string columns - convert to string without toLowerCase
                                aVal = String(aVal);
                                bVal = String(bVal);
                                // Simple string comparison (case-insensitive but without toLowerCase)
                                return sortDirection === 'asc' ?
                                    aVal.localeCompare(bVal, undefined, {
                                        sensitivity: 'base'
                                    }) :
                                    bVal.localeCompare(aVal, undefined, {
                                        sensitivity: 'base'
                                    });
                            }

                            // Compare values
                            if (aVal < bVal) return sortDirection === 'asc' ? -1 : 1;
                            if (aVal > bVal) return sortDirection === 'asc' ? 1 : -1;
                            return 0;
                        } catch (e) {
                            console.error("Error during sorting:", e);
                            return 0;
                        }
                    });
                }

                // Helper function to parse date strings safely
                function parseDateString(dateStr) {
                    try {
                        if (!dateStr) return null;
                        var parts = dateStr.split('/');
                        if (parts.length !== 3) return null;
                        var day = parseInt(parts[0]);
                        var month = parseInt(parts[1]) - 1;
                        var year = parseInt(parts[2]);
                        var date = new Date(year, month, day);
                        return isNaN(date.getTime()) ? null : date;
                    } catch (e) {
                        console.error("Error parsing date:", e);
                        return null;
                    }
                }

                // Update info text
                $('#tableInfo').text('Menampilkan ' + filteredData.length + ' data');

                // Render table rows
                var tbody = $('#bookingTable tbody');
                tbody.empty();

                console.log("Rendering table rows, filteredData length:", filteredData.length);

                if (filteredData.length === 0) {
                    console.log("No data to display");
                    tbody.html('<tr><td colspan="7" class="text-center">Data booking tidak ditemukan. Silakan coba filter dengan kriteria berbeda.</td></tr>');
                } else {
                    console.log("Displaying data:", filteredData);
                    for (var i = 0; i < filteredData.length; i++) {
                        var item = filteredData[i] || {};
                        console.log("Rendering item:", item);
                        var row = '<tr>' +
                            '<td>' + (item.no || '') + '</td>' +
                            '<td>' + (item.kdbooking || '') + '</td>' +
                            '<td>' + (item.pelanggan || '') + '</td>' +
                            // '<td>' + (item.tanggal || '') + '</td>' +
                            '<td>' + (item.paket || '') + '</td>' +
                            '<td>' + (item.harga || '') + '</td>' +
                            '</tr>';
                        tbody.append(row);
                    }
                }

                console.log("Table rendered successfully");
            } catch (e) {
                console.error('Error dalam renderTable:', e);
                $('#bookingTable tbody').html('<tr><td colspan="7" class="text-center">Terjadi kesalahan saat memuat data. Silakan coba lagi.</td></tr>');
            }
        }

        // Fungsi untuk menampilkan pesan alert
        function showAlert(type, message) {
            $('#alertMessage')
                .removeClass('d-none alert-success alert-danger')
                .addClass('alert-' + type)
                .find('#alertText')
                .text(message);

            // Sembunyikan pesan setelah 5 detik
            setTimeout(function() {
                $('#alertMessage').addClass('d-none');
            }, 5000);
        }

        // Fungsi untuk memperbarui URL cetak
        function updatePrintUrl(startDate, endDate, singleDate) {
            var printUrl = '<?= site_url('admin/reports/booking/print') ?>';
            var params = [];

            if (startDate) {
                params.push('start_date=' + startDate);
            }

            if (endDate) {
                params.push('end_date=' + endDate);
            }

            if (singleDate) {
                params.push('single_date=' + singleDate);
            }

            if (params.length > 0) {
                printUrl += '?' + params.join('&');
            }

            $('#printBtn').attr('href', printUrl);
            console.log("Print URL updated:", printUrl);
        }

        // Fungsi untuk memformat tanggal
        function formatDate(dateStr) {
            try {
                if (!dateStr) return '';
                var date = new Date(dateStr);
                if (isNaN(date.getTime())) return dateStr; // Jika parsing gagal, kembalikan string asli

                var day = date.getDate().toString().padStart(2, '0');
                var month = (date.getMonth() + 1).toString().padStart(2, '0');
                var year = date.getFullYear();

                return day + '/' + month + '/' + year;
            } catch (e) {
                console.error("Error formatting date:", e);
                return dateStr;
            }
        }

        // Fungsi untuk memformat angka
        function formatNumber(num) {
            try {
                if (num === null || num === undefined) return '0';
                return parseFloat(num).toLocaleString('id-ID');
            } catch (e) {
                console.error("Error formatting number:", e);
                return '0';
            }
        }

        // Event listener untuk tombol filter
        $('#filterBtn').on('click', function() {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var singleDate = $('#singleDate').val();
            console.log("Filter button clicked with dates:", startDate, endDate, singleDate);

            if (!startDate && !endDate && !singleDate) {
                alert('Silakan pilih setidaknya satu jenis tanggal untuk filter');
                return;
            }

            // Validasi tanggal range
            if (startDate && endDate && startDate > endDate) {
                alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir!');
                return;
            }

            loadBookingData(startDate, endDate, singleDate);
        });

        // Event listener untuk tombol reset
        $('#resetBtn').on('click', function() {
            console.log("Reset button clicked");
            $('#startDate').val('');
            $('#endDate').val('');
            $('#singleDate').val(''); // Reset single date
            // Tidak perlu memuat data, hanya reset form
            $('#instructionMessage').show();
            $('#tableContent').hide();
            tableData = [];
        });

        // Tambahkan event handler untuk tombol "Tampilkan Semua" yang akan memuat semua data
        $('#showAllBtn').on('click', function() {
            console.log("Show All button clicked");
            $('#startDate').val('');
            $('#endDate').val('');
            $('#singleDate').val(''); // Reset single date
            loadBookingData('', '', '');
        });

        // Event handler untuk tombol "Hari Ini"
        $('#todayBtn').on('click', function() {
            console.log("Today button clicked");
            var today = new Date();
            var formattedToday = formatDate2(today);

            // Reset semua filter dan set single date ke hari ini
            $('#startDate').val('');
            $('#endDate').val('');
            $('#singleDate').val(formattedToday);

            // Load data untuk hari ini
            loadBookingData('', '', formattedToday);
        });

        // Fungsi untuk memformat tanggal ke format YYYY-MM-DD untuk input date
        function formatDate2(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('-');
        }

        // Event listener untuk input-input tanggal agar saling eksklusif
        $('#singleDate').on('change', function() {
            if ($(this).val()) {
                // Jika tanggal tunggal diisi, kosongkan rentang tanggal
                $('#startDate').val('');
                $('#endDate').val('');
            }
        });

        $('#startDate, #endDate').on('change', function() {
            if ($('#startDate').val() || $('#endDate').val()) {
                // Jika salah satu rentang tanggal diisi, kosongkan tanggal tunggal
                $('#singleDate').val('');
            }
        });

        // Event handler untuk tombol print
        $('#printBtn').on('click', function(e) {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var singleDate = $('#singleDate').val();

            // Validasi tanggal
            if (startDate && endDate && startDate > endDate) {
                e.preventDefault();
                showAlert('danger', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir!');
                return;
            }

            // Update URL sebelum dibuka di tab baru
            updatePrintUrl(startDate, endDate, singleDate);
        });
    });
</script>

<style>
    .sortable {
        position: relative;
        cursor: pointer;
    }

    .sortable:hover {
        background-color: #f8f9fa;
    }

    .sort-icon {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 10px;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
            width: 100%;
        }

        .btn-group .btn {
            width: 100%;
            margin: 0.25rem 0;
        }

        table.table th,
        table.table td {
            font-size: 0.85rem;
        }

        .table-responsive {
            border: 0;
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 576px) {
        .card-header h6 {
            font-size: 0.9rem;
        }

        .form-group label {
            font-size: 0.85rem;
        }

        table.table th,
        table.table td {
            font-size: 0.75rem;
            padding: 0.5rem 0.25rem;
        }
    }
</style>
<?= $this->endSection() ?>