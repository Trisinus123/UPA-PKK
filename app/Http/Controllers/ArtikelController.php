<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    //crud artikel
    public function index()
    {
        //ambil data artikel dari database
        $artikel = Artikel::all();
        return view('artikel.index', compact('artikel'));
    }

    public function create()
    {
        return view('artikel.create');
    }

    public function store(Request $request)
    {
        //validasi
        $request->validate([
            'judul_artikel' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif',
        ]);

        //simpan data ke database
        $artikel = new Artikel();
        $artikel->judul_artikel = $request->judul_artikel;
        $artikel->deskripsi = $request->deskripsi;

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/gambar_artikel', $filename);
            $artikel->gambar = $filename;
        }
        
        $artikel->save();

        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil ditambahkan!');

    }

    public function show($id)
    {
        $artikel = Artikel::find($id);
        return view('artikel.show', compact('artikel'));
    }

    public function edit($id)
    {
        $artikel = Artikel::find($id);
        return view('artikel.edit', compact('artikel'));
    }

    public function update(Request $request, $id)
    {
        //validasi
        $request->validate([
            'judul_artikel' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        //update data ke database
        $artikel = Artikel::find($id);
        $artikel->judul_artikel = $request->judul_artikel;
        $artikel->deskripsi = $request->deskripsi;

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/gambar_artikel', $filename);
            $artikel->gambar = $filename;
        }

        $artikel->save();

        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil diupdate!');
    }

    public function destroy($id)
    {
        $artikel = Artikel::find($id);
        $artikel->delete();

        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil dihapus!');
    }
    
}

