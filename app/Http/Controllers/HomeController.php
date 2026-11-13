<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Testimoni;

class HomeController extends Controller
{
    public function index()
    {
        $layanan   = Layanan::orderBy('harga')->get();
        $testimoni = Testimoni::with('user')
                        ->where('status', 'disetujui')
                        ->orderByDesc('id_testimoni')
                        ->limit(6)
                        ->get();

        return view('home', compact('layanan', 'testimoni'));
    }
}
