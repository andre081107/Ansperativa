<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePeformanceMatrixTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'amount' => [
                'type'       => 'INT',
                'unique'     => true
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'profit' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'INT',
                'default'    => '1'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('peformancematrix');
    }

    public function down()
    {
        $this->forge->dropTable('peformancematrix', true);
    }
}
