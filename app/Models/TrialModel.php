<?php

namespace App\Models;

use CodeIgniter\Model;

class TrialModel extends Model
{
    protected $table = 'trial';  // Nama tabel
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'email']; // Kolom yang diizinkan untuk disisipkan/diperbarui
}
