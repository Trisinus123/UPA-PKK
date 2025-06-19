<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel; // Import the Artikel model
use App\Models\PerusahaanProfile; // Import the PerusahaanProfile model
use App\Models\Job; // Import the Job model
use App\Models\CategoryJob; // Import the CategoryJob model

class HomeController extends Controller
{
    //show artikel
public function index()
{
    $artikel = Artikel::all();
    $perusahaan = PerusahaanProfile::where('status_perusahaan', 1)->latest()->get();

    // Get job counts per category using the original keys
    $categories = CategoryJob::select('category.*')
        ->selectSub(function($query) {
            $query->select(\DB::raw('COUNT(*)'))
                ->from('jobs')
                ->whereColumn('jobs.category_id', 'category.id');
        }, 'jobs_count')
        ->get();

    return view('/layouts.welcome', compact('artikel', 'perusahaan', 'categories'));
}

public function showArticle($id)
{
    $artikel = Artikel::find($id);

    // Jika artikel tidak ditemukan, tampilkan pesan atau redirect
    if (!$artikel) {
        return redirect()->route('home')->with('error', 'Artikel tidak ditemukan.');
    }

    return view('artikels', compact('artikel'));
}


}
