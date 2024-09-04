<?php

namespace App\Controllers;

use Exception;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Detail_order;
use App\Controllers\BaseController;
use App\Models\Orders;

class APIController extends BaseController
{
    protected $products;
    protected $categories;
    protected $detailOrder;
    protected $orders;

    public function __construct()
    {
        $this->products = new Products();
        $this->categories = new Categories();
        $this->categories = new Detail_order();
        $this->categories = new Orders();
    }
    /**
     * role : Creer une api pour les categories
     * @return array
     */
    public function createAPICategories()
    {
        // Autoriser les requêtes provenant de n'importe quel domaine
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        $categories = new Categories();
        $listeCategories = $categories->listEtendue();
        $result = [];
        foreach ($listeCategories as $value) {
            $result[] = [
                "id" => $value->getId(),
                "title" => $value->get('label'),
                "image" => $value->get('picture')
            ];
        }
        return json_encode($result);
    }
    /**
     * role : Créer une API pour les produits
     * @return void
     */
    public function createAPIProducts()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        $categories = ['menus', 'burgers', 'boissons', 'frites', 'encas', 'desserts', 'sauces', 'salades', 'wraps'];
        $result = [];
        foreach ($categories as $category) {
            // Récupérer les produits pour chaque catégorie
            $listeProducts = $this->products->listEtendue(['label' => $category], [], 'categories', 'categories_id', 'label', 'label');
            $products = [];
            foreach ($listeProducts as $value) {
                $products[] = [
                    "id" => $value->getId(),
                    "nom" => $value->get('name'),
                    "prix" => $value->get('price'),
                    "image" => "http://exam-back.mdaszczynski.mywebecom.ovh/public/wacdo".$value->get('pictures')
                ];
            }
            // Ajouter les produits à la catégorie correspondante
            $result[$category] = $products;
        }
        // Encodage en JSON
        return json_encode($result);
    }
    public function addOrderDetailsWacdo()
    {
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
                if (!isset($input['order']) || !isset($input['restauration']) || !isset($input['composition']) || !is_array($input['composition'])) {
                    throw new Exception('Données manquantes ou invalides dans le JSON.');
                }
                $order = [];
                $type = "";
                // Création de la nouvelle commande
                $newOrder = new Orders();
                $newOrder->set('number_order', $input['order']);
                $newOrder->set('restauration', $input['restauration']);
                $newOrder->set('statut', 'A PREPARER');
                $id = $newOrder->insert();
                // Insertion des détails de la commande
                $item = [];
                foreach ($input['composition'] as $item) {
                    if (isset($item)) { 
                        $detailOrder = new Detail_order();
                        // $detailOrder->set('id_order', $id);
                        // $detailOrder->set('id_produit', $item['id_produit']);
                        // $detailOrder->set('id_boisson', $item['id_boisson']);
                        // $detailOrder->set('id_side', $item['id_side']);
                        // $detailOrder->set('quantite', $item['quantite']);
                        // $detailOrder->set('size', $item['size']);
                        $detailOrder->insert();
                    } else {
                        throw new Exception('Détail de la commande invalide, ID manquant.');
                    }
                }
                echo json_encode(['status' => 'success', 'message' => 'Commande ajoutée avec succès', 'order_id' => $id, 'data' => $item]);
            } else {
                throw new Exception('JSON reçu invalide: ' . json_last_error_msg());
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            error_log('Error: ' . $e->getMessage());
        }
    }
}
