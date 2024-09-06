<?php

namespace App\Models;

use App\Utils\_Model;
/**
 * gestion des details details des commandes
 */
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
        $sql = "SELECT `detail_order`.`id`, `orders`.`number_order`, `detail_order`.`quantite`, `detail_order`.`size`, `produit`.`name` AS `libelle_product`, `produit`.`price`, `boisson`.`name` AS `libelle_boisson`, `side`.`name` AS `libelle_side`, `orders`.`statut`
        FROM `detail_order` 
        LEFT JOIN `orders` ON `detail_order`.`id_order` = `orders`.`id` 
        LEFT JOIN `products` AS `produit` ON `detail_order`.`id_produit` = `produit`.`id` 
        LEFT JOIN `products` AS `boisson` ON `detail_order`.`id_boisson` = `boisson`.`id` 
        LEFT JOIN `products` AS `side` ON `detail_order`.`id_side` = `side`.`id`
        WHERE `orders`.`number_order` = :order";
        $param = [':order' => $order_number];
        global $bdd;
        $req = $bdd->fetchAll($sql, $param);
        return $req;
    }
        /**
     * role : affiche le detail d'une commande
     * @param string ($statut) statut a afficher
     */
    
    public function showOrderWithFilter($statut)
    {
        $sql = "SELECT `orders`.`id`, `number_order`, `date`, `statut`, `price`, `user_id` FROM `orders` WHERE `statut` = :statut LIMIT 50";
        $param = [':statut' => $statut];
        global $bdd;
        $req = $bdd->fetchAll($sql, $param);
        return $req;
    }
}
