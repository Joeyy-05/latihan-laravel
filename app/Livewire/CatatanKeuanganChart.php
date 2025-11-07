<?php

namespace App\Livewire;

use App\Models\CatatanKeuangan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;

class CatatanKeuanganChart extends Component
{
    // Properti untuk Kartu Ringkasan
    public $pemasukanFiltered = 0;
    public $pengeluaranFiltered = 0;
    public $saldoFiltered = 0;

    // Properti untuk Chart (data total keseluruhan)
    public $chartData = [
        'series' => [],
        'labels' => ['Total Pemasukan', 'Total Pengeluaran'],
    ];

    // Properti untuk menyimpan filter tanggal
    public $tanggalMulai;
    public $tanggalSelesai;

    /**
     * Inisialisasi properti saat component dimuat
     */
    public function mount()
    {
        // Atur default filter tanggal ke "Bulan Ini"
        $this->tanggalMulai = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->tanggalSelesai = Carbon::now()->endOfMonth()->format('Y-m-d');
        
        // Muat data pertama kali
        $this->loadData();
    }

    /**
     * Listener untuk event 'refreshChartData' (saat Tambah/Update/Delete)
     */
    #[On('refreshChartData')] 
    public function loadData()
    {
        $userId = Auth::id();

        // --- 1. Hitung Data untuk Kartu Ringkasan (BERDASARKAN FILTER) ---
        $this->pemasukanFiltered = CatatanKeuangan::where('user_id', $userId)
            ->where('tipe', 'pemasukan')
            ->when($this->tanggalMulai && $this->tanggalSelesai, function ($query) {
                return $query->whereBetween('tanggal', [$this->tanggalMulai, $this->tanggalSelesai]);
            })
            ->sum('jumlah');

        $this->pengeluaranFiltered = CatatanKeuangan::where('user_id', $userId)
            ->where('tipe', 'pengeluaran')
            ->when($this->tanggalMulai && $this->tanggalSelesai, function ($query) {
                return $query->whereBetween('tanggal', [$this->tanggalMulai, $this->tanggalSelesai]);
            })
            ->sum('jumlah');

        $this->saldoFiltered = $this->pemasukanFiltered - $this->pengeluaranFiltered;

        // --- 2. Hitung Data untuk Chart (Total Keseluruhan, TANPA FILTER TANGGAL) ---
        $totalPemasukan = CatatanKeuangan::where('user_id', $userId)
            ->where('tipe', 'pemasukan')
            ->sum('jumlah');

        $totalPengeluaran = CatatanKeuangan::where('user_id', $userId)
            ->where('tipe', 'pengeluaran')
            ->sum('jumlah');

        $this->chartData['series'] = [(float)$totalPemasukan, (float)$totalPengeluaran];

        // Kirim data baru ke browser untuk diperbarui oleh JavaScript
        $this->dispatch('update-chart', data: $this->chartData);
    }
    
    /**
     * Listener BARU untuk event 'tanggalFilterUpdated' (saat filter tanggal diubah)
     */
    #[On('tanggalFilterUpdated')] 
    public function updateTanggal($tanggalMulai, $tanggalSelesai)
    {
        // 1. Simpan tanggal baru
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalSelesai = $tanggalSelesai;
        
        // 2. Muat ulang semua data
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.catatan-keuangan-chart');
    }
}