<?php

namespace App\Controllers;

use App\Models\PeformanceMetrixModel;
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
        $target = new PeformanceMetrixModel();

        // Mengirim data produk ke view
        $data = [
            'title'   => 'Product Traffic',
            // 'product' => $products,  // Mengirim data produk ke view 
        ];
        $data['targets'] = $target->where('status', 1)->findAll();

        return view('pages/product', $data);
    }

    public function about() {}
}
