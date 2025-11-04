@extends('layouts.app')

@section('title', 'Catatan Keuangan')

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Catatan Keuangan</h4>
                            
                            {{-- Tombol ini akan tetap berfungsi memanggil modal --}}
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-catatan-modal">
                                Tambah Data
                            </button>
                        </div>
                        <div class="card-body">
                            {{-- Memuat Livewire Component (yang sekarang sudah berisi modal) --}}
                            <livewire:catatan-keuangan-livewire />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- 
        Pemanggilan modal @include Dihapus dari sini 
        karena sudah dipindahkan ke dalam file livewire component.
    --}}

@endsection