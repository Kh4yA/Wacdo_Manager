<?php

// controller Manager qui gere l'interface des manager

namespace App\Controllers;

use App\Models\Detail_order;
use Exception;
use App\Models\Orders;
use App\Controllers\BaseController;

/**
 * /**
 * Class ManagerController
 * 
 * Gère les opérations spécifiques au rôle de manager, telles que l'affichage des interfaces de gestion,
 * la gestion des commandes, et la mise à jour du statut des commandes.
 * 
 * Utilise les modèles Orders et Detail_order pour interagir avec les données de commande.
 */
class ManagerController extends BaseController
{

    protected $orders;
    protected $detailOrders;

    public function __construct()
    {
        $this->orders = new Orders();
        $this->detailOrders = new Detail_order();
    }
    /**
     * Prépare et affiche l'interface de gestion pour le manager.
     * Récupère la liste des commandes avec le statut 'A PREPARER' et les envoie à la vue pour affichage.
     * 
     * @param void
     * @return void
     */
    public function displayInterfaceManager()
    {
        $this->ensureStatus('MANAGER');
        $listOrder = $this->detailOrders->showOrderWithFilter('A PREPARER');
        return $this->render('manager/interface', [
            'listOrder' => $listOrder,
        ]);
    }
    /**
     * Affiche les détails d'une commande spécifique.
     * Récupère la liste des commandes à préparer, les détails de la commande sélectionnée,
     * ainsi que les informations de la commande actuelle, puis les envoie à la vue.
     * 
     * @param string $number_order Le numéro de la commande à afficher.
     * @return void
     */
    public function displayOrderDetail($number_order)
    {
        $this->ensureStatus('MANAGER');
        $listOrder = $this->detailOrders->showOrderWithFilter('A PREPARER');
        $detailOrder = $this->detailOrders->showOrderDetail($number_order);
        $order = new Orders();
        $orderCurrent = $order->loadWithOrderNumber($number_order);
        return $this->render('manager/interface', [
            'listOrder' => $listOrder,
            'detailOrder' => $detailOrder,
            'orderCurrent' => $orderCurrent,
        ]);
    }
/**
 * Change le statut d'une commande à 'PREPARE'.
 * Cette méthode reçoit une requête JSON contenant le numéro de commande, en $_POST
 * charge la commande correspondante et met à jour son statut à 'PREPARE'.
 * Retourne une réponse JSON indiquant le succès ou l'échec de l'opération.
 * 
 * @param void
 * @return void
 */
    public function readyOrder()
    {
        $this->ensureStatus('MANAGER');
        $this->CORSHeaders();
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
                $order->set('statut', 'PREPARE');
                if ($order->update()) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'La commande a été préparé',
                        'order' => $number_order,
                        'etat' => "L'état est passé à 'PREPARE'"
                    ]);
                } else {
                    throw new Exception('Échec de la modification de statut préparé');
                }
            } else {
                throw new Exception('Order manquant');
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
