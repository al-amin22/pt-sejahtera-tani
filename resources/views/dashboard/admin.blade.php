@extends('layouts.app')

@section('content')
<style>
    .stat-card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        border: none;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        font-size: 2rem;
        opacity: 0.8;
    }

    .bg-china {
        background: linear-gradient(45deg, #ffcccc, #ff9999);
    }

    .bg-jambi {
        background: linear-gradient(45deg, #ccffcc, #99ff99);
    }

    .bg-aceh {
        background: linear-gradient(45deg, #ccccff, #9999ff);
    }

    .flow-chart {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
    }

    .flow-node {
        text-align: center;
        padding: 20px;
        border-radius: 12px;
        flex: 1;
        margin: 0 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 2;
    }

    .flow-arrow {
        font-size: 28px;
        color: #6c757d;
        z-index: 2;
    }

    .flow-line {
        position: absolute;
        height: 3px;
        background: linear-gradient(90deg, #ff9999, #99ff99, #9999ff);
        top: 50%;
        left: 10%;
        right: 10%;
        z-index: 1;
    }

    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .recent-transactions {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .dashboard-header {
        background: linear-gradient(90deg, #4b6cb7 0%, #182848 100%);
        color: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .positive {
        color: #28a745;
    }

    .negative {
        color: #dc3545;
    }

    /* taruh di <style> kamu */
    .chart-container {
        position: relative;
        height: 350px;
        /* atau ganti sesuai kebutuhan */
        width: 100%;
    }

    @media (max-width: 768px) {
        .flow-chart {
            flex-direction: column;
        }

        .flow-node {
            margin: 10px 0;
            width: 100%;
        }

        .flow-arrow {
            transform: rotate(90deg);
            margin: 10px 0;
        }

        .flow-line {
            width: 3px;
            height: auto;
            top: 10%;
            bottom: 10%;
            left: 50%;
            transform: translateX(-50%);
        }
    }
</style>

<!-- Data untuk JavaScript (disembunyikan) -->
@if(isset($bulananData) && !empty($bulananData))
<div id="monthlyData" data-monthly='@json($bulananData)' style="display: none;"></div>
@else
<div id="monthlyData" data-monthly='[]' style="display: none;"></div>
@endif

<div class="container-fluid">
    <!-- Header Dashboard -->
    <div class="row">
        <div class="col-12">
            <div class="dashboard-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard Keuangan</h2>
                        <p class="mb-0">Ringkasan lengkap alur dana perusahaan</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="yearDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Tahun: {{ $tahun }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="yearDropdown">
                                @for($i = date('Y'); $i >= 2020; $i--)
                                <li><a class="dropdown-item" href="?tahun={{ $i }}">{{ $i }}</a></li>
                                @endfor
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visualisasi Alur Dana -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="chart-container">
                <h4 class="mb-4"><i class="fas fa-project-diagram me-2"></i>Alur Dana Perusahaan</h4>
                <div class="flow-chart">
                    <div class="flow-line"></div>
                    <div class="flow-node bg-china">
                        <h5>China</h5>
                        <div class="fw-bold fs-4">Rp {{ number_format($danaDariChina, 0, ',', '.') }}</div> <small>Investor</small>
                    </div>
                    <div class="flow-arrow"> <i class="fas fa-arrow-right"></i> </div>
                    <div class="flow-node bg-jambi">
                        <h5>Jambi</h5>
                        <div class="fw-bold fs-4">Rp {{ number_format($saldoJambi, 0, ',', '.') }}</div> <small>Saldo Jambi</small>
                    </div>
                    <div class="flow-arrow"> <i class="fas fa-arrow-right"></i> </div>
                    <div class="flow-node bg-aceh">
                        <h5>Aceh</h5>
                        <div class="fw-bold fs-4">Rp {{ number_format($saldoAceh, 0, ',', '.') }}</div> <small>Saldo Aceh</small>
                    </div>
                </div>
                <div class="row text-center mt-4">
                    <div class="col-md-4 mb-3">
                        <div class="p-3 border rounded bg-light"> <small class="text-muted d-block">Pengeluaran di Jambi</small> <span class="fw-bold text-danger fs-5">Rp {{ number_format($operasionalJambi, 0, ',', '.') }}</span> </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="p-3 border rounded bg-light"> <small class="text-muted d-block">Transfer Jambi ke Aceh</small> <span class="fw-bold text-primary fs-5">Rp {{ number_format($transferAceh, 0, ',', '.') }}</span> </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="p-3 border rounded bg-light"> <small class="text-muted d-block">Pengeluaran di Aceh</small> <span class="fw-bold text-danger fs-5">Rp {{ number_format($operasionalAceh, 0, ',', '.') }}</span> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Grafik Tren Bulanan dan Transaksi Terbaru -->
    <div class="row mb-4">
        <!-- Grafik Tren Bulanan -->
        <div class="col-lg-8 mb-3">
            <div class="chart-container">
                <h4 class="mb-4"><i class="fas fa-chart-line me-2"></i>Tren Bulanan Tahun {{ $tahun }}</h4>
                <!-- Container chart -->
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>

        <!-- Transaksi Terbaru -->
        <div class="col-lg-4 mb-3">
            <div class="recent-transactions">
                <h4 class="mb-4"><i class="fas fa-history me-2"></i>Transaksi Terbaru</h4>

                @if(isset($transaksiTerbaru) && $transaksiTerbaru->count() > 0)
                <div class="list-group">
                    @foreach($transaksiTerbaru as $transaksi)
                    @php
                    $isPemasukan = $transaksi->total < 0;
                        $jenis=$isPemasukan ? 'Pemasukan' : 'Pengeluaran' ;
                        $badgeClass=$isPemasukan ? 'bg-success' : 'bg-danger' ;
                        $nominal=$isPemasukan ? abs($transaksi->total) : $transaksi->total;
                        @endphp

                        <a href="{{ route('transaksi.show', $transaksi->id) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $transaksi->keterangan ?? 'Transaksi' }}</h6>
                                <span class="badge {{ $badgeClass }}">{{ $jenis }}</span>
                            </div>
                            <p class="mb-1">Rp {{ number_format($nominal, 0, ',', '.') }}</p>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }} •
                                Oleh: {{ $transaksi->user->name ?? 'Admin' }}
                            </small>
                        </a>
                        @endforeach
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-primary btn-sm">
                        Lihat Semua Transaksi <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada transaksi terbaru</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-12">
            <div class="chart-container">
                <h4 class="mb-4"><i class="fas fa-chart-pie me-2"></i>Statistik Cepat</h4>

                <div class="row text-center">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="p-3 border rounded">
                            <div class="text-primary mb-2">
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                            </div>
                            <h5>Rp {{ number_format($danaDariChina, 0, ',', '.') }}</h5>
                            <small class="text-muted">Total Dana dari China</small>
                        </div>
                    </div>

                    <div class="col-md-3 col-6 mb-3">
                        <div class="p-3 border rounded">
                            <div class="text-success mb-2">
                                <i class="fas fa-building fa-2x"></i>
                            </div>
                            <h5>Rp {{ number_format($saldoJambi, 0, ',', '.') }}</h5>
                            <small class="text-muted">Saldo Jambi</small>
                        </div>
                    </div>

                    <div class="col-md-3 col-6 mb-3">
                        <div class="p-3 border rounded">
                            <div class="text-info mb-2">
                                <i class="fas fa-building fa-2x"></i>
                            </div>
                            <h5>Rp {{ number_format($saldoAceh, 0, ',', '.') }}</h5>
                            <small class="text-muted">Saldo Aceh</small>
                        </div>
                    </div>

                    <div class="col-md-3 col-6 mb-3">
                        <div class="p-3 border rounded">
                            <div class="text-danger mb-2">
                                <i class="fas fa-tools fa-2x"></i>
                            </div>
                            <h5>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h5>
                            <small class="text-muted">Total Pengeluaran</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chartInstance = null;

    document.addEventListener('DOMContentLoaded', function() {
        const monthlyDataElement = document.getElementById('monthlyData');
        let monthlyData = [];

        if (monthlyDataElement) {
            monthlyData = JSON.parse(monthlyDataElement.dataset.monthly);
        }

        const months = monthlyData.map(item => item.bulan);
        const pemasukan = monthlyData.map(item => item.pemasukan);
        const pengeluaran = monthlyData.map(item => item.pengeluaran);

        const ctx = document.getElementById('monthlyTrendChart').getContext('2d');

        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                        label: 'Pemasukan',
                        data: pemasukan,
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pengeluaran',
                        data: pengeluaran,
                        backgroundColor: 'rgba(220, 53, 69, 0.7)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // 🔥 ikut parent container
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const counters = document.querySelectorAll('.counter');
        const speed = 100; // semakin kecil semakin cepat

        counters.forEach(counter => {
            const animate = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText.replace(/\./g, '');
                const increment = Math.ceil(target / speed);

                if (count < target) {
                    counter.innerText = new Intl.NumberFormat('id-ID').format(count + increment);
                    setTimeout(animate, 20);
                } else {
                    counter.innerText = new Intl.NumberFormat('id-ID').format(target);
                }
            };

            animate();
        });
    });
</script>
@endpush
@endsection