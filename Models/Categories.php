<?php

namespace App\Models;

use App\Utils\_Model;

/**
 * gestion des categories en bdd
 */
class Categories extends _Model
{
    protected $table = 'categories';
     protected $fields = ['label', 'picture'];
}