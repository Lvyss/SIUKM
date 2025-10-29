<?php

namespace App\Http\Controllers;

use App\Models\Ukm;
use Illuminate\Http\Request;

class UkmController extends Controller
{
    public function index()
    {
        $ukms = Ukm::all();
        return view('user.ukm.index', compact('ukms'));
    }

    public function show(Ukm $ukm)
    {
        return view('user.ukm.show', compact('ukm'));
    }
}
