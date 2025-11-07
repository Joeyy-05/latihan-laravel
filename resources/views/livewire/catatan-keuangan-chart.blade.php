<div>
    {{-- BARIS KARTU RINGKASAN (DIMODIFIKASI) --}}
    <div class="row mb-3">
        {{-- Kartu Pemasukan --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <h6 class="card-title text-success">Pemasukan (Difilter)</h6>
                    {{-- 
                        wire:poll dihapus, karena kita sekarang menggunakan event
                        Properti diganti ke 'pemasukanFiltered'
                    --}}
                    <h3 class="card-text">
                        Rp {{ number_format($pemasukanFiltered, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- Kartu Pengeluaran --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-danger">
                <div class="card-body">
                    <h6 class="card-title text-danger">Pengeluaran (Difilter)</h6>
                    <h3 class="card-text">
                        Rp {{ number_format($pengeluaranFiltered, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- Kartu Saldo --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-primary">
                <div class="card-body">
                    <h6 class="card-title text-primary">Saldo (Difilter)</h6>
                    <h3 class="card-text">
                        Rp {{ number_format($saldoFiltered, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
    </div>
    {{-- BATAS KARTU RINGKASAN --}}


    {{-- Chart (yang sudah ada) --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Statistik Total Keseluruhan (Pemasukan vs Pengeluaran)</h5>
            
            <div wire:ignore>
                <div id="stats-chart"></div>
            </div>
        </div>
    </div>

    {{-- (JavaScript Chart tidak berubah) --}}
    <script>
        document.addEventListener('livewire:init', function () {
            
            let statsDonutChart;

            function renderChart(chartData) {
                var options = {
                    series: chartData.series,
                    chart: {
                        type: 'donut',
                        height: 350
                    },
                    labels: chartData.labels,
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                };

                if (!statsDonutChart) {
                    statsDonutChart = new ApexCharts(document.querySelector("#stats-chart"), options);
                    statsDonutChart.render();
                } else {
                    statsDonutChart.updateSeries(chartData.series);
                }
            }

            renderChart(@json($chartData));

            Livewire.on('update-chart', ({ data }) => {
                renderChart(data);
            });
        });
    </script>
</div>