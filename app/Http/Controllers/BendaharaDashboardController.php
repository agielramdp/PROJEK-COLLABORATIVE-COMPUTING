<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BendaharaDashboardController extends Controller
{
    /**
     * Tampilkan halaman utama Dashboard Bendahara
     */
    public function index()
    {
        return view('bendahara.dashboard');
    }
}
