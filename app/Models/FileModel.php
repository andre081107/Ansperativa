<?php

namespace App\Models;

use CodeIgniter\Model;

class FileModel extends Model
{
    protected $table = 'file'; 
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_modul', 'kode_produk', 'perintah', 'aktif', 'prioritas', 'tgl_data'];
}