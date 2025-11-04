<?php

namespace App\Livewire;

use App\Models\CatatanKeuangan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CatatanKeuanganLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // Properti untuk form tambah/edit data
    public $judul;
    public $tipe = 'pengeluaran'; // Nilai default
    public $jumlah;
    public $tanggal;
    // public $keterangan; // Untuk Trix nanti
    // public $gambar; // Untuk upload gambar nanti

    /**
     * Aturan validasi
     */
    protected $rules = [
        'judul'   => 'required|string|max:255',
        'tipe'    => 'required|in:pemasukan,pengeluaran',
        'jumlah'  => 'required|numeric|min:0',
        'tanggal' => 'required|date',
    ];

    /**
     * Method untuk mereset form
     */
    public function resetForm()
    {
        $this->reset(['judul', 'tipe', 'jumlah', 'tanggal']);
        $this->resetErrorBag(); // Hapus pesan error
        $this->tipe = 'pengeluaran'; // Kembalikan ke default
    }

    /**
     * Method untuk menyimpan data baru
     */
    public function save()
    {
        // Validasi data
        $this->validate();

        try {
            // Buat data baru
            CatatanKeuangan::create([
                'user_id' => Auth::id(),
                'judul'   => $this->judul,
                'tipe'    => $this->tipe,
                'jumlah'  => $this->jumlah,
                'tanggal' => $this->tanggal,
            ]);

            // Reset form
            $this->resetForm();

            // Tutup modal
            $this->dispatch('closeModal', ['id' => 'add-catatan-modal']);

            // Opsional: Kirim notifikasi sukses (akan diimplementasikan nanti)

        } catch (\Exception $e) {
            // Tangani error (opsional)
            // dd($e->getMessage());
        }
    }

    public function render()
    {
        $catatans = CatatanKeuangan::where('user_id', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.catatan-keuangan-livewire', [
            'catatans' => $catatans,
        ]);
    }
}