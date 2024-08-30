<?php

// controller Equipier qui gere l'interface des equipier

namespace App\Controllers;

use App\Controllers\BaseController;
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
    protected $orderCurrent;

    function __construct()
    {
        $this->products = new Products();
        $this->categories = new Categories();
        $this->orders = new Orders();
        $this->detail_order = new Detail_order();
    }

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
     * genere un numero de commande unique
     * @return string
     */
    public function generateOrderId()
    {
        // Génère une chaîne de 3 caractères alphanumériques aléatoires
        $randomString = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
        return 'order' . $randomString;
    }
    /**
     * creer un json qui va detailler la commande dans le panier
     */
    public function createOrder()
    {
        $orderId = $this->generateOrderId();
        $this->orderCurrent = $orderId;
        $order = new Orders();
        $order->set('number_order', $orderId);
        $order->set('statut', 'EN COURS');
        $order->insert();
        return json_encode($orderId);
    }
    /**
     * Ajouter les detail de la commande
     *  @param array $data
     */
    public function addOrderDetails()
    {
        // Récupérer le contenu JSON envoyé par la requête POST
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE); // Décoder la chaîne JSON en tableau associatif
        if (isset($input['number_order'])) {
            $number_order = $input['number_order'];
            // Traiter le reste des détails de la commande ici...

            // Par exemple, insérer les détails dans la base de données
            // ...

            // Retourner une réponse JSON
            echo json_encode(['status' => 'success', 'message' => 'Commande ajoutée avec succès']);
        } else {
            // Gérer les cas où les données sont manquantes ou incorrectes
            echo json_encode(['status' => 'error', 'message' => 'Données de commande manquantes']);
        }
        print_r($_POST);
        $detailOrder = new Detail_order();
        print_r($this->orderCurrent);
        die();
        $detailOrder->set('id_order', $this->orderCurrent);
        // $detailOrder->set('product_id', $data['product_id']);
        // $detailOrder->set('quantity', $data['quantity']);
        // $detailOrder->set('price', $data['price']);
        $detailOrder->insert();
    }
}