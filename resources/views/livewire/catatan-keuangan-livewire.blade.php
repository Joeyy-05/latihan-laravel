<div>

    <div class="row mb-3 gy-2">
        <div class="col-md-3">
            <label for="search" class="form-label-sm">Cari Judul</label>
            <input type="text" id="search" class="form-control" placeholder="Cari berdasarkan judul..."
                   wire:model.live.debounce.300ms="search">
        </div>
        <div class="col-md-2">
            <label for="filterTipe" class="form-label-sm">Tipe</label>
            <select id="filterTipe" class="form-select" wire:model.live="filterTipe">
                <option value="">Semua Tipe</option>
                <option value="pemasukan">Pemasukan</option>
                <option value="pengeluaran">Pengeluaran</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="tanggalMulai" class="form-label-sm">Dari Tanggal</label>
            <input type="date" id="tanggalMulai" class="form-control" wire:model.live="tanggalMulai">
        </div>
        <div class="col-md-3">
            <label for="tanggalSelesai" class="form-label-sm">Sampai Tanggal</label>
            <input type="date" id="tanggalSelesai" class="form-control" wire:model.live="tanggalSelesai">
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white">
            <thead class="table-primary">
                <tr>
                    <th style="width: 5%;">#</th>
                    <th>Tanggal</th>
                    <th>Judul</th>
                    <th>Tipe</th>
                    <th>Jumlah (Rp)</th>
                    <th>Gambar</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($catatans as $index => $catatan)
                    {{-- 
                        PERUBAHAN DI SINI:
                        Menambahkan class kondisional ke <tr>
                    --}}
                    <tr class="@if ($catatan->tipe == 'pemasukan') bg-success-subtle @else bg-danger-subtle @endif">
                        <td>{{ $catatans->firstItem() + $index }}</td> 
                        <td>{{ $catatan->tanggal->format('d M Y') }}</td>
                        <td>{{ $catatan->judul }}</td>
                        <td>
                            @if ($catatan->tipe == 'pemasukan')
                                <span class="badge bg-success">Pemasukan</span>
                            @else
                                <span class="badge bg-danger">Pengeluaran</span>
                            @endif
                        </td>
                        <td class="text-end">{{ number_format($catatan->jumlah, 2, ',', '.') }}</td>
                        <td>
                            @if ($catatan->gambar)
                                <a href="{{ asset('storage/' . $catatan->gambar) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $catatan->gambar) }}" alt="Bukti" style="width: 80px; height: auto;">
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning" 
                                    wire:click="showEditModal({{ $catatan->id }})">Edit</button>
                            <button class="btn btn-sm btn-danger" 
                                    onclick="confirmDelete({{ $catatan->id }})">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            @if ($search !== '' || $filterTipe !== '' || $tanggalMulai || $tanggalSelesai)
                                Tidak ada data yang cocok dengan filter Anda.
                            @else
                                Belum ada data catatan keuangan.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-end">
        {{ $catatans->links() }}
    </div>

    {{-- Modals --}}
    @include('components.modals.catatan-keuangan.add')
    @include('components.modals.catatan-keuangan.edit')

    <script>
        // Helper untuk mendapatkan instance modal
        function getModalInstance(id) {
            const modalElement = document.getElementById(id);
            if (!modalElement) return null;
            return bootstrap.Modal.getOrCreateInstance(modalElement);
        }

        // Fungsi untuk konfirmasi hapus
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('destroy-data', { id: id });
                }
            });
        }

        // Helper untuk notifikasi Toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        // Event Listeners (Livewire 3+)
        document.addEventListener('livewire:initialized', () => {
           
           // Listener untuk modal Tambah
           Livewire.on('close-add-modal', (event) => {
                getModalInstance('add-catatan-modal')?.hide();
           });
           
           // Listener untuk modal Edit
           Livewire.on('show-edit-modal', (event) => {
                getModalInstance('edit-catatan-modal')?.show();
           });
           Livewire.on('close-edit-modal', (event) => {
                getModalInstance('edit-catatan-modal')?.hide();
           });
           
           // Listener notifikasi toast Hapus
           Livewire.on('data-deleted-success', ({ message }) => {
                Toast.fire({
                    icon: 'success',
                    title: message
                });
           });
           
           // Listener notifikasi toast Tambah/Edit
           Livewire.on('data-saved-success', ({ message }) => {
                Toast.fire({
                    icon: 'success',
                    title: message
                });
           });
        });
    </script>
</div>