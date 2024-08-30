<?php

namespace App\Models;

use App\Utils\_Model;

class Detail_order extends _Model
{
    protected $table = 'detail_order';
     protected $fields = ['id', 'id_order', 'id_produit', 'id_boisson', 'id_side', 'quantite', 'size'];
}