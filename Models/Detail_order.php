<?php

namespace App\Models;

use App\Utils\_Model;

class Detail_order extends _Model
{
    protected $table = 'detail_order';
     protected $fields = ['id_order', 'id_produit', 'id_boisson', 'id_side', 'quantite', 'size'];

         /**
     * role : affiche le detail d'une commande
     * @param string ($order_number) numero de la commande
     */
    public function showOrderDetail($order_number)
    {
        $sql = "SELECT `detail_order`.`id`,`orders`.`number_order`,`quantite`, `size` ,`produit`.`name` as `libelle_product`,`produit`.`price`, `boisson`.`name`as `libelle_boisson`,`side`.`name` as `libelle_side`
        FROM `detail_order` 
        LEFT JOIN `orders` ON `detail_order`.`id_order` = `orders`.`id`
        LEFT JOIN `products` as produit ON `detail_order`.`id_produit` = `produit`.`id`
        LEFT JOIN `products` as boisson ON `detail_order`.`id_boisson` = `boisson`.`id`
        LEFT JOIN `products` as side ON `detail_order`.`id_side` = `side`.`id`
        WHERE `orders`.`number_order` = :order";
        $param = [':order' => $order_number];
        global $bdd;
        $req = $bdd->fetchAll($sql, $param);
        return $req;
    }
}