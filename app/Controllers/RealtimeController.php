<?php

namespace App\Controllers;

use App\Models\PeformanceMetrixModel;
use CodeIgniter\Controller;

class RealtimeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Realtime',
        ];
        $target = new PeformanceMetrixModel();

       $data['targets'] =  $target->findAll();
        return view('pages/realtime', $data);
    }
}