<div>
    {{-- Bagian Search dan Filter (akan kita isi nanti) --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="Cari berdasarkan judul...">
        </div>
        <div class="col-md-2">
            <select class="form-select">
                <option value="">Semua Tipe</option>
                <option value="pemasukan">Pemasukan</option>
                <option value="pengeluaran">Pengeluaran</option>
            </select>
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
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($catatans as $index => $catatan)
                    <tr>
                        {{-- Hitung nomor urut berdasarkan pagination --}}
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
                            {{-- Tombol Aksi (akan kita fungsikan nanti) --}}
                            <button class="btn btn-sm btn-warning">Edit</button>
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            Belum ada data catatan keuangan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Link Pagination --}}
    <div class="d-flex justify-content-end">
        {{ $catatans->links() }}
    </div>

    {{-- 
        MODAL DIPINDAHKAN KE SINI. 
        Sekarang modal ini adalah bagian dari component Livewire.
    --}}
    @include('components.modals.catatan-keuangan.add')

</div>