<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CatatanKeuanganController extends Controller
{
    /**
     * Menampilkan halaman utama Catatan Keuangan.
     */
    public function index(): View
    {
        return view('pages.app.catatan-keuangan.index');
    }
}