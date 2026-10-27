<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="mb-3 mb-sm-0">
        <h1 class="h3 mb-0 text-gray-800">Laporan Booking Bulanan</h1>
        <p class="mb-0 text-black">Laporan data booking berdasarkan bulan</p>
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
        <h6 class="m-0 font-weight-bold text-primary">Filter Bulan</h6>
    </div>
    <div class="card-body">
        <form id="filterForm">
            <div class="row mb-2">
                <div class="col-lg-6 col-md-8 mb-3 mb-md-0">
                    <div class="form-group">
                        <label for="monthInput">Bulan</label>
                        <div class="input-group">
                            <input type="month" class="form-control" id="monthInput" name="month" value="<?= isset($_GET['month']) ? $_GET['month'] : '' ?>">
                            <button type="button" id="filterBtn" class="btn btn-primary">
                                <i class="bi bi-filter me-1"></i> Filter
                            </button>
                            <button type="button" id="resetBtn" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Reset
                            </button>
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
                <h6 class="m-0 font-weight-bold text-primary">Data Booking Bulan: <span id="labelBulan">-</span></h6>
                <div id="loadingIndicator" class="spinner-border spinner-border-sm text-primary d-none" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="card-body">
                <!-- Instruksi -->
                <div id="instructionMessage" class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i> Pilih bulan terlebih dahulu untuk menampilkan data.
                </div>

                <div id="tableContent" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari data...">
                                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                    <i class="bi bi-search"></i> Cari
                                </button>
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
                                    <th class="sortable" data-sort="tanggal">Tanggal</th>
                                    <th class="sortable" data-sort="paket">Nama Paket</th>
                                    <th class="sortable" data-sort="harga">Harga Paket</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-end">Total Bulan Ini</th>
                                    <th id="totalBulanIni">Rp 0</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

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
        var tableData = [];
        var sortColumn = 'no';
        var sortDirection = 'asc';

        $('.sortable').css('cursor', 'pointer');
        $('.sortable').append(' <span class="sort-icon"></span>');

        var urlParams = new URLSearchParams(window.location.search);
        var monthParam = urlParams.get('month');
        if (monthParam) {
            $('#monthInput').val(monthParam);
            var range = monthToRange(monthParam);
            loadBookingData(range.start, range.end, '');
            updateBulanLabel(monthParam);
        }

        $('.sortable').on('click', function() {
            var column = $(this).data('sort');
            if (sortColumn === column) {
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                sortColumn = column;
                sortDirection = 'asc';
            }
            $('.sort-icon').html('');
            var icon = sortDirection === 'asc' ? '&#9650;' : '&#9660;';
            $(this).find('.sort-icon').html(icon);
            renderTable();
        });

        $('#searchBtn').on('click', function() {
            renderTable();
        });
        $('#searchInput').on('keyup', function(e) {
            if (e.key === 'Enter') renderTable();
        });

        function monthToRange(ym) {
            var parts = (ym || '').split('-');
            if (parts.length !== 2) return {
                start: '',
                end: ''
            };
            var year = parseInt(parts[0]);
            var month = parseInt(parts[1]);
            if (!year || !month) return {
                start: '',
                end: ''
            };
            var start = new Date(year, month - 1, 1);
            var end = new Date(year, month, 0);
            return {
                start: formatDateParam(start),
                end: formatDateParam(end)
            };
        }

        function formatDateParam(date) {
            var d = new Date(date),
                m = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                y = d.getFullYear();
            if (m.length < 2) m = '0' + m;
            if (day.length < 2) day = '0' + day;
            return [y, m, day].join('-');
        }

        function updateBulanLabel(ym) {
            if (!ym) {
                $('#labelBulan').text('-');
                return;
            }
            var date = new Date(ym + '-01');
            var formatter = new Intl.DateTimeFormat('id-ID', {
                month: 'long',
                year: 'numeric'
            });
            $('#labelBulan').text(formatter.format(date));
        }

        function loadBookingData(startDate, endDate, singleDate) {
            $('#loadingIndicator').removeClass('d-none');
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
                    if (response.success) {
                        tableData = [];
                        var processed = {};
                        var totalBulan = 0;
                        if (Array.isArray(response.data)) {
                            var no = 1;
                            response.data.forEach(function(booking) {
                                if (processed[booking.kdbooking]) return;
                                processed[booking.kdbooking] = true;

                                var paketNames = [];
                                var tanggalTampil = '';
                                var subtotal = 0;
                                if (Array.isArray(booking.details)) {
                                    booking.details.forEach(function(detail) {
                                        var paketInfo = detail.nama_paket;
                                        if (detail.deskripsi) paketInfo += " (" + detail.deskripsi + ")";
                                        paketNames.push(paketInfo);
                                        subtotal += parseFloat(detail.harga || 0);
                                        if (!tanggalTampil && detail.tgl) tanggalTampil = formatDate(detail.tgl);
                                    });
                                }

                                totalBulan += parseFloat(booking.total || 0);
                                tableData.push({
                                    no: no++,
                                    kdbooking: booking.kdbooking,
                                    pelanggan: booking.nama_lengkap,
                                    tanggal: tanggalTampil,
                                    paket: paketNames.join(', '),
                                    harga: 'Rp ' + formatNumber(subtotal),
                                    total: 'Rp ' + formatNumber(booking.total)
                                });
                            });
                        }
                        $('#totalBulanIni').text('Rp ' + formatNumber(totalBulan));
                        renderTable();
                        showAlert('success', response.message || 'Data berhasil dimuat');
                        updatePrintUrl(startDate, endDate);
                        if (tableData.length > 0) {
                            $('#instructionMessage').hide();
                            $('#tableContent').show();
                        } else {
                            $('#instructionMessage').html('<i class="bi bi-info-circle me-2"></i> Tidak ada data booking pada bulan ini.');
                            $('#instructionMessage').show();
                            $('#tableContent').hide();
                        }
                    } else {
                        showAlert('danger', response.message || 'Terjadi kesalahan saat memuat data');
                    }
                },
                error: function(xhr, status, error) {
                    showAlert('danger', 'Terjadi kesalahan pada server: ' + error);
                },
                complete: function() {
                    $('#loadingIndicator').addClass('d-none');
                }
            });
        }

        function renderTable() {
            var filteredData = [];
            var searchTerm = ($('#searchInput').val() || '').toString().toLowerCase();
            for (var i = 0; i < tableData.length; i++) {
                var item = tableData[i];
                if (!searchTerm) {
                    filteredData.push(item);
                    continue;
                }
                var match = false;
                for (var key in item) {
                    if (!item.hasOwnProperty(key)) continue;
                    var val = item[key];
                    if (val === null || val === undefined) continue;
                    try {
                        if (String(val).toLowerCase().indexOf(searchTerm) !== -1) {
                            match = true;
                            break;
                        }
                    } catch (e) {}
                }
                if (match) filteredData.push(item);
            }

            if (sortColumn) {
                filteredData.sort(function(a, b) {
                    var aVal = a[sortColumn],
                        bVal = b[sortColumn];
                    if (aVal == null) aVal = '';
                    if (bVal == null) bVal = '';
                    if (sortColumn === 'no') {
                        aVal = parseInt(String(aVal).replace(/[^\d]/g, '')) || 0;
                        bVal = parseInt(String(bVal).replace(/[^\d]/g, '')) || 0;
                    } else if (sortColumn === 'total' || sortColumn === 'harga') {
                        aVal = parseFloat(String(aVal).replace(/[^\d.,]/g, '').replace(/\./g, '').replace(',', '.')) || 0;
                        bVal = parseFloat(String(bVal).replace(/[^\d.,]/g, '').replace(/\./g, '').replace(',', '.')) || 0;
                    } else if (sortColumn === 'tanggal') {
                        var ad = parseDateString(String(aVal));
                        var bd = parseDateString(String(bVal));
                        aVal = ad ? ad.getTime() : 0;
                        bVal = bd ? bd.getTime() : 0;
                    } else {
                        return sortDirection === 'asc' ?
                            String(aVal).localeCompare(String(bVal), undefined, {
                                sensitivity: 'base'
                            }) :
                            String(bVal).localeCompare(String(aVal), undefined, {
                                sensitivity: 'base'
                            });
                    }
                    if (aVal < bVal) return sortDirection === 'asc' ? -1 : 1;
                    if (aVal > bVal) return sortDirection === 'asc' ? 1 : -1;
                    return 0;
                });
            }

            $('#tableInfo').text('Menampilkan ' + filteredData.length + ' data');
            var tbody = $('#bookingTable tbody');
            tbody.empty();
            if (filteredData.length === 0) {
                tbody.html('<tr><td colspan="7" class="text-center">Data booking tidak ditemukan.</td></tr>');
            } else {
                for (var i = 0; i < filteredData.length; i++) {
                    var it = filteredData[i] || {};
                    var row = '<tr>' +
                        '<td>' + (it.no || '') + '</td>' +
                        '<td>' + (it.kdbooking || '') + '</td>' +
                        '<td>' + (it.pelanggan || '') + '</td>' +
                        '<td>' + (it.tanggal || '') + '</td>' +
                        '<td>' + (it.paket || '') + '</td>' +
                        '<td>' + (it.harga || '') + '</td>' +
                        '</tr>';
                    tbody.append(row);
                }
            }
        }

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
                return null;
            }
        }

        function formatDate(dateStr) {
            try {
                if (!dateStr) return '';
                var date = new Date(dateStr);
                if (isNaN(date.getTime())) return dateStr;
                var day = date.getDate().toString().padStart(2, '0');
                var month = (date.getMonth() + 1).toString().padStart(2, '0');
                var year = date.getFullYear();
                return day + '/' + month + '/' + year;
            } catch (e) {
                return dateStr;
            }
        }

        function formatNumber(num) {
            try {
                if (num == null) return '0';
                return parseFloat(num).toLocaleString('id-ID');
            } catch (e) {
                return '0';
            }
        }

        function showAlert(type, message) {
            $('#alertMessage')
                .removeClass('d-none alert-success alert-danger')
                .addClass('alert-' + type)
                .find('#alertText').text(message);
            setTimeout(function() {
                $('#alertMessage').addClass('d-none');
            }, 5000);
        }

        function updatePrintUrl(startDate, endDate) {
            var printUrl = '<?= site_url('admin/reports/booking/print') ?>';
            var params = [];
            if (startDate) params.push('start_date=' + startDate);
            if (endDate) params.push('end_date=' + endDate);
            if (params.length > 0) printUrl += '?' + params.join('&');
            $('#printBtn').attr('href', printUrl);
        }

        // Events
        $('#filterBtn').on('click', function() {
            var ym = $('#monthInput').val();
            if (!ym) {
                alert('Silakan pilih bulan terlebih dahulu');
                return;
            }
            var range = monthToRange(ym);
            updateBulanLabel(ym);
            loadBookingData(range.start, range.end, '');
            var newUrl = new URL(window.location.href);
            newUrl.searchParams.set('month', ym);
            window.history.replaceState({}, '', newUrl.toString());
        });

        $('#resetBtn').on('click', function() {
            $('#monthInput').val('');
            $('#labelBulan').text('-');
            $('#instructionMessage').show();
            $('#tableContent').hide();
            tableData = [];
            var newUrl = new URL(window.location.href);
            newUrl.searchParams.delete('month');
            window.history.replaceState({}, '', newUrl.toString());
        });

        $('#printBtn').on('click', function(e) {
            var ym = $('#monthInput').val();
            if (!ym) return; // biarkan cetak semua jika tidak pilih bulan
            var range = monthToRange(ym);
            updatePrintUrl(range.start, range.end);
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

    @media (max-width: 768px) {

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

