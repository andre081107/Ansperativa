<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\Controller;

class ProductController extends Controller
{
    protected $productModel;

    // Constructor untuk menginisialisasi model yang digunakan pada controller ini
    // public function __construct()
    // {
    //     $this->productModel = new ProductModel();
    // }

    // Method untuk menampilkan data produk ke dalam view
    public function index()
    {
        // Mengambil data produk dari model
        // $products = $this->productModel->findAll();

        // Mengirim data produk ke view
        $data = [
            'title'   => 'Product Traffic',
            // 'product' => $products,  // Mengirim data produk ke view 
        ];

        return view('pages/product', $data);
    }

    public function about() {}
}
