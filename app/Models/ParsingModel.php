<?php

namespace App\Models;

use CodeIgniter\Model;

class ParsingModel extends Model
{
    protected $table = 'parsing';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'excel_file'];
}