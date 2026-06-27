<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecretaireDashboardController extends Controller
{
    public function index()
    {
        return view('secretaire.dashboard');
    }
}
