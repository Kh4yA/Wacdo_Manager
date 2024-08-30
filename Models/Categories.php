<?php

namespace App\Models;

use App\Utils\_Model;

class Categories extends _Model
{
    protected $table = 'categories';
     protected $fields = ['label', 'picture'];
}