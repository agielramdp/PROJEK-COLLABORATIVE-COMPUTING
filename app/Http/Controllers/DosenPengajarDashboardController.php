<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosenPengajarDashboardController extends Controller
{
    /**
     * Tampilkan halaman utama Dashboard Dosen Pengajar
     */
    public function index()
    {
        return view('dosen-pengajar.dashboard');
    }
}
