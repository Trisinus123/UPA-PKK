<?php

namespace App\Http\Controllers;

use App\Models\CategoryJob;
use Illuminate\Http\Request;

class CategoryJobController extends Controller
{
    // Tampilkan semua data
       public function index()
    {
        $categories = CategoryJob::orderBy('id', 'asc')->paginate(10);
        return view('category-job.index', compact('categories'));
    }


    // Tampilkan form tambah data
    public function create()
    {
        return view('category-job.create');
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_category' => 'required|string|max:100',
        ]);

        CategoryJob::create($request->all());
        return redirect()->route('category-job.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Tampilkan detail 1 data
    public function show($id)
    {
        $category = CategoryJob::findOrFail($id);
        return view('category-job.show', compact('category'));
    }

    // Tampilkan form edit
    public function edit($id)
    {
        $category = CategoryJob::findOrFail($id);
        return view('category-job.edit', compact('category'));
    }

    // Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_category' => 'required|string|max:100',
        ]);

        $category = CategoryJob::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('category-job.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    // Hapus data
    public function destroy($id)
    {
        $category = CategoryJob::findOrFail($id);
        $category->delete();

        return redirect()->route('category-job.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
