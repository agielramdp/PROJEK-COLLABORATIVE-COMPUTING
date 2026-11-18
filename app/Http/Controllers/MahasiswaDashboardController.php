<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaDashboardController extends Controller
{
    /**
     * Tampilkan halaman utama Dashboard Mahasiswa
     */
    public function index()
    {
        return view('mahasiswa.dashboard');
    }
}
