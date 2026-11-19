<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosenWaliDashboardController extends Controller
{
    /**
     * Tampilkan halaman utama Dashboard Dosen Wali
     */
    public function index()
    {
        return view('dosen-wali.dashboard');
    }
}
