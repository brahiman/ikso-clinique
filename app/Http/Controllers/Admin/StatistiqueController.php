<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class StatistiqueController extends Controller
{
    public function index()
    {
        return view('admin.statistiques.index');
    }

    public function show(){
        return view('admin.statistiques.show');
    }
}
