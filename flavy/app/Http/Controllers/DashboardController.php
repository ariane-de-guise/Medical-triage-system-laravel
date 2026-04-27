<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index() {
        $user = auth()->user();
        return view('dashboard', compact('user'));
    }
}
