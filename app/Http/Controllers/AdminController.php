<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']); // Pastikan hanya admin yang bisa mengakses
    }

    public function index()
    {
        return view('admin.home');
    }
}
