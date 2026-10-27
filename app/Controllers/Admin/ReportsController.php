<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KaryawanModel;
use App\Models\PaketModel;
use App\Models\PelangganModel;
use App\Models\BookingModel;
use App\Models\DetailBookingModel;
use App\Models\PembayaranModel;
use App\Models\DetailPembayaranModel;

class ReportsController extends BaseController
{
    protected $karyawanModel;
    protected $paketModel;
    protected $pelangganModel;
    protected $bookingModel;
    protected $detailBookingModel;
    protected $pembayaranModel;
    protected $detailPembayaranModel;

    public function __construct()
    {
        $this->karyawanModel = new KaryawanModel();
        $this->paketModel = new PaketModel();
        $this->pelangganModel = new PelangganModel();
        $this->bookingModel = new BookingModel();
        $this->detailBookingModel = new DetailBookingModel();
        $this->pembayaranModel = new PembayaranModel();
        $this->detailPembayaranModel = new DetailPembayaranModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Laporan'
        ];
        return view('admin/reports/index', $data);
    }

    public function karyawan()
    {
        $data = [
            'title' => 'Laporan Karyawan',
            'karyawan' => $this->karyawanModel->findAll()
        ];
        return view('admin/reports/karyawan', $data);
    }

    public function printKaryawan()
    {
        $karyawan = $this->karyawanModel->findAll();


        $headerData = [
            'title' => 'Cetak Laporan Karyawan',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Adinegoro No.16, Batang Kabung Ganting, Kec. Koto Tangah Kota Padang, Sumatera Barat 25586',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal_label' => 'Tanggal: ' . date('d F Y'),
            'report_title' => 'LAPORAN DATA KARYAWAN',
            'manager' => 'Pimpinan'
        ];


        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Kode Karyawan</th>
                    <th>Nama Karyawan</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($karyawan as $k) {
            $jenkel = '-';
            if ($k['jenkel'] == 'L') {
                $jenkel = 'Laki-laki';
            } elseif ($k['jenkel'] == 'P') {
                $jenkel = 'Perempuan';
            }

            $content .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td>' . $k['idkaryawan'] . '</td>
                    <td>' . $k['namakaryawan'] . '</td>
                    <td class="text-center">' . $jenkel . '</td>
                    <td>' . ($k['alamat'] ?? '-') . '</td>
                    <td>' . ($k['nohp'] ?? '-') . '</td>
                </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end fw-bold">Total Karyawan:</td>
                    <td class="text-center fw-bold">' . count($karyawan) . ' orang</td>
                </tr>
            </tfoot>
        </table>';


        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function paket()
    {
        $data = [
            'title' => 'Laporan Paket',
            'paket' => $this->paketModel->findAll()
        ];
        return view('admin/reports/paket', $data);
    }

    public function printPaket()
    {
        $paket = $this->paketModel->findAll();


        $headerData = [
            'title' => 'Cetak Laporan Paket',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Adinegoro No.16, Batang Kabung Ganting, Kec. Koto Tangah Kota Padang, Sumatera Barat 25586',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal_label' => 'Tanggal: ' . date('d F Y'),
            'report_title' => 'LAPORAN DATA PAKET LAYANAN',
            'manager' => 'Pimpinan'
        ];


        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Kode Paket</th>
                    <th>Nama Paket</th>
                    <th>Jenis Paket</th>
                    <th class="text-center">Harga</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        $totalHarga = 0;
        foreach ($paket as $p) {
            $totalHarga += $p['harga'];

            $content .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td>' . $p['idpaket'] . '</td>
                    <td>' . $p['namapaket'] . '</td>
                    <td>' . ($p['deskripsi'] ?? '-') . '</td>
                    <td class="text-end">Rp ' . number_format($p['harga'], 0, ',', '.') . '</td>
                </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Total Paket:</td>
                    <td class="text-center fw-bold" colspan="2">' . count($paket) . ' paket</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Total Nilai Paket:</td>
                    <td class="text-end fw-bold" colspan="2">Rp ' . number_format($totalHarga, 0, ',', '.') . '</td>
                </tr>
            </tfoot>
        </table>';


        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function pelanggan()
    {
        $data = [
            'title' => 'Laporan Pelanggan',
            'pelanggan' => $this->pelangganModel->findAll()
        ];
        return view('admin/reports/pelanggan', $data);
    }

    public function printPelanggan()
    {

        $pelanggan = $this->pelangganModel->findAll();


        $headerData = [
            'title' => 'Cetak Laporan Pelanggan',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Adinegoro No.16, Batang Kabung Ganting, Kec. Koto Tangah Kota Padang, Sumatera Barat 25586',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal_label' => 'Tanggal: ' . date('d F Y'),
            'report_title' => 'LAPORAN DATA PELANGGAN',
            'manager' => 'Pimpinan'
        ];


        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Kode Pelanggan</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($pelanggan as $p) {
            $content .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td>' . $p['idpelanggan'] . '</td>
                    <td>' . $p['nama_lengkap'] . '</td>
                    <td>' . ($p['alamat'] ?? '-') . '</td>
                    <td>' . ($p['no_hp'] ?? '-') . '</td>
                </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Total Pelanggan:</td>
                    <td class="text-center fw-bold">' . count($pelanggan) . ' orang</td>
                </tr>
            </tfoot>
        </table>';


        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function booking()
    {

        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $singleDate = $this->request->getGet('single_date');


        $bookingData = [];


        if ($startDate || $endDate || $singleDate) {

            $bookings = $this->bookingModel->getBookingWithPelanggan();

            foreach ($bookings as $booking) {
                $details = $this->detailBookingModel->getDetailsByBookingCode($booking['kdbooking']);
                if (!empty($details)) {
                    $booking['details'] = $details;


                    if ($startDate || $endDate || $singleDate) {
                        $filteredDetails = [];
                        foreach ($details as $detail) {
                            $detailDate = date('Y-m-d', strtotime($detail['tgl']));

                            $includeDetail = true;

                            if ($singleDate && $detailDate != $singleDate) {
                                $includeDetail = false;
                            } else if (!$singleDate) {
                                if ($startDate && $detailDate < $startDate) {
                                    $includeDetail = false;
                                }
                                if ($endDate && $detailDate > $endDate) {
                                    $includeDetail = false;
                                }
                            }

                            if ($includeDetail) {
                                $filteredDetails[] = $detail;
                            }
                        }


                        if (!empty($filteredDetails)) {
                            $booking['details'] = $filteredDetails;
                            $bookingData[] = $booking;
                        }
                    }
                }
            }
        }

        $data = [
            'title' => 'Laporan Booking',
            'bookings' => $bookingData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'singleDate' => $singleDate
        ];
        return view('admin/reports/booking', $data);
    }

    public function bookingBulanan()
    {
        $data = [
            'title' => 'Laporan Booking Bulanan'
        ];
        return view('admin/reports/booking_bulanan', $data);
    }

    public function printBooking()
    {

        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $singleDate = $this->request->getGet('single_date');


        $bookings = $this->bookingModel->getBookingWithPelanggan();
        $bookingData = [];

        foreach ($bookings as $booking) {
            $details = $this->detailBookingModel->getDetailsByBookingCode($booking['kdbooking']);
            if (!empty($details)) {

                if ($startDate || $endDate || $singleDate) {
                    $filteredDetails = [];
                    foreach ($details as $detail) {
                        $detailDate = date('Y-m-d', strtotime($detail['tgl']));

                        $includeDetail = true;

                        if ($singleDate && $detailDate != $singleDate) {
                            $includeDetail = false;
                        } else if (!$singleDate) {
                            if ($startDate && $detailDate < $startDate) {
                                $includeDetail = false;
                            }
                            if ($endDate && $detailDate > $endDate) {
                                $includeDetail = false;
                            }
                        }

                        if ($includeDetail) {
                            $filteredDetails[] = $detail;
                        }
                    }


                    if (!empty($filteredDetails)) {
                        $booking['details'] = $filteredDetails;
                        $bookingData[] = $booking;
                    }
                } else {

                    $booking['details'] = $details;
                    $bookingData[] = $booking;
                }
            }
        }


        $reportTitle = 'LAPORAN DATA BOOKING';


        $tanggalLabel = '';
        if ($singleDate) {
            $tanggalLabel = 'Tanggal: ' . date('d/m/Y', strtotime($singleDate));
        } elseif ($startDate && $endDate) {
            $tanggalLabel = 'Tanggal: ' . date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate));
        } elseif ($startDate) {
            $tanggalLabel = 'Dari Tanggal: ' . date('d/m/Y', strtotime($startDate));
        } elseif ($endDate) {
            $tanggalLabel = 'Sampai Tanggal: ' . date('d/m/Y', strtotime($endDate));
        } else {
            $tanggalLabel = 'Tanggal: ' . date('d F Y'); // Tanggal hari ini jika tidak ada filter
        }


        $headerData = [
            'title' => 'Cetak Laporan Booking',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Adinegoro No.16, Batang Kabung Ganting, Kec. Koto Tangah Kota Padang, Sumatera Barat 25586',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal_label' => $tanggalLabel, // Kirim label tanggal ke template
            'report_title' => $reportTitle,
            'manager' => 'Pimpinan'
        ];


        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Kode Booking</th>
                    <th>Nama Pelanggan</th>
                    <th>Nama Paket</th>
                    <th class="text-center">Harga Paket</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        $totalBayar = 0;


        $processedCodes = [];

        foreach ($bookingData as $booking) {

            if (in_array($booking['kdbooking'], $processedCodes)) {
                continue;
            }


            $processedCodes[] = $booking['kdbooking'];


            $paketList = [];
            $totalHarga = 0;
            foreach ($booking['details'] as $detail) {
                $paketInfo = $detail['nama_paket'];
                if (!empty($detail['deskripsi'])) {
                    $paketInfo .= ' (' . $detail['deskripsi'] . ')';
                }
                $paketList[] = $paketInfo;

                $totalHarga += $detail['harga'] ?? 0;
            }


            $tanggal = date('d/m/Y', strtotime($booking['details'][0]['tgl']));

            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . $booking['kdbooking'] . '</td>
                <td>' . $booking['nama_lengkap'] . '</td>
                <td>' . implode(", ", $paketList) . '</td>
                <td class="text-end">Rp ' . number_format($totalHarga, 0, ',', '.') . '</td>
            </tr>';


            $totalBayar += $booking['total'];
        }

        $content .= '
            </tbody>
           
        </table>';


        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function pembayaran()
    {

        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $pembayaranData = [];


        if ($bulan || $tahun) {
            $pembayaran = $this->pembayaranModel->where('status', 'paid');


            if ($bulan && $tahun) {
                $pembayaran->where("DATE_FORMAT(created_at, '%m-%Y') = ", "$bulan-$tahun");
            } elseif ($bulan) {
                $pembayaran->where("DATE_FORMAT(created_at, '%m') = ", $bulan);
            } elseif ($tahun) {
                $pembayaran->where("DATE_FORMAT(created_at, '%Y') = ", $tahun);
            }

            $pembayaran = $pembayaran->findAll();

            foreach ($pembayaran as $p) {

                $booking = $this->bookingModel->getBookingWithPelanggan($p['fakturbooking']);
                if ($booking) {

                    $details = $this->detailBookingModel->getDetailsByBookingCode($p['fakturbooking']);
                    if (!empty($details)) {
                        $p['booking'] = $booking;
                        $p['details'] = $details;
                        $pembayaranData[] = $p;
                    }
                }
            }
        }

        $data = [
            'title' => 'Laporan Pembayaran',
            'pembayaran' => $pembayaranData
        ];
        return view('admin/reports/pembayaran', $data);
    }

    public function pembayaranPertanggal()
    {
        $data = [
            'title' => 'Laporan Pembayaran Pertanggal'
        ];
        return view('admin/reports/pembayaran_pertanggal', $data);
    }

    public function printPembayaranPertanggal()
    {
        $singleDate = $this->request->getGet('single_date');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $pembayaranData = [];
        $pembayaran = $this->pembayaranModel->where('status', 'paid');

        // Filter berdasarkan tanggal
        if ($singleDate) {
            $pembayaran->where('DATE(created_at)', $singleDate);
        } elseif ($startDate || $endDate) {
            if ($startDate) {
                $pembayaran->where('DATE(created_at) >=', $startDate);
            }
            if ($endDate) {
                $pembayaran->where('DATE(created_at) <=', $endDate);
            }
        }

        $pembayaran = $pembayaran->findAll();

        foreach ($pembayaran as $p) {
            $booking = $this->bookingModel->getBookingWithPelanggan($p['fakturbooking']);
            if ($booking) {
                $details = $this->detailBookingModel->getDetailsByBookingCode($p['fakturbooking']);
                if (!empty($details)) {
                    $p['booking'] = $booking;
                    $p['details'] = $details;
                    $pembayaranData[] = $p;
                }
            }
        }

        // Label periode
        $tanggalLabel = '';
        if ($singleDate) {
            $tanggalLabel = "Tanggal: " . date('d/m/Y', strtotime($singleDate));
        } elseif ($startDate && $endDate) {
            $tanggalLabel = "Periode: " . date('d/m/Y', strtotime($startDate)) . " s/d " . date('d/m/Y', strtotime($endDate));
        } elseif ($startDate) {
            $tanggalLabel = "Mulai: " . date('d/m/Y', strtotime($startDate));
        } elseif ($endDate) {
            $tanggalLabel = "Sampai: " . date('d/m/Y', strtotime($endDate));
        }

        $headerData = [
            'title' => 'Cetak Laporan Pembayaran Pertanggal',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Adinegoro No.16, Batang Kabung Ganting, Kec. Koto Tangah Kota Padang, Sumatera Barat 25586',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal_label' => $tanggalLabel,
            'report_title' => 'LAPORAN DATA PEMBAYARAN PERTANGGAL',
            'manager' => 'Pimpinan'
        ];

        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th width="15%">No Transaksi</th>
                    <th width="12%">Tanggal</th>
                    <th width="20%">Nama Pelanggan</th>
                    <th width="25%">Paket</th>
                    <th class="text-center" width="12%">Harga Paket</th>
                    <th class="text-center" width="12%">Total Bayar</th>
                    <th class="text-center" width="10%">Metode</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        $totalBayar = 0;
        $processedFaktur = [];

        foreach ($pembayaranData as $p) {
            // Skip jika faktur sudah diproses (untuk menghindari duplikat)
            
            $processedFaktur[] = $p['fakturbooking'];
            $totalBayar += $p['total_bayar'];

            $paketList = [];
            if (isset($p['details']) && is_array($p['details'])) {
                foreach ($p['details'] as $detail) {
                    $paketInfo = $detail['nama_paket'] ?? '';
                    if (!empty($detail['deskripsi'])) {
                        $paketInfo .= ' (' . $detail['deskripsi'] . ')';
                    }
                    $paketList[] = $paketInfo;
                }
            }

            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . ($p['fakturbooking'] ?? '') . '</td>
                <td>' . (isset($p['created_at']) ? date('d/m/Y', strtotime($p['created_at'])) : '') . '</td>
                <td>' . ($p['booking']['nama_lengkap'] ?? '') . '</td>
                <td>' . implode(', ', $paketList) . '</td>
                <td class="text-end">Rp ' . number_format(($p['grandtotal'] ?? 0), 0, ',', '.') . '</td>
                <td class="text-end">Rp ' . number_format(($p['total_bayar'] ?? 0), 0, ',', '.') . '</td>
                <td class="text-center">' . ucfirst($p['metode'] ?? '') . '</td>
            </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-end fw-bold">Total Seluruh Pembayaran:</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalBayar, 0, ',', '.') . '</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>';

        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function printPembayaranPerbulan()
    {
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $pembayaranData = [];
        $pembayaran = $this->pembayaranModel->where('status', 'paid');

        // Filter berdasarkan bulan dan tahun
        if ($bulan && $tahun) {
            $pembayaran->where("DATE_FORMAT(created_at, '%m-%Y') = ", "$bulan-$tahun");
        } elseif ($bulan) {
            $pembayaran->where("DATE_FORMAT(created_at, '%m') = ", $bulan);
        } elseif ($tahun) {
            $pembayaran->where("DATE_FORMAT(created_at, '%Y') = ", $tahun);
        }

        $pembayaran = $pembayaran->findAll();

        $processedFaktur = [];
        foreach ($pembayaran as $p) {
            // Skip jika faktur sudah diproses (untuk menghindari duplikat)
          
            $processedFaktur[] = $p['fakturbooking'];

            $booking = $this->bookingModel->getBookingWithPelanggan($p['fakturbooking']);
            if ($booking) {
                $details = $this->detailBookingModel->getDetailsByBookingCode($p['fakturbooking']);
                if (!empty($details)) {
                    $p['booking'] = $booking;
                    $p['details'] = $details;
                    $pembayaranData[] = $p;
                }
            }
        }

        // Label periode
        $tanggalLabel = '';
        if ($bulan && $tahun) {
            $namaBulan = $this->getNamaBulan($bulan);
            $tanggalLabel = "Bulan: $namaBulan $tahun";
        } elseif ($bulan) {
            $namaBulan = $this->getNamaBulan($bulan);
            $tanggalLabel = "Bulan: $namaBulan " . date('Y');
        } elseif ($tahun) {
            $tanggalLabel = "Tahun: $tahun";
        } else {
            $tanggalLabel = 'Bulan: Semua';
        }

        $headerData = [
            'title' => 'Cetak Laporan Pembayaran Perbulan',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Adinegoro No.16, Batang Kabung Ganting, Kec. Koto Tangah Kota Padang, Sumatera Barat 25586',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal_label' => $tanggalLabel,
            'report_title' => 'LAPORAN DATA PEMBAYARAN PERBULAN',
            'manager' => 'Pimpinan'
        ];

        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th width="15%">No Transaksi</th>
                    <th width="12%">Tanggal</th>
                    <th width="20%">Nama Pelanggan</th>
                    <th width="25%">Paket</th>
                    <th class="text-center" width="12%">Harga Paket</th>
                    <th class="text-center" width="12%">Total Bayar</th>
                    <th class="text-center" width="10%">Metode</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        $totalBayar = 0;

        foreach ($pembayaranData as $p) {
            $totalBayar += $p['total_bayar'];

            $paketList = [];
            if (isset($p['details']) && is_array($p['details'])) {
                foreach ($p['details'] as $detail) {
                    $paketInfo = $detail['nama_paket'] ?? '';
                    if (!empty($detail['deskripsi'])) {
                        $paketInfo .= ' (' . $detail['deskripsi'] . ')';
                    }
                    $paketList[] = $paketInfo;
                }
            }

            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . ($p['fakturbooking'] ?? '') . '</td>
                <td>' . (isset($p['created_at']) ? date('d/m/Y', strtotime($p['created_at'])) : '') . '</td>
                <td>' . ($p['booking']['nama_lengkap'] ?? '') . '</td>
                <td>' . implode(', ', $paketList) . '</td>
                <td class="text-end">Rp ' . number_format(($p['grandtotal'] ?? 0), 0, ',', '.') . '</td>
                <td class="text-end">Rp ' . number_format(($p['total_bayar'] ?? 0), 0, ',', '.') . '</td>
                <td class="text-center">' . ucfirst($p['metode'] ?? '') . '</td>
            </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-end fw-bold">Total Seluruh Pembayaran:</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalBayar, 0, ',', '.') . '</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>';

        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function printPembayaranPertahun()
    {
        $tahun = $this->request->getGet('tahun');

        $pembayaranData = [];
        $pembayaran = $this->pembayaranModel->where('status', 'paid');

        // Filter berdasarkan tahun
        if ($tahun) {
            $pembayaran->where("DATE_FORMAT(created_at, '%Y') = ", $tahun);
        }

        $pembayaran = $pembayaran->findAll();

        $processedFaktur = [];
        foreach ($pembayaran as $p) {
            // Skip jika faktur sudah diproses (untuk menghindari duplikat)
           
            $processedFaktur[] = $p['fakturbooking'];

            $booking = $this->bookingModel->getBookingWithPelanggan($p['fakturbooking']);
            if ($booking) {
                $details = $this->detailBookingModel->getDetailsByBookingCode($p['fakturbooking']);
                if (!empty($details)) {
                    $p['booking'] = $booking;
                    $p['details'] = $details;
                    $pembayaranData[] = $p;
                }
            }
        }

        // Label periode
        $tanggalLabel = '';
        if ($tahun) {
            $tanggalLabel = "Tahun: $tahun";
        } else {
            $tanggalLabel = 'Tahun: Semua';
        }

        $headerData = [
            'title' => 'Cetak Laporan Pembayaran Pertahun',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Adinegoro No.16, Batang Kabung Ganting, Kec. Koto Tangah Kota Padang, Sumatera Barat 25586',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal_label' => $tanggalLabel,
            'report_title' => 'LAPORAN DATA PEMBAYARAN PERTAHUN',
            'manager' => 'Pimpinan'
        ];

        // Inisialisasi array untuk semua bulan
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan = str_pad($i, 2, '0', STR_PAD_LEFT);
            $monthlyData[$bulan] = 0;
        }

        // Tambahkan data transaksi ke bulan yang sesuai
        $totalBayar = 0;
        foreach ($pembayaranData as $p) {
            $bulan = date('m', strtotime($p['created_at']));
            $monthlyData[$bulan] += $p['total_bayar'];
            $totalBayar += $p['total_bayar'];
        }

        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Bulan</th>
                    <th class="text-center">Total Bayar</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;

        // Tampilkan semua bulan
        foreach ($monthlyData as $bulan => $total) {
            $namaBulan = $this->getNamaBulan($bulan);
            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . $namaBulan . '</td>
                <td class="text-end">Rp ' . number_format($total, 0, ',', '.') . '</td>
            </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-end fw-bold">Total Seluruh Pembayaran:</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalBayar, 0, ',', '.') . '</td>
                </tr>
            </tfoot>
        </table>';

        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function getPembayaranPertanggalData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $singleDate = $this->request->getGet('single_date');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $pembayaranData = [];
        $pembayaran = $this->pembayaranModel->where('status', 'paid');

        // Filter berdasarkan tanggal
        if ($singleDate) {
            $pembayaran->where('DATE(created_at)', $singleDate);
        } elseif ($startDate || $endDate) {
            if ($startDate) {
                $pembayaran->where('DATE(created_at) >=', $startDate);
            }
            if ($endDate) {
                $pembayaran->where('DATE(created_at) <=', $endDate);
            }
        }

        $pembayaran = $pembayaran->findAll();
        $totalData = 0;

        $processedFaktur = [];
        foreach ($pembayaran as $p) {
            $booking = $this->bookingModel->getBookingWithPelanggan($p['fakturbooking']);
            if ($booking) {
                $details = $this->detailBookingModel->getDetailsByBookingCode($p['fakturbooking']);
                if (!empty($details)) {
                    $p['booking'] = $booking;
                    $p['details'] = $details;
                    $pembayaranData[] = $p;
                }
            }
        }

        // Generate HTML untuk tabel
        $html = '';
        $no = 1;

        foreach ($pembayaranData as $p) {
            // Skip jika faktur sudah diproses (untuk menghindari duplikat)
         
            $processedFaktur[] = $p['fakturbooking'];

            $paketList = [];
            if (isset($p['details']) && is_array($p['details'])) {
                foreach ($p['details'] as $detail) {
                    $paketInfo = $detail['nama_paket'] ?? '';
                    if (!empty($detail['deskripsi'])) {
                        $paketInfo .= ' (' . $detail['deskripsi'] . ')';
                    }
                    $paketList[] = $paketInfo;
                }
            }

            $html .= '<tr>';
            $html .= '<td class="text-center">' . $no++ . '</td>';
            $html .= '<td>' . ($p['fakturbooking'] ?? '') . '</td>';
            $html .= '<td>' . (isset($p['created_at']) ? date('d/m/Y', strtotime($p['created_at'])) : '') . '</td>';
            $html .= '<td>' . ($p['booking']['nama_lengkap'] ?? '') . '</td>';
            $html .= '<td>' . implode(", ", $paketList) . '</td>';
            $html .= '<td class="text-end">Rp ' . number_format(($p['grandtotal'] ?? 0), 0, ',', '.') . '</td>';
            $html .= '<td class="text-end">Rp ' . number_format(($p['total_bayar'] ?? 0), 0, ',', '.') . '</td>';
            $html .= '<td class="text-center">' . ucfirst($p['metode'] ?? '') . '</td>';
            $html .= '</tr>';
            $totalData++;
        }

        if ($totalData === 0) {
            $html = '<tr><td colspan="8" class="text-center">Tidak ada data yang ditemukan</td></tr>';
        }

        // Pesan berdasarkan filter
        $pesan = 'Data berhasil dimuat';
        if ($singleDate) {
            $pesan = 'Data pembayaran tanggal ' . date('d/m/Y', strtotime($singleDate)) . ' berhasil dimuat';
        } elseif ($startDate && $endDate) {
            $pesan = 'Data pembayaran periode ' . date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate)) . ' berhasil dimuat';
        } elseif ($startDate) {
            $pesan = 'Data pembayaran dari tanggal ' . date('d/m/Y', strtotime($startDate)) . ' berhasil dimuat';
        } elseif ($endDate) {
            $pesan = 'Data pembayaran sampai tanggal ' . date('d/m/Y', strtotime($endDate)) . ' berhasil dimuat';
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $pesan,
            'html' => $html,
            'data' => $pembayaranData,
            'total' => $totalData
        ]);
    }

    public function pembayaranPertahun()
    {
        $data = [
            'title' => 'Laporan Pembayaran Pertahun'
        ];
        return view('admin/reports/pembayaran', $data);
    }

    public function printPembayaran()
    {

        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $pembayaranData = [];
        $pembayaran = $this->pembayaranModel->where('status', 'paid');


        if ($bulan && $tahun) {
            $pembayaran->where("DATE_FORMAT(created_at, '%m-%Y') = ", "$bulan-$tahun");
        } elseif ($bulan) {
            $pembayaran->where("DATE_FORMAT(created_at, '%m') = ", $bulan);
        } elseif ($tahun) {
            $pembayaran->where("DATE_FORMAT(created_at, '%Y') = ", $tahun);
        }

        $pembayaran = $pembayaran->findAll();

        $processedFaktur = [];
        foreach ($pembayaran as $p) {
            // Skip jika faktur sudah diproses (untuk menghindari duplikat)
           
            $processedFaktur[] = $p['fakturbooking'];

            $booking = $this->bookingModel->getBookingWithPelanggan($p['fakturbooking']);
            if ($booking) {
                $details = $this->detailBookingModel->getDetailsByBookingCode($p['fakturbooking']);
                if (!empty($details)) {
                    $p['booking'] = $booking;
                    $p['details'] = $details;
                    $pembayaranData[] = $p;
                }
            }
        }


        // Mendukung parameter tanggal_awal dan tanggal_akhir
        $tanggalLabel = '';
        $tanggalAwal = $this->request->getGet('start_date');
        $tanggalAkhir = $this->request->getGet('end_date');

        if ($tanggalAwal && $tanggalAkhir) {
            $tanggalLabel = "Periode: " . date('d/m/Y', strtotime($tanggalAwal)) . " s/d " . date('d/m/Y', strtotime($tanggalAkhir));
        } elseif ($tanggalAwal) {
            $tanggalLabel = "Mulai: " . date('d/m/Y', strtotime($tanggalAwal));
        } elseif ($tanggalAkhir) {
            $tanggalLabel = "Sampai: " . date('d/m/Y', strtotime($tanggalAkhir));
        } elseif ($bulan && $tahun) {
            $namaBulan = $this->getNamaBulan($bulan);
            $tanggalLabel = "Bulan: $namaBulan $tahun";
        } elseif ($bulan) {
            $namaBulan = $this->getNamaBulan($bulan);
            $tanggalLabel = "Bulan: $namaBulan " . date('Y');
        } elseif ($tahun) {
            $tanggalLabel = "Tahun: $tahun";
        } else {
            $tanggalLabel = 'Bulan: Semua';
        }


        $headerData = [
            'title' => 'Cetak Laporan Pembayaran',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Adinegoro No.16, Batang Kabung Ganting, Kec. Koto Tangah Kota Padang, Sumatera Barat 25586',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal_label' => $tanggalLabel,
            'report_title' => 'LAPORAN DATA PEMBAYARAN',
            'manager' => 'Pimpinan'
        ];


        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Bulan</th>
                    <th class="text-center">Total Bayar</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        $totalBayar = 0;

        // Inisialisasi array untuk semua bulan
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan = str_pad($i, 2, '0', STR_PAD_LEFT);
            $monthlyData[$bulan] = 0;
        }

        // Tambahkan data transaksi ke bulan yang sesuai
        foreach ($pembayaranData as $p) {
            $bulan = date('m', strtotime($p['created_at']));
            $monthlyData[$bulan] += $p['total_bayar'];
            $totalBayar += $p['total_bayar'];
        }

        // Tampilkan semua bulan
        foreach ($monthlyData as $bulan => $total) {
            $namaBulan = $this->getNamaBulan($bulan);
            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . $namaBulan . '</td>
                <td class="text-end">Rp ' . number_format($total, 0, ',', '.') . '</td>
            </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-end fw-bold">Total Seluruh Pembayaran:</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalBayar, 0, ',', '.') . '</td>
                </tr>
            </tfoot>
        </table>';


        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function pendapatanBulanan()
    {

        $this->db->query("SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");


        $queryTahun = $this->db->query(
            "SELECT DISTINCT YEAR(created_at) as tahun FROM pembayaran WHERE status = 'paid' ORDER BY tahun DESC"
        );
        $daftarTahun = $queryTahun->getResultArray();


        $pendapatanBulanan = [];
        $totalPendapatan = 0;
        $tahun = date('Y');
        $bulan = date('m');


        $filterSelected = $this->request->getGet('tahun') && $this->request->getGet('bulan');

        if ($filterSelected) {
            $tahun = $this->request->getGet('tahun');
            $bulan = $this->request->getGet('bulan');


            $query = $this->db->query(
                "SELECT 
                    DATE(db.tgl) as tanggal,
                    db.nama_paket,
                    db.harga as harga_satuan,
                    COUNT(db.iddetail) as jumlah,
                    SUM(db.harga) as total
                FROM 
                    detail_booking db
                WHERE 
                    db.status != 4
                    AND MONTH(db.tgl) = ?
                    AND YEAR(db.tgl) = ?
                GROUP BY 
                    DATE(db.tgl), db.nama_paket, db.harga
                ORDER BY 
                    DATE(db.tgl) ASC, db.nama_paket ASC",
                [$bulan, $tahun]
            );

            $pendapatanBulanan = $query->getResultArray();


            foreach ($pendapatanBulanan as $item) {
                $totalPendapatan += $item['total'];
            }
        }

        $data = [
            'title' => 'Laporan Uang Masuk Bulanan',
            'pendapatan' => $pendapatanBulanan,
            'totalPendapatan' => $totalPendapatan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'daftarTahun' => $daftarTahun
        ];

        return view('admin/reports/pendapatan_bulanan', $data);
    }

    public function getPendapatanBulananData()
    {

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }


        $tahun = $this->request->getGet('tahun');
        $bulan = $this->request->getGet('bulan');


        $showAll = $this->request->getGet('show_all') === 'true';

        if (!$showAll && (empty($tahun) || empty($bulan))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parameter tidak lengkap'
            ]);
        }


        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];


        $this->db->query("SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");

        $sql = "SELECT 
                DATE(db.tgl) as tanggal,
                db.nama_paket,
                db.harga as harga_satuan,
                COUNT(db.iddetail) as jumlah,
                SUM(db.harga) as total
            FROM 
                detail_booking db
            WHERE 
                db.status != 4";  // Status 4 adalah dibatalkan


        if (!$showAll) {
            $sql .= " AND MONTH(db.tgl) = '$bulan' AND YEAR(db.tgl) = '$tahun'";
        }


        $sql .= " GROUP BY 
                DATE(db.tgl), db.nama_paket, db.harga
            ORDER BY 
                DATE(db.tgl) DESC, db.nama_paket ASC";


        if ($showAll) {
            $sql .= " LIMIT 100";
        }

        $query = $this->db->query($sql);
        $pendapatanBulanan = $query->getResultArray();


        $formattedData = [];
        $totalPendapatan = 0;

        foreach ($pendapatanBulanan as $item) {
            $formattedData[] = [
                'tanggal' => date('d/m/Y', strtotime($item['tanggal'])),
                'nama_paket' => $item['nama_paket'],
                'harga_satuan' => $item['harga_satuan'],
                'jumlah' => $item['jumlah'],
                'total' => $item['total'],
                'total_formatted' => 'Rp ' . number_format($item['total'], 0, ',', '.')
            ];

            $totalPendapatan += $item['total'];
        }


        $periodeLabel = $showAll ? 'Semua Data' : 'Bulan: ' . $namaBulan[$bulan] . ' ' . $tahun;

        return $this->response->setJSON([
            'success' => true,
            'data' => $formattedData,
            'totalPendapatan' => $totalPendapatan,
            'totalPendapatanFormatted' => 'Rp ' . number_format($totalPendapatan, 0, ',', '.'),
            'periodeLabel' => $periodeLabel
        ]);
    }

    public function printPendapatanBulanan()
    {

        $this->db->query("SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");

        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $bulan = $this->request->getGet('bulan') ?? date('m');


        $query = $this->db->query(
            "SELECT 
                DATE(db.tgl) as tanggal,
                db.nama_paket,
                db.harga as harga_satuan,
                COUNT(db.iddetail) as jumlah,
                SUM(db.harga) as total
            FROM 
                detail_booking db
            WHERE 
                db.status != 4
                AND MONTH(db.tgl) = ?
                AND YEAR(db.tgl) = ?
            GROUP BY 
                DATE(db.tgl), db.nama_paket, db.harga
            ORDER BY 
                DATE(db.tgl) ASC, db.nama_paket ASC",
            [$bulan, $tahun]
        );

        $pendapatanBulanan = $query->getResultArray();


        $totalPendapatan = 0;
        foreach ($pendapatanBulanan as $item) {
            $totalPendapatan += $item['total'];
        }


        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];


        $headerData = [
            'title' => 'Cetak Laporan Uang Masuk Bulanan',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Adinegoro No.16, Batang Kabung Ganting, Kec. Koto Tangah Kota Padang, Sumatera Barat 25586',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => 'Bulan: ' . $namaBulan[$bulan] . ' ' . $tahun,
            'tanggal_label' => 'Bulan: ' . $namaBulan[$bulan] . ' ' . $tahun,
            'report_title' => 'LAPORAN PENDAPATAN PERBULAN',
            'manager' => 'Pimpinan'
        ];




        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Tanggal</th>
                    <th>Nama Paket</th>
                   
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;

        foreach ($pendapatanBulanan as $item) {
            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . date('d/m/Y', strtotime($item['tanggal'])) . '</td>
                <td>' . $item['nama_paket'] . '</td>
               
                <td class="text-end">Rp ' . number_format($item['total'], 0, ',', '.') . '</td>
            </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total Pendapatan:</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalPendapatan, 0, ',', '.') . '</td>
                </tr>
            </tfoot>
        </table>';


        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function pendapatanTahunan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Initialize array for all 12 months
        $pendapatanPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $pendapatanPerBulan[$i] = [
                'bulan' => $i,
                'tahun' => $tahun,
                'total' => 0
            ];
        }

        $this->db->query("SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");

        $query = $this->db->query(
            "SELECT 
                MONTH(p.created_at) as bulan,
                YEAR(p.created_at) as tahun,
                SUM(p.total_bayar) as total
            FROM 
                pembayaran p
            WHERE 
                p.status = 'paid'
                AND YEAR(p.created_at) = ?
            GROUP BY 
                MONTH(p.created_at), YEAR(p.created_at)
            ORDER BY 
                bulan ASC",
            [$tahun]
        );

        $result = $query->getResultArray();

        $totalPendapatan = 0;
        foreach ($result as $row) {
            $pendapatanPerBulan[$row['bulan']] = $row;
            $totalPendapatan += $row['total'];
        }

        $data = [
            'title' => 'Laporan Uang Masuk Tahunan',
            'pendapatan' => $pendapatanPerBulan,
            'totalPendapatan' => $totalPendapatan,
            'tahun' => $tahun
        ];

        return view('admin/reports/pendapatan_tahunan', $data);
    }

    public function getPendapatanTahunanData()
    {

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }


        $this->db->query("SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");


        $tahun = $this->request->getGet('tahun');
        $showAll = $this->request->getGet('show_all') === 'true';

        if (!$showAll && empty($tahun)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parameter tidak lengkap'
            ]);
        }

        $totalPendapatan = 0;
        $formattedData = [];

        if (!$showAll && $tahun) {
            // Logic for a specific year: ensure all 12 months are present
            $monthlyData = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthlyData[$i] = [
                    'bulan' => $i,
                    'tahun' => $tahun,
                    'total' => 0
                ];
            }

            $sql = "SELECT 
                    MONTH(p.created_at) as bulan,
                    SUM(p.total_bayar) as total
                FROM 
                    pembayaran p
                WHERE 
                    p.status = 'paid' AND YEAR(p.created_at) = ?
                GROUP BY 
                    MONTH(p.created_at)
                ORDER BY 
                    bulan ASC";

            $query = $this->db->query($sql, [$tahun]);
            $result = $query->getResultArray();

            foreach ($result as $row) {
                $monthlyData[$row['bulan']]['total'] = $row['total'];
            }

            foreach ($monthlyData as $item) {
                $formattedData[] = [
                    'bulan' => $item['bulan'],
                    'tahun' => $item['tahun'],
                    'bulan_nama' => $this->getNamaBulan($item['bulan']),
                    'total' => $item['total']
                ];
                $totalPendapatan += $item['total'];
            }
        } else {
            // New logic for "Show All"
            $yearQuery = $this->db->query("SELECT DISTINCT YEAR(created_at) as tahun FROM pembayaran WHERE status = 'paid' ORDER BY tahun ASC");
            $years = $yearQuery->getResultArray();

            $allData = [];
            foreach ($years as $y) {
                $currentYear = $y['tahun'];
                $monthlyData = [];
                for ($i = 1; $i <= 12; $i++) {
                    $monthlyData[$i] = [
                        'bulan' => $i,
                        'tahun' => $currentYear,
                        'total' => 0
                    ];
                }

                $sql = "SELECT MONTH(p.created_at) as bulan, SUM(p.total_bayar) as total
                        FROM pembayaran p
                        WHERE p.status = 'paid' AND YEAR(p.created_at) = ?
                        GROUP BY MONTH(p.created_at)
                        ORDER BY bulan ASC";
                $query = $this->db->query($sql, [$currentYear]);
                $result = $query->getResultArray();

                foreach ($result as $row) {
                    $monthlyData[$row['bulan']]['total'] = $row['total'];
                }

                foreach ($monthlyData as $item) {
                    $allData[] = [
                        'bulan' => $item['bulan'],
                        'tahun' => $item['tahun'],
                        'bulan_nama' => $this->getNamaBulan($item['bulan']),
                        'total' => $item['total']
                    ];
                    $totalPendapatan += $item['total'];
                }
            }
            $formattedData = $allData;
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $formattedData,
            'totalPendapatan' => $totalPendapatan,
            'message' => 'Data berhasil dimuat'
        ]);
    }

    public function printPendapatanTahunan()
    {
        $this->db->query("SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
        $tahun = $this->request->getGet('tahun');

        $pendapatanBulanan = [];
        $totalPendapatan = 0;
        $headerData = [
            'title' => 'Cetak Laporan Pendapatan Tahunan',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Adinegoro No.16, Batang Kabung Ganting, Kec. Koto Tangah Kota Padang, Sumatera Barat 25586',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'report_title' => 'LAPORAN PENDAPATAN TAHUNAN',
            'manager' => 'Pimpinan'
        ];

        if ($tahun) {
            // Case 1: A specific year is selected
            $headerData['tanggal_label'] = 'Tahun: ' . $tahun;

            $monthlyData = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthlyData[$i] = ['bulan' => $i, 'total' => 0];
            }

            $sql = "SELECT MONTH(p.created_at) as bulan, SUM(p.total_bayar) as total
                    FROM pembayaran p
                    WHERE p.status = 'paid' AND YEAR(p.created_at) = ?
                    GROUP BY MONTH(p.created_at)
                    ORDER BY bulan ASC";
            $query = $this->db->query($sql, [$tahun]);
            $result = $query->getResultArray();

            foreach ($result as $row) {
                $monthlyData[$row['bulan']]['total'] = $row['total'];
            }
            $pendapatanBulanan = array_values($monthlyData);
        } else {
            // Case 2: "Show All" is selected
            $headerData['tanggal_label'] = 'Semua Tahun';

            $yearQuery = $this->db->query("SELECT DISTINCT YEAR(created_at) as tahun FROM pembayaran WHERE status = 'paid' ORDER BY tahun ASC");
            $years = $yearQuery->getResultArray();

            $allData = [];
            foreach ($years as $y) {
                $currentYear = $y['tahun'];
                $monthlyData = [];
                for ($i = 1; $i <= 12; $i++) {
                    $monthlyData[$i] = ['bulan' => $i, 'tahun' => $currentYear, 'total' => 0];
                }

                $sql = "SELECT MONTH(p.created_at) as bulan, SUM(p.total_bayar) as total
                        FROM pembayaran p
                        WHERE p.status = 'paid' AND YEAR(p.created_at) = ?
                        GROUP BY MONTH(p.created_at)
                        ORDER BY bulan ASC";
                $query = $this->db->query($sql, [$currentYear]);
                $result = $query->getResultArray();

                foreach ($result as $row) {
                    $monthlyData[$row['bulan']]['total'] = $row['total'];
                }
                $allData = array_merge($allData, array_values($monthlyData));
            }
            $pendapatanBulanan = $allData;
        }

        // Calculate total
        foreach ($pendapatanBulanan as $item) {
            $totalPendapatan += $item['total'];
        }

        // Generate HTML content for the print view
        $content = '<table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Bulan</th>';
        if (!$tahun) {
            $content .= '<th>Tahun</th>';
        }
        $content .= '<th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($pendapatanBulanan as $item) {
            $namaBulan = $this->getNamaBulan($item['bulan']);
            $content .= '<tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . $namaBulan . '</td>';
            if (!$tahun) {
                $content .= '<td>' . $item['tahun'] . '</td>';
            }
            $content .= '<td class="text-end">Rp ' . number_format($item['total'], 0, ',', '.') . '</td>
            </tr>';
        }

        $colspan = $tahun ? 2 : 3;
        $content .= '</tbody>
            <tfoot>
                <tr>
                    <td colspan="' . $colspan . '" class="text-end fw-bold">Total :</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalPendapatan, 0, ',', '.') . '</td>
                </tr>
            </tfoot>
        </table>';

        $data = array_merge($headerData, ['content' => $content]);
        return view('admin/reports/template_laporan', $data);
    }

    public function pengeluaran()
    {

        $queryTahun = $this->db->query(
            "SELECT DISTINCT YEAR(tgl) as tahun FROM pengeluaran ORDER BY tahun DESC"
        );
        $daftarTahun = $queryTahun->getResultArray();


        $pengeluaran = [];
        $totalPengeluaran = 0;
        $tahun = date('Y');
        $bulan = date('m');

        $data = [
            'title' => 'Laporan Pengeluaran',
            'pengeluaran' => $pengeluaran,
            'totalPengeluaran' => $totalPengeluaran,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'daftarTahun' => $daftarTahun
        ];

        return view('admin/reports/pengeluaran', $data);
    }

    public function pengeluaranBulanan()
    {

        $queryTahun = $this->db->query(
            "SELECT DISTINCT YEAR(tgl) as tahun FROM pengeluaran ORDER BY tahun DESC"
        );
        $daftarTahun = $queryTahun->getResultArray();


        $data = [
            'title' => 'Laporan Pengeluaran Bulanan',
            'daftarTahun' => $daftarTahun,
            'bulan' => date('m'),
            'tahun' => date('Y')
        ];

        return view('admin/reports/pengeluaran_bulanan', $data);
    }

    public function getPengeluaranBulananData()
    {

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }


        $tahun = $this->request->getGet('tahun');
        $bulan = $this->request->getGet('bulan');
        $showAll = $this->request->getGet('show_all') === 'true';

        if (!$showAll && (empty($tahun) || empty($bulan))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parameter tidak lengkap'
            ]);
        }

        $pengeluaranModel = new \App\Models\PengeluaranModel();
        $builder = $pengeluaranModel->builder();

        if (!$showAll) {
            $builder->where('MONTH(tgl)', $bulan);
            $builder->where('YEAR(tgl)', $tahun);
        } else {
            $builder->limit(100);
        }

        $builder->orderBy('tgl', 'ASC');
        $rows = $builder->get()->getResultArray();

        $formatted = [];
        $total = 0;
        foreach ($rows as $r) {
            $formatted[] = [
                'id' => $r['idpengeluaran'],
                'tanggal' => date('d/m/Y', strtotime($r['tgl'])),
                'tgl_raw' => $r['tgl'],
                'keterangan' => $r['keterangan'],
                'jumlah' => $r['jumlah'],
                'jumlah_formatted' => 'Rp ' . number_format($r['jumlah'], 0, ',', '.')
            ];
            $total += $r['jumlah'];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $formatted,
            'total' => $total,
            'total_formatted' => 'Rp ' . number_format($total, 0, ',', '.')
        ]);
    }

    public function printPengeluaranBulanan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $bulan = $this->request->getGet('bulan') ?? date('m');

        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $pengeluaranModel = new \App\Models\PengeluaranModel();
        $builder = $pengeluaranModel->builder();
        $builder->where('MONTH(tgl)', $bulan);
        $builder->where('YEAR(tgl)', $tahun);
        $builder->orderBy('tgl', 'ASC');
        $rows = $builder->get()->getResultArray();

        $total = 0;
        foreach ($rows as $r) {
            $total += $r['jumlah'];
        }

        $headerData = [
            'title' => 'Cetak Laporan Pengeluaran Bulanan',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Adinegoro No.16, Batang Kabung Ganting, Kec. Koto Tangah Kota Padang, Sumatera Barat 25586',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal_label' => 'Bulan: ' . ($namaBulan[$bulan] ?? $bulan) . ' ' . $tahun,
            'report_title' => 'LAPORAN PENGELUARAN PERBULAN',
            'manager' => 'Pimpinan'
        ];

        $content = '        <table class="table table-bordered">            <thead>                <tr>                    <th class="text-center" width="5%">No</th>                    <th width="15%">Tanggal</th>                    <th>Keterangan</th>                    <th class="text-center">Jumlah</th>                </tr>            </thead>            <tbody>';

        $no = 1;
        foreach ($rows as $r) {
            $content .= '            <tr>                <td class="text-center">' . $no++ . '</td>                <td>' . date('d/m/Y', strtotime($r['tgl'])) . '</td>                <td>' . $r['keterangan'] . '</td>                <td class="text-end">Rp ' . number_format($r['jumlah'], 0, ',', '.') . '</td>            </tr>';
        }

        $content .= '            </tbody>            <tfoot>                <tr>                    <td colspan="3" class="text-end fw-bold">Total:</td>                    <td class="text-end fw-bold">Rp ' . number_format($total, 0, ',', '.') . '</td>                </tr>            </tfoot>        </table>';

        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function pengeluaranTahunan()
    {
        $queryTahun = $this->db->query(
            "SELECT DISTINCT YEAR(tgl) as tahun FROM pengeluaran ORDER BY tahun DESC"
        );
        $daftarTahun = $queryTahun->getResultArray();

        $data = [
            'title' => 'Laporan Pengeluaran Tahunan',
            'daftarTahun' => $daftarTahun,
            'tahun' => date('Y')
        ];

        return view('admin/reports/pengeluaran_tahunan', $data);
    }

    public function getPengeluaranTahunanData()
    {

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        $tahun = $this->request->getGet('tahun');
        $showAll = $this->request->getGet('show_all') === 'true';

        if (!$showAll && empty($tahun)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parameter tidak lengkap'
            ]);
        }

        $formatted = [];
        $total = 0;

        if (!$showAll && $tahun) {
            // Specific year selected
            $monthlyData = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthlyData[$i] = [
                    'bulan' => $i,
                    'tahun' => $tahun,
                    'total' => 0
                ];
            }

            $sql = "SELECT MONTH(tgl) as bulan, SUM(jumlah) as total FROM pengeluaran WHERE YEAR(tgl) = ? GROUP BY MONTH(tgl) ORDER BY bulan ASC";
            $query = $this->db->query($sql, [$tahun]);
            $result = $query->getResultArray();

            foreach ($result as $row) {
                $monthlyData[$row['bulan']]['total'] = $row['total'];
            }

            foreach ($monthlyData as $item) {
                $formatted[] = [
                    'bulan' => $item['bulan'],
                    'tahun' => $item['tahun'],
                    'bulan_nama' => $this->getNamaBulan($item['bulan']),
                    'total' => $item['total'],
                    'total_formatted' => 'Rp ' . number_format($item['total'], 0, ',', '.')
                ];
                $total += $item['total'];
            }
        } else {
            // Show All selected
            $yearQuery = $this->db->query("SELECT DISTINCT YEAR(tgl) as tahun FROM pengeluaran ORDER BY tahun ASC");
            $years = $yearQuery->getResultArray();

            foreach ($years as $y) {
                $currentYear = $y['tahun'];
                $monthlyData = [];
                for ($i = 1; $i <= 12; $i++) {
                    $monthlyData[$i] = [
                        'bulan' => $i,
                        'tahun' => $currentYear,
                        'total' => 0
                    ];
                }

                $sql = "SELECT MONTH(tgl) as bulan, SUM(jumlah) as total FROM pengeluaran WHERE YEAR(tgl) = ? GROUP BY MONTH(tgl) ORDER BY bulan ASC";
                $query = $this->db->query($sql, [$currentYear]);
                $result = $query->getResultArray();

                foreach ($result as $row) {
                    $monthlyData[$row['bulan']]['total'] = $row['total'];
                }

                foreach ($monthlyData as $item) {
                    $formatted[] = [
                        'bulan' => $item['bulan'],
                        'tahun' => $item['tahun'],
                        'bulan_nama' => $this->getNamaBulan($item['bulan']),
                        'total' => $item['total'],
                        'total_formatted' => 'Rp ' . number_format($item['total'], 0, ',', '.')
                    ];
                    $total += $item['total'];
                }
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $formatted,
            'total' => $total,
            'total_formatted' => 'Rp ' . number_format($total, 0, ',', '.')
        ]);
    }

    public function printPengeluaranTahunan()
    {
        $tahun = $this->request->getGet('tahun');

        $rows = [];
        $total = 0;

        $headerData = [
            'title' => 'Cetak Laporan Pengeluaran Tahunan',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Adinegoro No.16, Batang Kabung Ganting, Kec. Koto Tangah Kota Padang, Sumatera Barat 25586',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'report_title' => 'LAPORAN PENGELUARAN PERTAHUN',
            'manager' => 'Pimpinan'
        ];

        if ($tahun) {
            // Specific year
            $headerData['tanggal_label'] = 'Tahun: ' . $tahun;
            $monthlyData = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthlyData[$i] = ['bulan' => $i, 'tahun' => $tahun, 'total' => 0];
            }

            $sql = "SELECT MONTH(tgl) as bulan, SUM(jumlah) as total FROM pengeluaran WHERE YEAR(tgl) = ? GROUP BY MONTH(tgl) ORDER BY bulan ASC";
            $query = $this->db->query($sql, [$tahun]);
            $result = $query->getResultArray();

            foreach ($result as $row) {
                $monthlyData[$row['bulan']]['total'] = $row['total'];
            }
            $rows = array_values($monthlyData);
        } else {
            // Show All
            $headerData['tanggal_label'] = 'Semua Data';
            $yearQuery = $this->db->query("SELECT DISTINCT YEAR(tgl) as tahun FROM pengeluaran ORDER BY tahun ASC");
            $years = $yearQuery->getResultArray();

            foreach ($years as $y) {
                $currentYear = $y['tahun'];
                $monthlyData = [];
                for ($i = 1; $i <= 12; $i++) {
                    $monthlyData[$i] = ['bulan' => $i, 'tahun' => $currentYear, 'total' => 0];
                }

                $sql = "SELECT MONTH(tgl) as bulan, SUM(jumlah) as total FROM pengeluaran WHERE YEAR(tgl) = ? GROUP BY MONTH(tgl) ORDER BY bulan ASC";
                $query = $this->db->query($sql, [$currentYear]);
                $result = $query->getResultArray();

                foreach ($result as $row) {
                    $monthlyData[$row['bulan']]['total'] = $row['total'];
                }
                $rows = array_merge($rows, array_values($monthlyData));
            }
        }

        foreach ($rows as $r) {
            $total += $r['total'];
        }

        $content = '<table class="table table-bordered"><thead><tr><th class="text-center" width="5%">No</th><th>Bulan</th>';
        if (!$tahun) {
            $content .= '<th>Tahun</th>';
        }
        $content .= '<th class="text-center">Total</th></tr></thead><tbody>';

        $no = 1;
        foreach ($rows as $r) {
            $content .= '<tr><td class="text-center">' . $no++ . '</td><td>' . $this->getNamaBulan($r['bulan']) . '</td>';
            if (!$tahun) {
                $content .= '<td>' . $r['tahun'] . '</td>';
            }
            $content .= '<td class="text-end">Rp ' . number_format($r['total'], 0, ',', '.') . '</td></tr>';
        }

        $colspan = $tahun ? 2 : 3;
        $content .= '</tbody><tfoot><tr><td colspan="' . $colspan . '" class="text-end fw-bold">Total :</td><td class="text-end fw-bold">Rp ' . number_format($total, 0, ',', '.') . '</td></tr></tfoot></table>';

        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function getPengeluaranData()
    {

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }


        $this->db->query("SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");


        $tahun = $this->request->getGet('tahun');
        $bulan = $this->request->getGet('bulan');


        $showAll = $this->request->getGet('show_all') === 'true';

        if (!$showAll && (empty($tahun) || empty($bulan))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parameter tidak lengkap'
            ]);
        }


        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $pengeluaranModel = new \App\Models\PengeluaranModel();


        $builder = $pengeluaranModel->builder();

        if (!$showAll) {

            $builder->where('MONTH(tgl)', $bulan);
            $builder->where('YEAR(tgl)', $tahun);
        }


        $builder->orderBy('tgl', 'ASC');


        if ($showAll) {
            $builder->limit(100);
        }

        $pengeluaran = $builder->get()->getResultArray();


        $formattedData = [];
        $totalPengeluaran = 0;

        foreach ($pengeluaran as $item) {
            $formattedData[] = [
                'id' => $item['idpengeluaran'],
                'tanggal' => date('d/m/Y', strtotime($item['tgl'])),
                'tgl_raw' => $item['tgl'],
                'keterangan' => $item['keterangan'],
                'jumlah' => $item['jumlah'],
                'jumlah_formatted' => 'Rp ' . number_format($item['jumlah'], 0, ',', '.')
            ];

            $totalPengeluaran += $item['jumlah'];
        }


        $periodeLabel = $showAll ? 'Semua Data' : 'Bulan: ' . $namaBulan[$bulan] . ' ' . $tahun;

        return $this->response->setJSON([
            'success' => true,
            'data' => $formattedData,
            'totalPengeluaran' => $totalPengeluaran,
            'totalPengeluaranFormatted' => 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'),
            'periodeLabel' => $periodeLabel
        ]);
    }

    public function printPengeluaran()
    {
        $tahun = $this->request->getGet('tahun');
        $bulan = $this->request->getGet('bulan');
        $showAll = $this->request->getGet('show_all') === 'true';


        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $pengeluaranModel = new \App\Models\PengeluaranModel();


        $builder = $pengeluaranModel->builder();

        if (!$showAll && !empty($tahun) && !empty($bulan)) {

            $builder->where('MONTH(tgl)', $bulan);
            $builder->where('YEAR(tgl)', $tahun);
            $periodeLabel = 'Bulan: ' . $namaBulan[$bulan] . ' ' . $tahun;
        } else {

            $periodeLabel = 'Semua Data';

            $builder->limit(100);
        }


        $builder->orderBy('tgl', 'ASC');

        $pengeluaran = $builder->get()->getResultArray();


        $totalPengeluaran = 0;
        foreach ($pengeluaran as $item) {
            $totalPengeluaran += $item['jumlah'];
        }


        $headerData = [
            'title' => 'Cetak Laporan Pengeluaran',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Adinegoro No.16, Batang Kabung Ganting, Kec. Koto Tangah Kota Padang, Sumatera Barat 25586',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => $periodeLabel,
            'tanggal_label' => $periodeLabel,
            'report_title' => 'LAPORAN PENGELUARAN',
            'manager' => 'Pimpinan'
        ];


        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th>Keterangan</th>
                    <th class="text-center">Jumlah</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;

        foreach ($pengeluaran as $item) {
            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . date('d/m/Y', strtotime($item['tgl'])) . '</td>
                <td>' . $item['keterangan'] . '</td>
                <td class="text-end">Rp ' . number_format($item['jumlah'], 0, ',', '.') . '</td>
            </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total:</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalPengeluaran, 0, ',', '.') . '</td>
                </tr>
            </tfoot>
        </table>';


        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function labaRugi()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');


        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];


        $dataBulanan = [];
        foreach ($namaBulan as $kode => $nama) {
            $dataBulanan[$kode] = [
                'pendapatan' => 0,
                'pengeluaran' => 0,
                'laba' => 0
            ];
        }


        $queryPendapatan = $this->db->query(
            "SELECT 
                MONTH(created_at) as bulan,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'
                AND YEAR(created_at) = ?
            GROUP BY 
                MONTH(created_at)
            ORDER BY 
                bulan ASC",
            [$tahun]
        );

        $pendapatanBulanan = $queryPendapatan->getResultArray();


        foreach ($pendapatanBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            $dataBulanan[$bulan]['pendapatan'] = $item['total'];
        }


        $pengeluaranModel = new \App\Models\PengeluaranModel();
        $queryPengeluaran = $this->db->query(
            "SELECT 
                MONTH(tgl) as bulan,
                SUM(jumlah) as total
            FROM 
                pengeluaran
            WHERE 
                YEAR(tgl) = ?
            GROUP BY 
                MONTH(tgl)
            ORDER BY 
                bulan ASC",
            [$tahun]
        );

        $pengeluaranBulanan = $queryPengeluaran->getResultArray();


        foreach ($pengeluaranBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            $dataBulanan[$bulan]['pengeluaran'] = $item['total'];
        }


        foreach ($dataBulanan as $bulan => &$data) {
            $data['laba'] = $data['pendapatan'] - $data['pengeluaran'];
        }


        $totalPendapatan = 0;
        $totalPengeluaran = 0;

        foreach ($dataBulanan as $data) {
            $totalPendapatan += $data['pendapatan'];
            $totalPengeluaran += $data['pengeluaran'];
        }

        $labaBersih = $totalPendapatan - $totalPengeluaran;


        $queryTahun = $this->db->query(
            "SELECT DISTINCT YEAR(created_at) as tahun FROM pembayaran WHERE status = 'paid' ORDER BY tahun DESC"
        );
        $daftarTahun = $queryTahun->getResultArray();

        $data = [
            'title' => 'Laporan Laba Rugi',
            'dataBulanan' => $dataBulanan,
            'namaBulan' => $namaBulan,
            'totalPendapatan' => $totalPendapatan,
            'totalPengeluaran' => $totalPengeluaran,
            'labaBersih' => $labaBersih,
            'tahun' => $tahun,
            'daftarTahun' => $daftarTahun
        ];


        if (empty($daftarTahun)) {

            $data['daftarTahun'] = [['tahun' => date('Y')]];
        }


        foreach ($dataBulanan as $bulan => &$bulanData) {
            if (!isset($bulanData['pendapatan'])) {
                $bulanData['pendapatan'] = 0;
            }
            if (!isset($bulanData['pengeluaran'])) {
                $bulanData['pengeluaran'] = 0;
            }
            if (!isset($bulanData['laba'])) {
                $bulanData['laba'] = $bulanData['pendapatan'] - $bulanData['pengeluaran'];
            }
        }

        return view('admin/reports/laba_rugi', $data);
    }

    public function printLabaRugi()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');


        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];


        $dataBulanan = [];
        foreach ($namaBulan as $kode => $nama) {
            $dataBulanan[$kode] = [
                'pendapatan' => 0,
                'pengeluaran' => 0,
                'laba' => 0
            ];
        }


        $queryPendapatan = $this->db->query(
            "SELECT 
                MONTH(created_at) as bulan,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'
                AND YEAR(created_at) = ?
            GROUP BY 
                MONTH(created_at)
            ORDER BY 
                bulan ASC",
            [$tahun]
        );

        $pendapatanBulanan = $queryPendapatan->getResultArray();


        foreach ($pendapatanBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            $dataBulanan[$bulan]['pendapatan'] = $item['total'];
        }


        $pengeluaranModel = new \App\Models\PengeluaranModel();
        $queryPengeluaran = $this->db->query(
            "SELECT 
                MONTH(tgl) as bulan,
                SUM(jumlah) as total
            FROM 
                pengeluaran
            WHERE 
                YEAR(tgl) = ?
            GROUP BY 
                MONTH(tgl)
            ORDER BY 
                bulan ASC",
            [$tahun]
        );

        $pengeluaranBulanan = $queryPengeluaran->getResultArray();


        foreach ($pengeluaranBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            $dataBulanan[$bulan]['pengeluaran'] = $item['total'];
        }


        foreach ($dataBulanan as $bulan => &$data) {
            $data['laba'] = $data['pendapatan'] - $data['pengeluaran'];
        }


        $totalPendapatan = 0;
        $totalPengeluaran = 0;

        foreach ($dataBulanan as $data) {
            $totalPendapatan += $data['pendapatan'];
            $totalPengeluaran += $data['pengeluaran'];
        }

        $labaBersih = $totalPendapatan - $totalPengeluaran;


        $headerData = [
            'title' => 'Cetak Laporan Laba Rugi',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Dr. Moh. Hatta No.3kel, RT.01, Cupak Tangan, Kec. Pauh, Kota Padang, Sumatera Barat 25127',
            'telepon' => '0811-6359-5965',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => date('d F Y'),
            'report_title' => 'LAPORAN LABA RUGI TAHUN ' . $tahun,
            'manager' => 'Pimpinan'
        ];


        $content = '
        <div class="text-center mb-3">
            <p class="mb-0">Periode: Tahun ' . $tahun . '</p>
        </div>
        
        <!-- Tabel Pendapatan -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="10%">NO</th>
                    <th width="60%">Pendapatan</th>
                    <th width="30%">Jumlah</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($dataBulanan as $bulan => $data) {
            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . $namaBulan[$bulan] . '</td>
                <td class="text-end">Rp ' . number_format($data['pendapatan'], 0, ',', '.') . '</td>
            </tr>';
        }

        $content .= '
            <tr>
                <td colspan="2" class="text-end fw-bold">Total</td>
                <td class="text-end fw-bold">Rp ' . number_format($totalPendapatan, 0, ',', '.') . '</td>
            </tr>
            </tbody>
        </table>

        <!-- Tabel Beban/Pengeluaran -->
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th width="10%">NO</th>
                    <th width="60%">Beban</th>
                    <th width="30%">Jumlah</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($dataBulanan as $bulan => $data) {
            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . $namaBulan[$bulan] . '</td>
                <td class="text-end">Rp ' . number_format($data['pengeluaran'], 0, ',', '.') . '</td>
            </tr>';
        }

        $content .= '
            <tr>
                <td colspan="2" class="text-end fw-bold">Total</td>
                <td class="text-end fw-bold">Rp ' . number_format($totalPengeluaran, 0, ',', '.') . '</td>
            </tr>
            </tbody>
        </table>

        <!-- Pendapatan Bersih -->
        <div class="row mt-3">
            <div class="col-12">
                <table class="table table-bordered" width="100%">
                    <tr>
                        <td width="70%" class="text-end fw-bold">Pendapatan Bersih</td>
                        <td width="30%" class="text-end fw-bold">Rp ' . number_format($labaBersih, 0, ',', '.') . '</td>
                    </tr>
                </table>
            </div>
        </div>';


        $data = array_merge($headerData, ['content' => $content]);


        foreach ($dataBulanan as $bulan => &$bulanData) {
            if (!isset($bulanData['pendapatan'])) {
                $bulanData['pendapatan'] = 0;
            }
            if (!isset($bulanData['pengeluaran'])) {
                $bulanData['pengeluaran'] = 0;
            }
            if (!isset($bulanData['laba'])) {
                $bulanData['laba'] = $bulanData['pendapatan'] - $bulanData['pengeluaran'];
            }
        }

        return view('admin/reports/template_laporan', $data);
    }

    public function labaRugiBulanan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $bulan = $this->request->getGet('bulan') ?? date('m');


        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];


        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, intval($bulan), intval($tahun));


        $dataHarian = [];
        for ($i = 1; $i <= $jumlahHari; $i++) {
            $tanggal = $tahun . '-' . $bulan . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $dataHarian[$tanggal] = [
                'pendapatan' => 0,
                'pengeluaran' => 0,
                'laba' => 0
            ];
        }


        $queryPendapatan = $this->db->query(
            "SELECT 
                DATE(created_at) as tanggal,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'
                AND YEAR(created_at) = ?
                AND MONTH(created_at) = ?
            GROUP BY 
                DATE(created_at)
            ORDER BY 
                tanggal ASC",
            [$tahun, $bulan]
        );

        $pendapatanHarian = $queryPendapatan->getResultArray();


        foreach ($pendapatanHarian as $item) {
            if (isset($dataHarian[$item['tanggal']])) {
                $dataHarian[$item['tanggal']]['pendapatan'] = $item['total'];
            }
        }


        $pengeluaranModel = new \App\Models\PengeluaranModel();
        $queryPengeluaran = $this->db->query(
            "SELECT 
                DATE(tgl) as tanggal,
                SUM(jumlah) as total
            FROM 
                pengeluaran
            WHERE 
                YEAR(tgl) = ?
                AND MONTH(tgl) = ?
            GROUP BY 
                DATE(tgl)
            ORDER BY 
                tanggal ASC",
            [$tahun, $bulan]
        );

        $pengeluaranHarian = $queryPengeluaran->getResultArray();


        foreach ($pengeluaranHarian as $item) {
            if (isset($dataHarian[$item['tanggal']])) {
                $dataHarian[$item['tanggal']]['pengeluaran'] = $item['total'];
            }
        }


        $dataHarianFiltered = [];
        foreach ($dataHarian as $tanggal => $data) {
            $data['laba'] = $data['pendapatan'] - $data['pengeluaran'];


            if ($data['pendapatan'] > 0 || $data['pengeluaran'] > 0) {
                $dataHarianFiltered[$tanggal] = $data;
            }
        }


        $totalPendapatan = 0;
        $totalPengeluaran = 0;

        foreach ($dataHarianFiltered as $data) {
            $totalPendapatan += $data['pendapatan'];
            $totalPengeluaran += $data['pengeluaran'];
        }

        $labaBersih = $totalPendapatan - $totalPengeluaran;


        $queryTahun = $this->db->query(
            "SELECT DISTINCT YEAR(created_at) as tahun FROM pembayaran WHERE status = 'paid' ORDER BY tahun DESC"
        );
        $daftarTahun = $queryTahun->getResultArray();

        $data = [
            'title' => 'Laporan Laba Rugi Bulanan',
            'dataHarian' => $dataHarianFiltered,
            'namaBulan' => $namaBulan,
            'totalPendapatan' => $totalPendapatan,
            'totalPengeluaran' => $totalPengeluaran,
            'labaBersih' => $labaBersih,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'daftarTahun' => $daftarTahun
        ];


        if (empty($daftarTahun)) {

            $data['daftarTahun'] = [['tahun' => date('Y')]];
        }


        foreach ($dataHarianFiltered as $tanggal => &$hariData) {
            if (!isset($hariData['pendapatan'])) {
                $hariData['pendapatan'] = 0;
            }
            if (!isset($hariData['pengeluaran'])) {
                $hariData['pengeluaran'] = 0;
            }
            if (!isset($hariData['laba'])) {
                $hariData['laba'] = $hariData['pendapatan'] - $hariData['pengeluaran'];
            }
        }

        return view('admin/reports/laba_rugi_bulanan', $data);
    }

    public function printLabaRugiBulanan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $bulan = $this->request->getGet('bulan') ?? date('m');


        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];


        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, intval($bulan), intval($tahun));


        $dataHarian = [];
        for ($i = 1; $i <= $jumlahHari; $i++) {
            $tanggal = $tahun . '-' . $bulan . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $dataHarian[$tanggal] = [
                'pendapatan' => 0,
                'pengeluaran' => 0,
                'laba' => 0
            ];
        }


        $queryPendapatan = $this->db->query(
            "SELECT 
                DATE(created_at) as tanggal,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'
                AND YEAR(created_at) = ?
                AND MONTH(created_at) = ?
            GROUP BY 
                DATE(created_at)
            ORDER BY 
                tanggal ASC",
            [$tahun, $bulan]
        );

        $pendapatanHarian = $queryPendapatan->getResultArray();


        foreach ($pendapatanHarian as $item) {
            if (isset($dataHarian[$item['tanggal']])) {
                $dataHarian[$item['tanggal']]['pendapatan'] = $item['total'];
            }
        }


        $pengeluaranModel = new \App\Models\PengeluaranModel();
        $queryPengeluaran = $this->db->query(
            "SELECT 
                DATE(tgl) as tanggal,
                SUM(jumlah) as total
            FROM 
                pengeluaran
            WHERE 
                YEAR(tgl) = ?
                AND MONTH(tgl) = ?
            GROUP BY 
                DATE(tgl)
            ORDER BY 
                tanggal ASC",
            [$tahun, $bulan]
        );

        $pengeluaranHarian = $queryPengeluaran->getResultArray();


        foreach ($pengeluaranHarian as $item) {
            if (isset($dataHarian[$item['tanggal']])) {
                $dataHarian[$item['tanggal']]['pengeluaran'] = $item['total'];
            }
        }


        $dataHarianFiltered = [];
        foreach ($dataHarian as $tanggal => $data) {
            $data['laba'] = $data['pendapatan'] - $data['pengeluaran'];


            if ($data['pendapatan'] > 0 || $data['pengeluaran'] > 0) {
                $dataHarianFiltered[$tanggal] = $data;
            }
        }


        $totalPendapatan = 0;
        $totalPengeluaran = 0;

        foreach ($dataHarianFiltered as $data) {
            $totalPendapatan += $data['pendapatan'];
            $totalPengeluaran += $data['pengeluaran'];
        }

        $labaBersih = $totalPendapatan - $totalPengeluaran;


        $headerData = [
            'title' => 'Cetak Laporan Laba Rugi Bulanan',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Dr. Moh. Hatta No.3kel, RT.01, Cupak Tangan, Kec. Pauh, Kota Padang, Sumatera Barat 25127',
            'telepon' => '0811-6359-5965',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => date('d F Y'),
            'report_title' => 'LAPORAN LABA RUGI BULAN ' . $namaBulan[$bulan] . ' ' . $tahun,
            'manager' => 'Pimpinan'
        ];


        $content = '
        <div class="text-center mb-3">
            <p class="mb-0">Periode: ' . $namaBulan[$bulan] . ' ' . $tahun . '</p>
        </div>';

        if (empty($dataHarianFiltered)) {
            $content .= '
            <div class="alert alert-info">
                Tidak ada data laba rugi untuk bulan ' . $namaBulan[$bulan] . ' ' . $tahun . '
            </div>';
        } else {
            $content .= '
            <!-- Tabel Pendapatan -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="10%">NO</th>
                        <th width="60%">Pendapatan</th>
                        <th width="30%">Jumlah</th>
                    </tr>
                </thead>
                <tbody>';

            $no = 1;
            foreach ($dataHarianFiltered as $tanggal => $data) {
                $content .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td>Tanggal ' . date('d/m/Y', strtotime($tanggal)) . '</td>
                    <td class="text-end">Rp ' . number_format($data['pendapatan'], 0, ',', '.') . '</td>
                </tr>';
            }

            $content .= '
                <tr>
                    <td colspan="2" class="text-end fw-bold">Total</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalPendapatan, 0, ',', '.') . '</td>
                </tr>
                </tbody>
            </table>

            <!-- Tabel Beban/Pengeluaran -->
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th width="10%">NO</th>
                        <th width="60%">Beban</th>
                        <th width="30%">Jumlah</th>
                    </tr>
                </thead>
                <tbody>';

            $no = 1;
            foreach ($dataHarianFiltered as $tanggal => $data) {
                $content .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td>Tanggal ' . date('d/m/Y', strtotime($tanggal)) . '</td>
                    <td class="text-end">Rp ' . number_format($data['pengeluaran'], 0, ',', '.') . '</td>
                </tr>';
            }

            $content .= '
                <tr>
                    <td colspan="2" class="text-end fw-bold">Total</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalPengeluaran, 0, ',', '.') . '</td>
                </tr>
                </tbody>
            </table>

            <!-- Pendapatan Bersih -->
            <div class="row mt-3">
                <div class="col-12">
                    <table class="table table-bordered" width="100%">
                        <tr>
                            <td width="70%" class="text-end fw-bold">Pendapatan Bersih</td>
                            <td width="30%" class="text-end fw-bold">Rp ' . number_format($labaBersih, 0, ',', '.') . '</td>
                        </tr>
                    </table>
                </div>
            </div>';
        }


        $data = array_merge($headerData, ['content' => $content]);


        foreach ($dataHarianFiltered as $tanggal => &$hariData) {
            if (!isset($hariData['pendapatan'])) {
                $hariData['pendapatan'] = 0;
            }
            if (!isset($hariData['pengeluaran'])) {
                $hariData['pengeluaran'] = 0;
            }
            if (!isset($hariData['laba'])) {
                $hariData['laba'] = $hariData['pendapatan'] - $hariData['pengeluaran'];
            }
        }

        return view('admin/reports/template_laporan', $data);
    }

    public function getData()
    {

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }


        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $singleDate = $this->request->getGet('single_date');


        $bookings = $this->bookingModel->getBookingWithPelanggan();
        $bookingData = [];
        $totalData = 0;

        foreach ($bookings as $booking) {
            $details = $this->detailBookingModel->getDetailsByBookingCode($booking['kdbooking']);
            if (!empty($details)) {

                if ($startDate || $endDate || $singleDate) {
                    $filteredDetails = [];
                    foreach ($details as $detail) {
                        $detailDate = date('Y-m-d', strtotime($detail['tgl']));

                        $includeDetail = true;

                        if ($singleDate && $detailDate != $singleDate) {
                            $includeDetail = false;
                        } else if (!$singleDate) {
                            if ($startDate && $detailDate < $startDate) {
                                $includeDetail = false;
                            }
                            if ($endDate && $detailDate > $endDate) {
                                $includeDetail = false;
                            }
                        }

                        if ($includeDetail) {
                            $filteredDetails[] = $detail;
                        }
                    }


                    if (!empty($filteredDetails)) {
                        $booking['details'] = $filteredDetails;
                        $bookingData[] = $booking;
                    }
                } else {

                    $booking['details'] = $details;
                    $bookingData[] = $booking;
                }
            }
        }


        $processedBookings = [];


        $html = '';
        $no = 1;

        foreach ($bookingData as $booking) {

            if (isset($processedBookings[$booking['kdbooking']])) {
                continue;
            }


            $processedBookings[$booking['kdbooking']] = true;


            $paketList = [];
            $totalHarga = 0;
            foreach ($booking['details'] as $detail) {
                $paketInfo = $detail['nama_paket'];
                if (!empty($detail['deskripsi'])) {
                    $paketInfo .= ' (' . $detail['deskripsi'] . ')';
                }
                $paketList[] = $paketInfo;

                $totalHarga += $detail['harga'] ?? 0;
            }


            $tanggal = isset($booking['details'][0]['tgl']) ? date('d/m/Y', strtotime($booking['details'][0]['tgl'])) : '';

            $html .= '<tr>';
            $html .= '<td>' . $no++ . '</td>';
            $html .= '<td>' . ($booking['kdbooking'] ?? '') . '</td>';
            $html .= '<td>' . ($booking['nama_lengkap'] ?? '') . '</td>';
            $html .= '<td>' . $tanggal . '</td>';
            $html .= '<td>' . implode(", ", $paketList) . '</td>';
            $html .= '<td>Rp ' . number_format($totalHarga, 0, ',', '.') . '</td>';
            $html .= '<td>Rp ' . number_format(($booking['total'] ?? 0), 0, ',', '.') . '</td>';
            $html .= '</tr>';
            $totalData++;
        }


        if ($totalData === 0) {
            $html = '<tr><td colspan="7" class="text-center">Tidak ada data yang ditemukan</td></tr>';
        }


        $pesan = 'Data berhasil dimuat';
        if ($singleDate) {
            $pesan = 'Data booking tanggal ' . date('d/m/Y', strtotime($singleDate)) . ' berhasil dimuat';
        } elseif ($startDate && $endDate) {
            $pesan = 'Data booking periode ' . date('d/m/Y', strtotime($startDate)) . ' - ' .
                date('d/m/Y', strtotime($endDate)) . ' berhasil dimuat';
        } elseif ($startDate) {
            $pesan = 'Data booking dari tanggal ' . date('d/m/Y', strtotime($startDate)) . ' berhasil dimuat';
        } elseif ($endDate) {
            $pesan = 'Data booking sampai tanggal ' . date('d/m/Y', strtotime($endDate)) . ' berhasil dimuat';
        } else {
            $pesan = 'Semua data booking berhasil dimuat';
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $pesan,
            'html' => $html,
            'data' => $bookingData, // Tambahkan data mentah
            'total' => $totalData,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'single_date' => $singleDate
        ]);
    }

    public function getPembayaranData()
    {

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }


        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');
        $singleDate = $this->request->getGet('single_date');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $pembayaranData = [];
        $pembayaran = $this->pembayaranModel->where('status', 'paid');


        if ($singleDate) {
            $pembayaran->where('DATE(created_at)', $singleDate);
        } elseif ($startDate || $endDate) {
            if ($startDate) {
                $pembayaran->where('DATE(created_at) >=', $startDate);
            }
            if ($endDate) {
                $pembayaran->where('DATE(created_at) <=', $endDate);
            }
        } elseif ($bulan && $tahun) {
            $pembayaran->where("DATE_FORMAT(created_at, '%m-%Y') = ", "$bulan-$tahun");
        } elseif ($bulan) {
            $pembayaran->where("DATE_FORMAT(created_at, '%m') = ", $bulan);
        } elseif ($tahun) {
            $pembayaran->where("DATE_FORMAT(created_at, '%Y') = ", $tahun);
        }

        $pembayaran = $pembayaran->findAll();
        $totalData = 0;

        foreach ($pembayaran as $p) {
           

            $booking = $this->bookingModel->getBookingWithPelanggan($p['fakturbooking']);
            if ($booking) {
                $details = $this->detailBookingModel->getDetailsByBookingCode($p['fakturbooking']);
                if (!empty($details)) {
                    $p['booking'] = $booking;
                    $p['details'] = $details;
                    $pembayaranData[] = $p;
                }
            }
        }


        $html = '';
        $no = 1;
        $processedFakturHtml = [];

        foreach ($pembayaranData as $p) {
            // Skip jika faktur sudah diproses (untuk menghindari duplikat)
            if (in_array($p['fakturbooking'], $processedFakturHtml)) {
                continue;
            }
            $processedFakturHtml[] = $p['fakturbooking'];

            $paketList = [];
            if (isset($p['details']) && is_array($p['details'])) {
                foreach ($p['details'] as $detail) {
                    $paketInfo = $detail['nama_paket'] ?? '';
                    if (!empty($detail['deskripsi'])) {
                        $paketInfo .= ' (' . $detail['deskripsi'] . ')';
                    }
                    $paketList[] = $paketInfo;
                }
            }

            $html .= '<tr>';
            $html .= '<td class="text-center">' . $no++ . '</td>';
            $html .= '<td>' . ($p['fakturbooking'] ?? '') . '</td>';
            $html .= '<td>' . (isset($p['created_at']) ? date('d/m/Y', strtotime($p['created_at'])) : '') . '</td>';
            $html .= '<td>' . ($p['booking']['nama_lengkap'] ?? '') . '</td>';
            $html .= '<td>' . implode(", ", $paketList) . '</td>';
            $html .= '<td class="text-end">Rp ' . number_format(($p['grandtotal'] ?? 0), 0, ',', '.') . '</td>';
            $html .= '<td class="text-end">Rp ' . number_format(($p['total_bayar'] ?? 0), 0, ',', '.') . '</td>';
            $html .= '<td class="text-center">' . ucfirst($p['metode'] ?? '') . '</td>';
            $html .= '</tr>';
            $totalData++;
        }


        if ($totalData === 0) {
            $html = '<tr><td colspan="8" class="text-center">Tidak ada data yang ditemukan</td></tr>';
        }


        $pesan = 'Data berhasil dimuat';
        if ($singleDate) {
            $pesan = 'Data pembayaran tanggal ' . date('d/m/Y', strtotime($singleDate)) . ' berhasil dimuat';
        } elseif ($startDate && $endDate) {
            $pesan = 'Data pembayaran periode ' . date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate)) . ' berhasil dimuat';
        } elseif ($startDate) {
            $pesan = 'Data pembayaran dari tanggal ' . date('d/m/Y', strtotime($startDate)) . ' berhasil dimuat';
        } elseif ($endDate) {
            $pesan = 'Data pembayaran sampai tanggal ' . date('d/m/Y', strtotime($endDate)) . ' berhasil dimuat';
        } elseif ($bulan && $tahun) {
            $namaBulan = $this->getNamaBulan($bulan);
            $pesan = "Data pembayaran bulan $namaBulan $tahun berhasil dimuat";
        } elseif ($bulan) {
            $namaBulan = $this->getNamaBulan($bulan);
            $pesan = "Data pembayaran bulan $namaBulan berhasil dimuat";
        } elseif ($tahun) {
            $pesan = "Data pembayaran tahun $tahun berhasil dimuat";
        } else {
            $pesan = "Semua data pembayaran berhasil dimuat";
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $pesan,
            'html' => $html,
            'data' => $pembayaranData, // Tambahkan data mentah
            'total' => $totalData,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
    }


    private function getNamaBulan($bulan)
    {

        if (is_numeric($bulan)) {

            $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        }

        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        return $namaBulan[$bulan] ?? 'Bulan ' . $bulan;
    }

    public function uangMasukKeluar()
    {

        $queryTahun = $this->db->query(
            "SELECT DISTINCT YEAR(created_at) as tahun FROM pembayaran WHERE status = 'paid' ORDER BY tahun DESC"
        );
        $daftarTahun = $queryTahun->getResultArray();

        $data = [
            'title' => 'Laporan Uang Masuk dan Keluar',
            'tahun' => date('Y'),
            'daftarTahun' => $daftarTahun
        ];

        return view('admin/reports/uang_masuk_keluar', $data);
    }

    public function getUangMasukKeluarData()
    {

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }


        $tahun = $this->request->getGet('tahun');


        $showAll = $this->request->getGet('show_all') === 'true';

        if (!$showAll && empty($tahun)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parameter tidak lengkap'
            ]);
        }


        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];


        $dataBulanan = [];
        foreach ($namaBulan as $kode => $nama) {
            $dataBulanan[$kode] = [
                'bulan_kode' => $kode,
                'bulan' => $nama,
                'uang_masuk' => 0,
                'uang_keluar' => 0,
                'total' => 0,
                'status' => '' // Akan diisi "LABA" atau "RUGI"
            ];
        }


        $sqlPendapatan = "SELECT 
                MONTH(created_at) as bulan,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'";

        if (!$showAll && $tahun) {
            $sqlPendapatan .= " AND YEAR(created_at) = ?";
        }

        $sqlPendapatan .= " GROUP BY MONTH(created_at)";

        $queryParams = !$showAll && $tahun ? [$tahun] : [];
        $queryPendapatan = $this->db->query($sqlPendapatan, $queryParams);
        $pendapatanBulanan = $queryPendapatan->getResultArray();


        foreach ($pendapatanBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            if (isset($dataBulanan[$bulan])) {
                $dataBulanan[$bulan]['uang_masuk'] = $item['total'];
            }
        }


        $sqlPengeluaran = "SELECT 
                MONTH(tgl) as bulan,
                SUM(jumlah) as total
            FROM 
                pengeluaran
            WHERE 
                1=1";

        if (!$showAll && $tahun) {
            $sqlPengeluaran .= " AND YEAR(tgl) = ?";
        }

        $sqlPengeluaran .= " GROUP BY MONTH(tgl)";

        $queryPengeluaran = $this->db->query($sqlPengeluaran, $queryParams);
        $pengeluaranBulanan = $queryPengeluaran->getResultArray();


        foreach ($pengeluaranBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            if (isset($dataBulanan[$bulan])) {
                $dataBulanan[$bulan]['uang_keluar'] = $item['total'];
            }
        }


        $totalUangMasuk = 0;
        $totalUangKeluar = 0;

        foreach ($dataBulanan as $bulan => &$data) {
            $data['total'] = $data['uang_masuk'] - $data['uang_keluar'];
            $data['status'] = $data['total'] >= 0 ? 'LABA' : 'RUGI';

            $totalUangMasuk += $data['uang_masuk'];
            $totalUangKeluar += $data['uang_keluar'];
        }

        $totalKeseluruhan = $totalUangMasuk - $totalUangKeluar;
        $statusKeseluruhan = $totalKeseluruhan >= 0 ? 'LABA' : 'RUGI';


        $formattedData = [];
        foreach ($dataBulanan as $data) {

            if ($data['uang_masuk'] > 0 || $data['uang_keluar'] > 0) {
                $formattedData[] = [
                    'bulan_kode' => $data['bulan_kode'],
                    'bulan' => $data['bulan'],
                    'uang_masuk' => $data['uang_masuk'],
                    'uang_masuk_formatted' => 'Rp ' . number_format($data['uang_masuk'], 0, ',', '.'),
                    'uang_keluar' => $data['uang_keluar'],
                    'uang_keluar_formatted' => 'Rp ' . number_format($data['uang_keluar'], 0, ',', '.'),
                    'total' => $data['total'],
                    'total_formatted' => 'Rp ' . number_format($data['total'], 0, ',', '.'),
                    'status' => $data['status']
                ];
            }
        }


        usort($formattedData, function ($a, $b) {
            return $a['bulan_kode'] <=> $b['bulan_kode'];
        });


        $periodeLabel = $showAll ? 'Semua Data' : 'Tahun: ' . $tahun;

        return $this->response->setJSON([
            'success' => true,
            'data' => $formattedData,
            'totalUangMasuk' => $totalUangMasuk,
            'totalUangMasukFormatted' => 'Rp ' . number_format($totalUangMasuk, 0, ',', '.'),
            'totalUangKeluar' => $totalUangKeluar,
            'totalUangKeluarFormatted' => 'Rp ' . number_format($totalUangKeluar, 0, ',', '.'),
            'totalKeseluruhan' => $totalKeseluruhan,
            'totalKeseluruhanFormatted' => 'Rp ' . number_format($totalKeseluruhan, 0, ',', '.'),
            'statusKeseluruhan' => $statusKeseluruhan,
            'periodeLabel' => $periodeLabel
        ]);
    }

    public function printUangMasukKeluar()
    {

        $tahun = $this->request->getGet('tahun');
        $showAll = $this->request->getGet('show_all') === 'true';


        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];


        $dataBulanan = [];
        foreach ($namaBulan as $kode => $nama) {
            $dataBulanan[$kode] = [
                'bulan_kode' => $kode,
                'bulan' => $nama,
                'uang_masuk' => 0,
                'uang_keluar' => 0,
                'total' => 0,
                'status' => '' // Akan diisi "LABA" atau "RUGI"
            ];
        }


        $sqlPendapatan = "SELECT 
                MONTH(created_at) as bulan,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'";

        if (!$showAll && $tahun) {
            $sqlPendapatan .= " AND YEAR(created_at) = ?";
        }

        $sqlPendapatan .= " GROUP BY MONTH(created_at)";

        $queryParams = !$showAll && $tahun ? [$tahun] : [];
        $queryPendapatan = $this->db->query($sqlPendapatan, $queryParams);
        $pendapatanBulanan = $queryPendapatan->getResultArray();


        foreach ($pendapatanBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            if (isset($dataBulanan[$bulan])) {
                $dataBulanan[$bulan]['uang_masuk'] = $item['total'];
            }
        }


        $sqlPengeluaran = "SELECT 
                MONTH(tgl) as bulan,
                SUM(jumlah) as total
            FROM 
                pengeluaran
            WHERE 
                1=1";

        if (!$showAll && $tahun) {
            $sqlPengeluaran .= " AND YEAR(tgl) = ?";
        }

        $sqlPengeluaran .= " GROUP BY MONTH(tgl)";

        $queryPengeluaran = $this->db->query($sqlPengeluaran, $queryParams);
        $pengeluaranBulanan = $queryPengeluaran->getResultArray();


        foreach ($pengeluaranBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            if (isset($dataBulanan[$bulan])) {
                $dataBulanan[$bulan]['uang_keluar'] = $item['total'];
            }
        }


        $totalUangMasuk = 0;
        $totalUangKeluar = 0;

        foreach ($dataBulanan as $bulan => &$data) {
            $data['total'] = $data['uang_masuk'] - $data['uang_keluar'];
            $data['status'] = $data['total'] >= 0 ? 'LABA' : 'RUGI';

            $totalUangMasuk += $data['uang_masuk'];
            $totalUangKeluar += $data['uang_keluar'];
        }

        $totalKeseluruhan = $totalUangMasuk - $totalUangKeluar;
        $statusKeseluruhan = $totalKeseluruhan >= 0 ? 'LABA' : 'RUGI';


        $filteredData = array_filter($dataBulanan, function ($data) {
            return $data['uang_masuk'] > 0 || $data['uang_keluar'] > 0;
        });


        uasort($filteredData, function ($a, $b) {
            return $a['bulan_kode'] <=> $b['bulan_kode'];
        });


        $headerData = [
            'title' => 'Cetak Laporan Uang Masuk dan Keluar',
            'nama_perusahaan' => 'Vixs Barbershop',
            'alamat_perusahaan' => 'Jl. Adinegoro No.16, Batang Kabung Ganting, Kec. Koto Tangah Kota Padang, Sumatera Barat 25586',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => $showAll ? 'Semua Data' : 'Tahun: ' . $tahun,
            'tanggal_label' => $showAll ? 'Semua Data' : 'Tahun: ' . $tahun,
            'report_title' => 'LAPORAN UANG MASUK DAN KELUAR',
            'manager' => 'Pimpinan'
        ];


        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Bulan</th>
                    <th class="text-center">Uang Masuk (UM)</th>
                    <th class="text-center">Uang Keluar (UK)</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;

        foreach ($filteredData as $data) {
            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . $data['bulan'] . '</td>
                <td class="text-end">Rp ' . number_format($data['uang_masuk'], 0, ',', '.') . '</td>
                <td class="text-end">Rp ' . number_format($data['uang_keluar'], 0, ',', '.') . '</td>
            </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-end fw-bold">Total :</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalUangMasuk, 0, ',', '.') . '</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalUangKeluar, 0, ',', '.') . '</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-end fw-bold">Status :</td>
                    <td colspan="2" class="text-center fw-bold ' . ($statusKeseluruhan == 'LABA' ? 'text-success' : 'text-danger') . '">' . $statusKeseluruhan . '</td>
                </tr>
            </tfoot>
        </table>';


        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }
}
