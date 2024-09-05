<?php

namespace App\Controllers;

use Exception;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Detail_order;
use App\Controllers\BaseController;
use App\Models\Orders;
/**
 * class qui gere les API
 */
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
     * Crée un endpoint API pour récupérer les catégories.
     * @return string JSON contenant la liste des catégories.
     */
    public function createAPICategories()
    {
        // Autoriser les requêtes provenant de n'importe quel domaine
        $this->CORSHeaders();
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
     * Crée un endpoint API pour récupérer les produits par catégorie.
     * @return string JSON contenant les produits organisés par catégorie.
     */
    public function createAPIProducts()
    {
        $this->CORSHeaders();
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
        /**
     * Crée un endpoint API pour ajouter les détails d'une commande.
     * @return void Renvoie un JSON indiquant le succès ou l'échec de l'opération.
     */
    public function addOrderDetailsWacdo()
    {
        $this->CORSHeaders();
    
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
                        $detailOrder->set('id_order', $id);
                        $detailOrder->loadFromTab($item);
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
