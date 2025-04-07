<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
        ];
        return view('pages/dashboard', $data);
    }

    public function about()
    {

    }

    public function show()
    {
 
    }
}
