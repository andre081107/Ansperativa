<?php

namespace App\Controllers;

use App\Models\BulkModel;
use CodeIgniter\Controller;

class BulkController extends Controller
{
    protected $bulkModel;

    public function __construct()
    {
        $this->bulkModel = new BulkModel();
    }

    // Menampilkan data tabel perintah
    public function index()
    {
        // Mengambil semua data dari tabel perintah
        // $data['otomax'] = $this->bulkModel->findAll();

        // Mengirim data ke view
        return view('pages/bulk');
    }

    // Fungsi untuk menambah data perintah
    // public function about()
    // {
    //     // Mendapatkan data dari form
    //     $data = [
    //         'kode_modul' => $this->request->getPost('kode_modul'),
    //         'kode_produk' => $this->request->getPost('kode_produk'),
    //         'perintah' => $this->request->getPost('perintah'),
    //         'prioritas' => $this->request->getPost('prioritas')
    //     ];

    //     // Memasukkan data baru ke dalam tabel
    //     $this->bulkModel->insert($data);

    //     // Mengarahkan kembali ke halaman utama setelah data ditambahkan
    //     return redirect()->to('pages/bulk');
    // }
}
