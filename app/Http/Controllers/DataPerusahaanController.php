<?php

namespace App\Http\Controllers;

use App\Models\PerusahaanProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DataPerusahaanController extends Controller
{
    // Tampilkan semua data perusahaan
    public function index()
    {
        $perusahaans = PerusahaanProfile::with('user')->get();
        return view('data-perusahaan.index', compact('perusahaans'));
    }

    // Tampilkan form tambah data
    public function create()
    {
        return view('data-perusahaan.create');
    }

    // Simpan data perusahaan baru
    public function store(Request $request)
    {
        $request->validate([
            'website' => 'nullable|url',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string|max:255',
        ]);
    
        $data = $request->except('foto');
        $data['user_id'] = Auth::id(); // ambil ID user yang sedang login
    
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_perusahaan', 'public');
        }
    
        PerusahaanProfile::create($data);
    
        return redirect()->route('data-perusahaan.index')->with('success', 'Data perusahaan berhasil disimpan.');
    }
    // Tampilkan detail perusahaan
    public function show($id)
    {
        $perusahaan = PerusahaanProfile::findOrFail($id);
        return view('data-perusahaan.show', compact('perusahaan'));
    }

    // Form edit
    public function edit($id)
    {
        $perusahaan = PerusahaanProfile::findOrFail($id);
        return view('data-perusahaan.edit', compact('perusahaan'));
    }

    // Update data
    public function update(Request $request, $id)
    {
        $perusahaan = PerusahaanProfile::findOrFail($id);
    
        $request->validate([
            'website' => 'nullable|url',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string|max:255',
        ]);
    
        $data = $request->except('foto');
        $data['user_id'] = Auth::id(); // pastikan user_id diupdate sesuai yang login
    
        if ($request->hasFile('foto')) {
            if ($perusahaan->foto && Storage::disk('public')->exists($perusahaan->foto)) {
                Storage::disk('public')->delete($perusahaan->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto_perusahaan', 'public');
        }
    
        $perusahaan->update($data);
    
        return redirect()->route('data-perusahaan.index')->with('success', 'Data perusahaan berhasil diperbarui.');
    }

    // Hapus data
    public function destroy($id)
    {
        $perusahaan = PerusahaanProfile::findOrFail($id);

        // Hapus foto jika ada
        if ($perusahaan->foto && Storage::disk('public')->exists($perusahaan->foto)) {
            Storage::disk('public')->delete($perusahaan->foto);
        }

        $perusahaan->delete();

        return redirect()->route('data-perusahaan.index')->with('success', 'Data perusahaan berhasil dihapus.');
    }
}
