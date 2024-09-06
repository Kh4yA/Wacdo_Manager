<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Products;
use App\Models\Categories;
/**
 * Gere l'utilasation coté admin avec les listes des produits et des utilisateurs
 */
class AdminController extends BaseController
{
    private $products;
    private $categories;
    private $users;

    function __construct()
    {
        $this->products = new Products();
        $this->categories = new Categories();
        $this->users = new User();
    }
    /**
     * role : prepare la page produit 
     */
    public function adminProductPage()
    {
        // On verfie e, entré de controlleur que l'utilisateur est connecter et qu'il a le bon statut
        $this->ensureStatus('ADMIN');
        $listeCategories = $this->categories->listEtendue();
        $listeProducts = $this->products->listEtendue(['label' => 'menus'], [], 'categories', 'categories_id', 'label', 'label');
        return $this->render('admin/products', [
            "listeCategories" => $listeCategories,
            "listeProducts" => $listeProducts
        ]);
    }
    /**
     * role : Afficher les produits selectionner par categories
     * @param string $category (category trier par le label)
     * @return string 
     */
    public function displayProductByCategory($category)
    {
        $this->ensureStatus('ADMIN');
        // echo "Captured Category: " . htmlspecialchars($category);
        $listeCategories = $this->categories->listEtendue();
        $listeProducts = $this->products->listEtendue(['label' => $category], [], 'categories', 'categories_id', 'label', 'label');
        return $this->render('admin/products', [
            "listeCategories" => $listeCategories,
            "listeProducts" => $listeProducts,
        ]);
    }
    /**
     * role : Afficher les produits selectionner par categories
     * @param string $category (category trier par le label)
     * @param int $id (id du produit selectionner)
     * @return string
     */
    public function displayProductModifForm($category, $id)
    {
        $this->ensureStatus('ADMIN');

        $listeCategories = $this->categories->listEtendue();
        $listeProducts = $this->products->listEtendue(['label' => $category], [], 'categories', 'categories_id', 'label', 'label');
        $productCurrent = new Products($id);
        $productCurrent->set('category', $category);
        return $this->render('admin/products', [
            "productCurrent" => $productCurrent,
            "listeCategories" => $listeCategories,
            "listeProducts" => $listeProducts
        ]);
    }
    /**
     * role : modifie le produit selectionner
     * @param int $id
     * @return string 
     */
    public function saveModifProduct($id)
    {
        $this->ensureStatus('ADMIN');
        $productCurrent = new Products($id);
        $category = $_POST['category'];
        $productCurrent->loadFromTab($_POST);
        //Traitement de la photo
        // On verifie si le fichier n'est pas vide
        // on reccuperer le chemin relatif ou on enregistre les images
        $chemin = PICTURE_PATH;
        $file = $_FILES["pictures"];
        $code = $file['error'];
        if ($code == UPLOAD_ERR_INI_SIZE or $code == UPLOAD_ERR_FORM_SIZE) {
            // Erreur : fichier trop gros
            // traitement (message / template)
            echo 'fichier trop grand';
            exit;
        }
        if (!empty($file)) {
            // On supprime l'ancienne photo du répertoire si elle existe
            if (file_exists(PICTURE_PATH . $productCurrent->get('pictures'))) {
                unlink(PICTURE_PATH . $productCurrent->get('pictures'));
            }
            $img = $file['name'];
            // Récupérer l'extension du fichier
            $extension = pathinfo($img, PATHINFO_EXTENSION);
            // Générer un nom de fichier unique
            $fichier = md5(uniqid()) . '.' . $extension;
            if (move_uploaded_file($file["tmp_name"], $chemin . $fichier)) {
                // Enregistrer le nom du fichier dans l'objet produit
                $productCurrent->set('pictures','/' . $fichier);
            }
        } else {
            echo "Aucune photo n'a été téléchargée.";
        }
        $productCurrent->update();
        return $this->redirectToRoute("admin_produit/$category/$id");
    }
    /**
     * Role : ajouter un nouveau produit en base de donnée
     */
    public function addNewProduct()
    {
        $this->ensureStatus('ADMIN');
        $this->products->loadFromTab($_POST);
                //Traitement de la photo
        // On verifie si le fichier n'est pas vide
        // on reccuperer le chemin relatif ou on enregistre les images
        $chemin = PICTURE_PATH;
        $file = $_FILES["pictures"];
        $code = $file['error'];
        if ($code == UPLOAD_ERR_INI_SIZE or $code == UPLOAD_ERR_FORM_SIZE) {
            // Erreur : fichier trop gros
            // traitement (message / template)
            echo 'fichier trop grand';
            exit;
        } else if ($code != UPLOAD_ERR_OK) {
            // Erreur : autre ereur technique
            // traitement (message / template)
            echo 'erreur technique' . $code;
            exit;
        }
        if (!empty($file)) {
            $img = $file['name'];
            // Récupérer l'extension du fichier
            $extension = pathinfo($img, PATHINFO_EXTENSION);
            // Générer un nom de fichier unique
            $fichier = md5(uniqid()) . '.' . $extension;
            if (move_uploaded_file($file["tmp_name"], $chemin . $fichier)) {
                // Enregistrer le nom du fichier dans l'objet produit
                $this->products->set('pictures','/' . $fichier);
            }
        } else {
            echo "Aucune photo n'a été téléchargée.";
        }
        $this->products->insert();
        return $this->redirectToRoute("admin_produit");
    }
    /**
     * Role afficher le page utilisateur
     * @return object
     */
    public function adminUserPage()
    {
        // On verfie e, entré de controlleur que l'utilisateur est connecter et qu'il a le bon statut
        $this->ensureStatus('ADMIN');
        $listUser = $this->users->listEtendue();
        return $this->render('admin/users', [
            "listUser" => $listUser,
        ]);
    }
    /**
     * role : Charge le formulaire avec les données de l'utilisateurs
     * @param int $id (id du champs a charger)
     */
    public function displayUserModifForm($id)
    {
        $this->ensureStatus('ADMIN');
        $listUser = $this->users->listEtendue();
        $userCurrent = new User($id);
        return $this->render('admin/users', [
            "listUser" => $listUser,
            "userCurrent" => $userCurrent
        ]);
    }
    /**
     * role : modifier le profil de l'utilisateur
     * @param int $id (id de l'utilisateur selectionner)
     */
    public function saveModifUser($id){
        $this->ensureStatus('ADMIN');
        $user = new User($id);
        $user->loadFromTab($_POST);
        $user->set('password', password_hash($_POST['password'], PASSWORD_DEFAULT));
        $user->update();
        return $this->redirectToRoute("admin_utilisateur");
    }
    /**
     * Role : ajouter un nouvel utilisateur en base de donnée
     */
    public function addNewUser()
    {
        $this->ensureStatus('ADMIN');
        $this->users->loadFromTab($_POST);
        $this->users->set('password', password_hash($_POST['password'], PASSWORD_DEFAULT));
        $this->users->insert();
        return $this->redirectToRoute("admin_utilisateur");
    }
}
