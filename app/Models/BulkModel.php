<?php

namespace App\Models;

use CodeIgniter\Model;

class BulkModel extends Model
{
    protected $table = 'otomax'; 
    protected $primaryKey = 'id';
    // protected $allowedFields = ['kode_modul', 'kode_produk', 'perintah', 'aktif', 'prioritas', 'tgl_data'];
}