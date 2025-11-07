<div class="modal fade" id="edit-catatan-modal" tabindex="-1" aria-labelledby="edit-catatan-modal-label" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="edit-catatan-modal-label">Edit Data Catatan Keuangan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit="update">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul Catatan</label>
                                <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                    id="judul" wire:model="judul" placeholder="Contoh: Gaji Bulanan">
                                @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal Transaksi</label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                    id="tanggal" wire:model="tanggal">
                                @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                                @error('tipe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah (Rupiah)</label>
                                <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                    id="jumlah" wire:model="jumlah" placeholder="Contoh: 50000" step="any">
                                @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Ubah Bukti Gambar (Opsional)</label>
                        <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                               id="gambar" wire:model="gambar">
                        @error('gambar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div wire:loading wire:target="gambar" class="text-muted mt-1">
                            Mengupload...
                        </div>
                        @if ($gambar)
                            <div class="mt-2">
                                <span class="text-muted">Preview Gambar Baru:</span>
                                <img src="{{ $gambar->temporaryUrl() }}" alt="Preview" class="img-thumbnail" style="width: 200px;">
                            </div>
                        @elseif ($gambar_existing)
                            <div class="mt-2">
                                <span class="text-muted">Gambar Saat Ini:</span>
                                <img src="{{ asset('storage/' . $gambar_existing) }}" alt="Gambar Saat Ini" class="img-thumbnail" style="width: 200px;">
                            </div>
                        @endif
                    </div>

                    {{-- BLOK TRIX EDITOR DIHAPUS DARI SINI --}}

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="update">Simpan Perubahan</span>
                            <span wire:loading wire:target="update">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>