<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ukm;
use Illuminate\Http\Request;

class UkmAdminController extends Controller
{
    public function index()
    {
        $ukms = Ukm::all();
        return view('admin.ukm.index', compact('ukms'));
    }

    public function create()
    {
        return view('admin.ukm.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $data = $request->only(['nama', 'kategori', 'deskripsi']);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->store('logos', 'public');
            $data['logo'] = $path;
        }

        Ukm::create($data);

        return redirect()->route('admin.ukm.index')->with('success', 'UKM berhasil ditambahkan!');
    }

    public function edit(Ukm $ukm)
    {
        return view('admin.ukm.edit', compact('ukm'));
    }

    public function update(Request $request, Ukm $ukm)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $data = $request->only(['nama', 'kategori', 'deskripsi']);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->store('logos', 'public');
            $data['logo'] = $path;
        }

        $ukm->update($data);

        return redirect()->route('admin.ukm.index')->with('success', 'UKM berhasil diperbarui!');
    }

    public function destroy(Ukm $ukm)
    {
        $ukm->delete();
        return redirect()->route('admin.ukm.index')->with('success', 'UKM dihapus!');
    }
}
