<div class="modal fade" id="add-catatan-modal" tabindex="-1" aria-labelledby="add-catatan-modal-label" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-lg"> {{-- Kita buat modal lebih besar (lg) --}}
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="add-catatan-modal-label">Tambah Data Catatan Keuangan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Form akan di-handle oleh method 'save' di Livewire Component --}}
                <form wire:submit="save">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul Catatan</label>
                                <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                    id="judul" wire:model="judul" placeholder="Contoh: Gaji Bulanan">
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal Transaksi</label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                    id="tanggal" wire:model="tanggal">
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipe" class="form-label">Tipe Catatan</label>
                                <select class="form-select @error('tipe') is-invalid @enderror" id="tipe"
                                    wire:model="tipe">
                                    <option value="pengeluaran">Pengeluaran</option>
                                    <option value="pemasukan">Pemasukan</option>
                                </select>
                                @error('tipe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah (Rupiah)</label>
                                <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                    id="jumlah" wire:model="jumlah" placeholder="Contoh: 50000" step="any">
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Keterangan (Opsional - Trix Editor) akan ditambahkan nanti --}}
                    {{-- Gambar (Opsional) akan ditambahkan nanti --}}

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="save">Simpan</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>