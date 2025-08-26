<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Transaksi #{{ $transaksi->id }}</title>
    <style>
        /* Font Import */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        /* Reset Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #fff;
            padding: 20px;
        }

        /* Header Styles */
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
        }

        .company-info {
            flex: 1;
        }

        .company-name {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 12px;
            color: #7f8c8d;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: 700;
            color: #3498db;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-size: 16px;
            color: #7f8c8d;
        }

        /* Transaction Summary */
        .summary-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .summary-card {
            flex: 0 0 48%;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            border-left: 4px solid #3498db;
        }

        .summary-title {
            font-size: 16px;
            font-weight: 600;
            color: #7f8c8d;
            margin-bottom: 15px;
        }

        .summary-item {
            margin-bottom: 10px;
            display: flex;
        }

        .summary-label {
            font-weight: 500;
            color: #7f8c8d;
            width: 120px;
            flex-shrink: 0;
        }

        .summary-value {
            font-weight: 400;
        }

        .total-amount {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }

        /* Table Styles */
        .table-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #3498db;
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #3498db;
            color: white;
            text-align: left;
            padding: 12px 15px;
            font-weight: 600;
            font-size: 14px;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background-color: #2ecc71;
            color: white;
        }

        .badge-warning {
            background-color: #f39c12;
            color: white;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
        }

        .terms {
            flex: 0 0 60%;
            font-size: 12px;
            color: #7f8c8d;
        }

        .signature {
            flex: 0 0 35%;
            text-align: center;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #7f8c8d;
            padding-top: 5px;
            font-size: 12px;
        }

        /* Utility Classes */
        .mb-4 {
            margin-bottom: 20px;
        }

        .page-break {
            page-break-before: always;
        }

        /* Watermark for Copy */
        body::after {
            content: "INVOICE COPY";
            position: fixed;
            bottom: 50%;
            right: 50%;
            transform: translate(50%, 50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.05);
            z-index: -1;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <!-- <div class="header">
        <div class="company-info">
            <div class="company-name">Nama Perusahaan Anda</div>
            <div class="company-details">
                Alamat: Jl. Contoh No. 123, Kota Contoh<br>
                Telepon: (021) 123-4567 | Email: info@perusahaan.com<br>
                Website: www.perusahaan.com
            </div>
        </div>
        <div class="invoice-info">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">#{{ $transaksi->id }}</div>
        </div>
    </div> -->

    <!-- Transaction Summary Section -->
    <div class="summary-section">
        <div class="summary-card">
            <div class="summary-title">Informasi Transaksi</div>
            <div class="summary-item">
                <span class="summary-label">Tanggal</span>
                <span class="summary-value">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Keterangan</span>
                <span class="summary-value">{{ $transaksi->keterangan ?? '-' }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Dibuat Oleh</span>
                <span class="summary-value">{{ $transaksi->user->name ?? 'Admin' }}</span>
            </div>
        </div>

        <!-- <div class="summary-card">
            <div class="summary-title">Ringkasan Pembayaran</div>
            <div class="summary-item">
                <span class="summary-label">Status</span>
                <span class="summary-value">Lunas</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Metode</span>
                <span class="summary-value">Transfer Bank</span>
            </div>
            <div class="total-amount">
                Total: Rp {{ number_format($transaksi->total, 0, ',', '.') }}
            </div>
        </div> -->
    </div>

    <!-- Transaction Details Section -->
    <div class="table-section">
        <div class="section-title">Detail Item Transaksi</div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th class="text-right">Jumlah</th>
                    <th class="text-right">Satuan</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Subtotal</th>
                    <th>Mata Uang</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->detailTransaksi as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $detail->nama_barang }}</td>
                    <td>
                        <span class="badge {{ $detail->jenis == 'pemasukan' ? 'badge-success' : 'badge-warning' }}">
                            {{ $detail->jenis == 'pemasukan' ? 'Pemasukan' : 'Pengeluaran' }}
                        </span>
                    </td>
                    <td class="text-right">{{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $detail->satuan ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    <td>{{ $detail->mataUang->nama ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right" style="font-weight: 700;">Total</td>
                    <td class="text-right" style="font-weight: 700;">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Additional Information Section -->
    <!-- <div class="table-section">
        <div class="section-title">Informasi Tambahan</div>

        <table>
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th>Referensi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->detailTransaksi as $detail)
                <tr>
                    <td>{{ $detail->keterangan ?? '-' }}</td>
                    <td>
                        @if($detail->referensi)
                        <span style="color: #3498db;">Dokumen Tersedia</span>
                        @else
                        -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div> -->

    <!-- Footer Section -->
    <!-- <div class="footer">
        <div class="terms">
            <strong>Ketentuan Pembayaran:</strong><br>
            • Pembayaran diharapkan dilakukan dalam waktu 30 hari setelah invoice diterima<br>
            • Pembayaran dapat dilakukan melalui transfer bank ke rekening berikut:<br>
            &nbsp;&nbsp;Bank: Bank Contoh | No. Rekening: 123-456-7890 | Atas Nama: Nama Perusahaan Anda<br>
            • Harap cantumkan nomor invoice pada saat melakukan pembayaran
        </div>
        <div class="signature">
            <div class="signature-line">Hormat Kami,</div>
        </div>
    </div> -->

    <!-- Page Break for Second Copy -->
    <!-- <div class="page-break"></div> -->

    <!-- Duplicate for internal records (optional) -->
    <!-- <div class="header">
        <div class="company-info">
            <div class="company-name">Nama Perusahaan Anda</div>
        </div>
        <div class="invoice-info">
            <div class="invoice-title">COPY INTERNAL</div>
            <div class="invoice-number">#{{ $transaksi->id }}</div>
        </div>
    </div> -->


</body>

</html>
