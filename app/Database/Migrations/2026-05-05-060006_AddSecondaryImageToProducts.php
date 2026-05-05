<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSecondaryImageToProducts extends Migration
{
    public function up()
    {
        $fields = [
            'image_secondary' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'image'
            ],
        ];
        $this->forge->addColumn('products', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'image_secondary');
    }
}
