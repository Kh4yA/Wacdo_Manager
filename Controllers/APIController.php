<?php

namespace App\Controllers;

use App\Models\Categories;
use App\Models\Products;

class APIController
{
    protected $products;
    protected $categories;

    public function __construct()
    {
        $this->products = new Products();
        $this->categories = new Categories();
    }
    /**
     * role : Creer une api pour les categories
     * @return array
     */
    public function createAPICategories()
    {
        // Autoriser les requêtes provenant de n'importe quel domaine
        header('Access-Control-Allow-Origin: *');
        // Autoriser les méthodes HTTP spécifiques
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        // Autoriser les en-têtes spécifiques
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        $listeCategories = $this->categories->listEtendue();
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
        // Autoriser les requêtes provenant de n'importe quel domaine
        header('Access-Control-Allow-Origin: *');
        // Autoriser les méthodes HTTP spécifiques
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        // Autoriser les en-têtes spécifiques
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
                    "image" => $value->get('pictures')
                ];
            }
            // Ajouter les produits à la catégorie correspondante
            $result[$category] = $products;
        }
        // Encodage en JSON
        return json_encode($result);
    }
}
