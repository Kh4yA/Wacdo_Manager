<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Detail_order;
use App\Controllers\BaseController;

class TestController extends BaseController
{

    protected $product;
    protected $categories;
    protected $user;
    protected $products;
    protected $orders;
    protected $detail_order;
    protected $orderCurrent;

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
            $this->products = new Products();
            $this->categories = new Categories();
            $this->orders = new Orders();
            $this->detail_order = new Detail_order();
    
        }
    }
    public function testControl()
    {
        echo '<pre>';   
        $detailOrder = $this->detail_order->showOrderWithFilter('PREPARE');
        $orders = $this->orders->loadWithOrderNumber('orderFPS');
        $listeCategories = $this->categories->listEtendue();
            print_r($listeCategories);
    }
}