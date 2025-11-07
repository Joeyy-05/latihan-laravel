<?php

namespace App\Livewire;

use App\Models\CatatanKeuangan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Carbon; 
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On; 
use Livewire\WithFileUploads; 
use App\Livewire\CatatanKeuanganChart; 

class CatatanKeuanganLivewire extends Component
{
    use WithPagination;
    use WithFileUploads; 
    protected $paginationTheme = 'bootstrap';

    // Properti untuk form
    public $judul;
    public $tipe = 'pengeluaran';
    public $jumlah;
    public $tanggal;
    public $selectedId; 
    public $gambar; 
    public $gambar_existing; 

    // Properti untuk Filter
    public $search = '';
    public $filterTipe = '';
    public $tanggalMulai; 
    public $tanggalSelesai; 

    protected $rules = [
        'judul'      => 'required|string|max:255',
        'tipe'       => 'required|in:pemasukan,pengeluaran',
        'jumlah'     => 'required|numeric|min:0',
        'tanggal'    => 'required|date',
        'gambar'     => 'nullable|image|max:2048', 
    ];

    /**
     * Inisialisasi properti saat component dimuat
     */
    public function mount()
    {
        // Atur default filter tanggal ke "Bulan Ini"
        $this->tanggalMulai = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->tanggalSelesai = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    // (Hooks 'updatedSearch' & 'updatedFilterTipe' tidak berubah)
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterTipe() { $this->resetPage(); }
    
    // --- HOOKS BARU UNTUK TANGGAL ---
    public function updatedTanggalMulai() { $this->dispatchDates(); }
    public function updatedTanggalSelesai() { $this->dispatchDates(); }

    /**
     * Helper untuk mengirim event ke Chart dan reset paginasi
     */
    public function dispatchDates()
    {
        $this->resetPage();
        // Kirim event HANYA ke component chart
        $this->dispatch('tanggalFilterUpdated', 
            tanggalMulai: $this->tanggalMulai, 
            tanggalSelesai: $this->tanggalSelesai
        )->to(CatatanKeuanganChart::class);
    }
    // --- BATAS HOOKS BARU ---


    public function resetForm()
    {
        $this->reset(['judul', 'tipe', 'jumlah', 'tanggal', 'selectedId', 'gambar', 'gambar_existing']);
        $this->resetErrorBag();
        $this->tipe = 'pengeluaran';
    }

    public function showEditModal($id)
    {
        $this->resetForm();
        try {
            $catatan = CatatanKeuangan::where('user_id', Auth::id())->findOrFail($id); 
            $this->selectedId = $catatan->id;
            $this->judul      = $catatan->judul;
            $this->tipe       = $catatan->tipe;
            $this->jumlah     = $catatan->jumlah;
            $this->tanggal    = $catatan->tanggal->format('Y-m-d'); 
            $this->gambar_existing = $catatan->gambar; 
            $this->dispatch('show-edit-modal'); 
        } catch (\Exception $e) { }
    }

    public function save()
    {
        $this->validate();
        try {
            // ==========================================================
            // INI ADALAH PERBAIKANNYA:
            // Logika upload gambar dikembalikan ke metode save()
            // ==========================================================
            $gambarPath = null;
            if ($this->gambar) {
                $gambarPath = $this->gambar->store('gambar-catatan', 'public');
            }

            CatatanKeuangan::create([
                'user_id'    => Auth::id(), 
                'judul'      => $this->judul,
                'tipe'       => $this->tipe,
                'jumlah'     => $this->jumlah,
                'tanggal'    => $this->tanggal,
                'gambar'     => $gambarPath, // <-- Sekarang $gambarPath sudah benar
            ]);
            
            $this->resetForm();
            $this->dispatch('close-add-modal');
            $this->dispatch('refreshChartData')->to(CatatanKeuanganChart::class); 
            $this->dispatch('data-saved-success', message: 'Data berhasil disimpan!'); 
        } catch (\Exception $e) { }
    }

    public function update()
    {
        $this->validate();
        try {
            $catatan = CatatanKeuangan::where('user_id', Auth::id())->findOrFail($this->selectedId); 
            $gambarPath = $catatan->gambar; 
            if ($this->gambar) {
                if ($catatan->gambar) {
                    Storage::disk('public')->delete($catatan->gambar);
                }
                $gambarPath = $this->gambar->store('gambar-catatan', 'public');
            }
            $catatan->update([
                'judul'      => $this->judul,
                'tipe'       => $this->tipe,
                'jumlah'     => $this->jumlah,
                'tanggal'    => $this->tanggal,
                'gambar'     => $gambarPath,
            ]);
            $this->resetForm();
            $this->dispatch('close-edit-modal');
            $this->dispatch('refreshChartData')->to(CatatanKeuanganChart::class); 
            $this->dispatch('data-saved-success', message: 'Data berhasil diperbarui!');
        } catch (\Exception $e) { }
    }

    #[On('destroy-data')] 
    public function destroy($id)
    {
        try {
            $catatan = CatatanKeuangan::where('user_id', Auth::id())->findOrFail($id); 
            if ($catatan->gambar) {
                Storage::disk('public')->delete($catatan->gambar);
            }
            $catatan->delete();
            $this->dispatch('data-deleted-success', message: 'Data berhasil dihapus!');
            $this->dispatch('refreshChartData')->to(CatatanKeuanganChart::class); 
        } catch (\Exception $e) { }
    }


    public function render()
    {
        $catatans = CatatanKeuangan::where('user_id', Auth::id()) 
            ->when($this->filterTipe !== '', function ($query) {
                return $query->where('tipe', $this->filterTipe);
            })
            ->when($this->search !== '', function ($query) {
                return $query->where('judul', 'like', '%' . $this->search . '%');
            })
            ->when($this->tanggalMulai && $this->tanggalSelesai, function ($query) {
                return $query->whereBetween('tanggal', [$this->tanggalMulai, $this->tanggalSelesai]);
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.catatan-keuangan-livewire', [
            'catatans' => $catatans,
        ]);
    }
}