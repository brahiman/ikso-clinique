<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MedecinDashboardController extends Controller
{
    public function index()
    {
        // On peut filtrer par médecin connecté plus tard
        return view('medecin.dashboard');
    }
}
