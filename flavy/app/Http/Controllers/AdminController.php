<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    public function index() {
        return view('admin.index');
    }

    public function edit() {
        return view('admin.edit');
    }
}
