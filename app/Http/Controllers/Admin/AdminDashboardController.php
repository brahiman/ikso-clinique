<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Tu peux ajouter ici des statistiques globales plus tard
        return view('admin.dashboard');
    }
}
