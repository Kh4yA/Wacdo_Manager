<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Products;
use App\Models\Categories;

class TestController extends BaseController
{

    protected $product;
    protected $categories;
    protected $user;

    function __construct()
    {
        if ($this->product === null) {
            $this->product = new Products();
        }
        if ( $this->categories === null){
            $this->categories = new Categories();
        }
        if ( $this->user === null){
            $this->user = new User();
        }
    }
    public function testControl()
    {
        echo '<pre>';   
        $user = $this->user->load(1);
        echo '</pre>';
    }
}