<?php

namespace App\Models;

use App\Utils\_Model;

class Orders extends _Model
{
    protected $table = 'orders';
     protected $fields = ["id", "number_order", "date", "statut", "user_id"];
}