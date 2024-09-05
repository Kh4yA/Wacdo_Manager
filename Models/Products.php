<?php

namespace App\Models;

use App\Utils\_Model;
/**
 * clas qui gere les produits en bdd
 */
class Products extends _Model
{
    protected $table = 'products';
    protected $fields = ['categories_id', 'name', 'description', 'price', 'pictures', 'dispo'];

}