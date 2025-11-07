@extends('layouts.app')

@section('title', 'Catatan Keuangan')

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-12">
                    <livewire:catatan-keuangan-chart />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Data Transaksi</h4>
                            
                            {{-- INI ADALAH KODE YANG BENAR (KEMBALI KE SEMULA) --}}
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-catatan-modal">
                                Tambah Data
                            </button>
                        </div>
                        <div class="card-body">
                            <livewire:catatan-keuangan-livewire />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection