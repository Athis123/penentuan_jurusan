@extends('layouts.stisla')

@section('title', 'Dashboard')

@section('content')
    <div class="section-header">
        <h1>Dashboard</h1>
    </div>

    <div class="section-body">
        <p class="section-title">Selamat datang, <strong>{{ Auth::user()->name }}</strong></p>

        {{-- ── STAT CARDS ────────────────────────────────────────────── --}}
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="far fa-user"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Siswa</h4>
                        </div>
                        <div class="card-body">{{ $data['siswaCount'] }}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="far fa-clipboard"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Kriteria</h4>
                        </div>
                        <div class="card-body">{{ $data['kriteriaCount'] }}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Jurusan Tersedia</h4>
                        </div>
                        <div class="card-body">{{ $data['distribusiJurusan']->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── BARIS CHART 1: Doughnut + Bar Yi ─────────────────────── --}}
        <div class="row">

            {{-- Distribusi Rekomendasi Jurusan (Doughnut) --}}
            <div class="col-lg-5 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-pie mr-2 text-primary"></i>Distribusi Rekomendasi Jurusan</h4>
                    </div>
                    <div class="card-body">
                        <div style="position:relative; height:280px;">
                            <canvas id="chartDoughnut"></canvas>
                        </div>
                        {{-- Legenda manual --}}
                        <div class="mt-3 d-flex justify-content-center flex-wrap" id="legendDoughnut"></div>
                    </div>
                </div>
            </div>

            {{-- Rata-rata Yi per Jurusan (Bar Horizontal) --}}
            <div class="col-lg-7 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-bar mr-2 text-success"></i>Rata-rata Nilai Yi per Jurusan</h4>
                    </div>
                    <div class="card-body">
                        <div style="position:relative; height:280px;">
                            <canvas id="chartRataYi"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── BARIS CHART 2: Top 10 Siswa (Bar Vertikal) ───────────── --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-trophy mr-2 text-warning"></i>Top 10 Siswa Berdasarkan Nilai Yi</h4>
                        <div class="card-header-action">
                            <small class="text-muted">Diurutkan dari nilai Yi tertinggi</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="position:relative; height:320px;">
                            <canvas id="chartTop10"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ── DATA DARI LARAVEL ───────────────────────────────────────────
            const distribusi = @json($data['distribusiJurusan']);
            const rataYi = @json($data['rataYiJurusan']);
            const top10 = @json($data['top10']);

            // ── PALET WARNA ─────────────────────────────────────────────────
            const palette = {
                TKJ: {
                    bg: 'rgba(52,  144, 220, 0.85)',
                    border: '#3490dc'
                },
                DKV: {
                    bg: 'rgba(229, 57,  53,  0.85)',
                    border: '#e53935'
                },
                TG: {
                    bg: 'rgba(56,  161, 105, 0.85)',
                    border: '#38a169'
                },
            };

            const fallback = [{
                    bg: 'rgba(99,102,241,0.85)',
                    border: '#6366f1'
                },
                {
                    bg: 'rgba(245,158, 11,0.85)',
                    border: '#f59e0b'
                },
                {
                    bg: 'rgba(20,184,166,0.85)',
                    border: '#14b8a6'
                },
            ];

            function getColor(label, index) {
                return palette[label] ?? fallback[index % fallback.length];
            }

            // ── 1. DOUGHNUT — Distribusi Jurusan ────────────────────────────
            const doughnutLabels = Object.keys(distribusi);
            const doughnutData = Object.values(distribusi);
            const doughnutColors = doughnutLabels.map((l, i) => getColor(l, i));

            const ctxD = document.getElementById('chartDoughnut').getContext('2d');
            new Chart(ctxD, {
                type: 'doughnut',
                data: {
                    labels: doughnutLabels,
                    datasets: [{
                        data: doughnutData,
                        backgroundColor: doughnutColors.map(c => c.bg),
                        borderColor: doughnutColors.map(c => c.border),
                        borderWidth: 2,
                        hoverOffset: 10,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.label}: ${ctx.parsed} siswa`
                            }
                        }
                    }
                }
            });

            // Legenda manual supaya tampil rapi
            const legendEl = document.getElementById('legendDoughnut');
            doughnutLabels.forEach((label, i) => {
                const color = doughnutColors[i];
                const total = doughnutData.reduce((a, b) => a + b, 0);
                const pct = ((doughnutData[i] / total) * 100).toFixed(1);
                legendEl.innerHTML += `
                <div class="d-flex align-items-center mr-3 mb-1">
                    <span style="display:inline-block;width:12px;height:12px;border-radius:50%;
                                 background:${color.border};margin-right:6px;"></span>
                    <small><strong>${label}</strong>: ${doughnutData[i]} siswa (${pct}%)</small>
                </div>`;
            });

            // ── 2. BAR HORIZONTAL — Rata-rata Yi per Jurusan ────────────────
            const rataLabels = Object.keys(rataYi);
            const rataData = Object.values(rataYi);
            const rataColors = rataLabels.map((l, i) => getColor(l, i));

            const ctxR = document.getElementById('chartRataYi').getContext('2d');
            new Chart(ctxR, {
                type: 'bar',
                data: {
                    labels: rataLabels,
                    datasets: [{
                        label: 'Rata-rata Yi',
                        data: rataData,
                        backgroundColor: rataColors.map(c => c.bg),
                        borderColor: rataColors.map(c => c.border),
                        borderWidth: 2,
                        borderRadius: 6,
                    }]
                },
                options: {
                    indexAxis: 'y', // horizontal bar
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` Yi rata-rata: ${ctx.parsed.x}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });

            // ── 3. BAR VERTIKAL — Top 10 Siswa ──────────────────────────────
            const top10Labels = top10.map(s => s.nama);
            const top10Data = top10.map(s => s.yi);
            const top10BgColors = top10.map((s, i) => {
                if (i === 0) return 'rgba(245, 158,  11, 0.90)'; // gold
                if (i === 1) return 'rgba(156, 163, 175, 0.90)'; // silver
                if (i === 2) return 'rgba(180, 120,  60, 0.90)'; // bronze
                return 'rgba(52, 144, 220, 0.75)';
            });
            const top10BorderColors = top10.map((s, i) => {
                if (i === 0) return '#f59e0b';
                if (i === 1) return '#9ca3af';
                if (i === 2) return '#b4783c';
                return '#3490dc';
            });

            const ctxT = document.getElementById('chartTop10').getContext('2d');
            new Chart(ctxT, {
                type: 'bar',
                data: {
                    labels: top10Labels,
                    datasets: [{
                        label: 'Nilai Yi',
                        data: top10Data,
                        backgroundColor: top10BgColors,
                        borderColor: top10BorderColors,
                        borderWidth: 2,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                afterLabel: ctx => {
                                    const item = top10[ctx.dataIndex];
                                    return `Jurusan: ${item.jurusan}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                maxRotation: 30,
                                minRotation: 20,
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.06)'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });

        });
    </script>
@endpush
