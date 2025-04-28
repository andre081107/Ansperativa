<?php

namespace App\Controllers;

use App\Models\PeformanceMetrixModel;
use CodeIgniter\Controller;

class PeformanceMatrixController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Peformance Matix',
        ];
        $target = new PeformanceMetrixModel();

       $data['targets'] =  $target->findAll();
        return view('pages/peformance', $data);
    }

    public function save()
    {
        $peforma = new PeformanceMetrixModel();

        $data = [
            'amount'     => $this->request->getPost('transaksi'),
            'category'    => $this->request->getPost('kategori'),
            'profit'    => $this->request->getPost('profit'),
           
        ];
// return $data;

        $peforma->insert($data);

        return redirect()->to('/peformance')->with('message', 'Target Product Peformance Added!');

    }


}
