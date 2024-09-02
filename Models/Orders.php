<?php

namespace App\Models;

use App\Utils\_Model;

class Orders extends _Model
{
    protected $table = 'orders';
    protected $fields = ["id", "number_order", "date", "statut", "user_id", "price"];
    /**
     * role : retourner l'id avex le numero de commande en parametre
     * @return string id de la commande
     */
    public function getIdWithOrderNumber($order_number)
    {
        $sql = "SELECT`id` FROM `$this->table` WHERE `number_order` = :orderNumber";
        $param = [':orderNumber' => $order_number];
        global $bdd;
        $req = $bdd->fetch($sql, $param);
        return $req["id"];
    }
    /**
     * role charger l'objet avec le numero de commande
     * @param string $number_order(numero de commande)
     */
    public function loadWithOrderNumber($number_order)
    {
        $sql = "  SELECT " . $this->listField() . " FROM `$this->table` WHERE `number_order` = :orderNumber";
        $param = [':orderNumber' => $number_order];
        global $bdd;
        $obj = $bdd->fetch($sql, $param);
        if ($obj) {
            $this->id = $obj["id"];
            foreach ($this->fields as $data) {
            $this->values[$data] = $obj[$data];
            }
            return $this;
        }
        return false;
    }
}
