<?php

// controller Equipier qui gere l'interface des equipier

namespace App\Controllers;

use App\Controllers\BaseController;
use Exception;

use App\Models\Orders;

use App\Models\Categories;
use App\Models\Detail_order;
use App\Models\Products;


class EquipierController extends BaseController
{

    protected $categories;
    protected $products;
    protected $orders;
    protected $detail_order;

    function __construct()
    {
        $this->products = new Products();
        $this->categories = new Categories();
        $this->orders = new Orders();
        $this->detail_order = new Detail_order();
    }

    /**
     * Affiche l'interface principale pour l'équipier, incluant les catégories,
     * produits, boissons et sides disponibles.
     *
     * @return void
     */
    public function displayInterfaceEquipier()
    {
        $this->ensureStatus('EQUIPIER');
        $listeCategories = $this->categories->listEtendue();
        $listeProducts = $this->products->listEtendue(["categories_id" => 1]);
        $listeBoissons = $this->products->listEtendue(['label' => 'boissons'], [], 'categories', 'categories_id', 'label', 'label');
        $listeSide = $this->products->listEtendue();
        return $this->render('equipier/interface', [
            "listeCategories" => $listeCategories,
            "listeProduit" => $listeProducts,
            'listeSide' => $listeSide,
            'listeBoissons' => $listeBoissons,
        ]);
    }
    /**
     * Affiche l'interface de gestion des commandes pour l'équipier, 
     * montrant les commandes qui sont prêtes à être préparées.
     *
     * @return void
     */
    public function displayOrderInterface(){
        $this->ensureStatus('EQUIPIER');
        $listOrder = $this->detail_order->showOrderWithFilter('PREPARE');
        return $this->render('equipier/order', [
            'listOrder' => $listOrder,
        ]);
    }
    /**
     * Affiche l'interface de gestion avec les details d'une commande specifique
     */
    public function displayDetailOrder($number_order){
        $this->ensureStatus('EQUIPIER');
        $listOrder = $this->detail_order->showOrderWithFilter('PREPARE');
        $detailOrder = $this->detail_order->showOrderDetail($number_order);
        $order = new Orders();
        $orderCurrent = $order->loadWithOrderNumber($number_order);
        return $this->render('equipier/order', [
            'listOrder' => $listOrder,
            'detailOrder' => $detailOrder,
            'orderCurrent' => $orderCurrent,
        ]);
    }
    /**
     * genere un numero de commande unique
     * @return string
     */
    public function generateOrderId()
    {
        $this->ensureStatus('EQUIPIER');
        // Génère une chaîne de 3 caractères alphanumériques aléatoires
        $randomString = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
        return 'order' . $randomString;
    }
    /**
     * creer une nouvelle commande et ajoute le statut en cours
     *@return string chaine de caractere au format json
     */
    public function createOrder()
    {
        $this->ensureStatus('EQUIPIER');
        $orderId = $this->generateOrderId();
        $order = new Orders();
        if($this->orders->existe($orderId)){
            return json_encode(['error' => 'Le numero de commande existe deja']);
            }else{
                $_SESSION["order"] = $orderId;
                $order->set('number_order', $orderId);
                $order->set('statut', 'EN COURS DE COMMANDE');
                $order->insert();
                return json_encode($orderId);
            }
        }
    /**
     * Ajouter les detail de la commande et insert en basse de donnée
     */
    public function addOrderDetails()
    {
        $this->ensureStatus('EQUIPIER');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        try {
            if (json_last_error() === JSON_ERROR_NONE) {
                if (!isset($input['id_order']) || empty($input['id_order'])) {
                    throw new Exception('id_order est null ou vide');
                }
                $detailOrder = new Detail_order();
                $idOrder = $this->orders->getIdWithOrderNumber($input['id_order']);
                $_SESSION["order"] = $input['id_order'];
                if (!isset($idOrder)) {
                    throw new Exception('l\'id réccupéré est invalide');
                }
                if ($detailOrder->loadFromTab($input)) {
                    $detailOrder->set('id_order', $idOrder);
                    if ($detailOrder->insert()) {
                        echo json_encode(['statut' => 'success', 'message' => 'Données reçues', 'donnée' => $input]);
                    } else {
                        throw new Exception('insertion echoué en base de donnée');
                    }
                } else {
                    throw new Exception('echec du chargement des données');
                }
            } else {
                throw new Exception('JSON recu invalide: ' . json_last_error_msg());
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            error_log('Error: ' . $e->getMessage());
        }
    }
    /**
     * Role  de la fonction : creer un json du detail de la commande pour le reccuperer en js
     *  @return json
     */
    public function getJsonOrderDetails()
    {
        $this->ensureStatus('EQUIPIER');
        $detailOrder = new Detail_order();
        $detailOrder = $this->detail_order->showOrderDetail($_SESSION["order"]);
        $json = array();
        foreach ($detailOrder as $key => $value) {
            $json[$key] = $value;
        }
        return json_encode($json);
    }
    /**
     * Role : supprime un element de la bdd envoyer par l'ajax
     * @param int $id (id du produit a spprimer)
     */
    public function deleteOrderDetail()
    {
        $this->ensureStatus('EQUIPIER');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        try {
            if (isset($input['id'])) {
                $id = $input['id'];
                $detailOrder = new Detail_order($id);
                if ($detailOrder->delete()) {
                    echo json_encode(["statut' => 'success', 'message' => 'l\'element a l\'id $id a éte supprimé"]);
                } else {
                    throw new Exception('echec de la suppression');
                }
            } else {
                throw new Exception('id manquant');
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    /**
     * Role : valider la commande (au clixk sur le bouton payer on passe la commande au statut 'en preparation' et on ajoute la somme de la commande )
     */
    public function validateOrder()
    {
        $this->ensureStatus('EQUIPIER');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['status' => 'error', 'message' => 'Erreur de décodage JSON : ' . json_last_error_msg()]);
            return;
        }

        try {
            if (isset($input['price'])) {
                $order = new Orders();
                $order->loadWithOrderNumber($_SESSION['order']);
                $number_order = $_SESSION['order'];
                $order->set('statut', 'A PREPARER');
                $order->set('price', $input['price']);
                if ($order->update()) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'La commande a été validée',
                        'order' => $number_order,
                        'etat' => "L'état est passé à 'A PREPARER'"
                    ]);
                } else {
                    throw new Exception('Échec de la validation');
                }
            } else {
                throw new Exception('Price manquant');
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    /**
     * role : abandonner une commande
     */
    public function abandonOrder()
    {
        $this->ensureStatus('EQUIPIER');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['status' => 'error', 'message' => 'Erreur de décodage JSON : ' . json_last_error_msg()]);
            return;
        }
        try {
            if (isset($input['order'])) {
                $order = new Orders();
                $order->loadWithOrderNumber($input['order']);
                $number_order = $input['order'];
                $order->set('statut', 'ABANDONNER');
                if ($order->update()) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'La commande a été abandonnée',
                        'order' => $number_order,
                        'etat' => "L'état est passé à 'ABANDONNER'"
                    ]);
                } else {
                    throw new Exception('Échec de l\'abandon');
                }
            } else {
                throw new Exception('Order manquant');
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    /**
     * role :livrer une commande
     */
    public function deliveryOrder()
    {
        $this->ensureStatus('EQUIPIER');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['status' => 'error', 'message' => 'Erreur de décodage JSON : ' . json_last_error_msg()]);
            return;
        }
        try {
            if (isset($input['order'])) {
                $order = new Orders();
                $order->loadWithOrderNumber($input['order']);
                $number_order = $input['order'];
                $order->set('statut', 'LIVRER');
                if ($order->update()) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'La commande a été livré',
                        'order' => $number_order,
                        'etat' => "L'état est passé à 'LIVRER'"
                    ]);
                } else {
                    throw new Exception('Échec de la modification de statut livrer');
                }
            } else {
                throw new Exception('Order manquant');
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
