<?php
// appel du fichier init
if(file_exists( "Utils/init.php")){
    require_once "Utils/init.php";
}else{
    echo 'init non trouvé';
}

use App\Router\Router;
use App\Exceptions\RouteNotFound;
use App\Controllers\HomeController;
use App\Controllers\TestController;
use App\Controllers\AdminController;
use App\Controllers\AccessController;
use App\Controllers\APIController;
use App\Controllers\ManagerController;
use App\Controllers\EquipierController;
use App\Exceptions\ErrorConnexion;
use App\Exceptions\ForbiddenPage;

//definir une constante pour le router
define('VIEW_PATH',__DIR__.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR);
define('PICTURE_PATH',__DIR__.'/public/wacdo/');

$router = new Router();

//==============================================
// ROUTES
//==============================================
// Route pour la page d'accueil
$router->register('/', [HomeController::class, 'index']);
// Route pour la page de connexion 
$router->register('/connexion', [AccessController::class, 'verify_connexion']);
$router->register('/deconnexion', [AccessController::class, 'deconnexion']);
// Route pour la page de gestion des utilisateurs et des produis pour l'admin
$router->register('/admin_produit', [AdminController::class, 'adminProductPage']);
$router->register('/admin_produit/{category}', [AdminController::class, 'displayProductByCategory']);
$router->register('/admin_produit/{category}/{id}', [AdminController::class, 'displayProductModifForm']);
$router->register('/modifProduct/{id}', [AdminController::class, 'saveModifProduct']);
$router->register('/addProduct', [AdminController::class, 'addNewProduct']);
$router->register('/admin_utilisateur', [AdminController::class, 'adminUserPage']);
$router->register('/admin_utilisateur/{id}', [AdminController::class, 'displayUserModifForm']);
$router->register('/modifUser/{id}', [AdminController::class, 'saveModifUser']);
$router->register('/addUser', [AdminController::class, 'addNewUser']);
// routes l'interface du manager
$router->register('/interface_manager', [ManagerController::class, 'displayInterfaceManager']);
$router->register('/interface_manager/detail/{number_order}', [ManagerController::class, 'displayOrderDetail']);
$router->register('/readyOrder', [ManagerController::class, 'readyOrder']);
// route pour l'inteface de l'equipier
$router->register('/interface_equipier', [EquipierController::class, 'displayInterfaceEquipier']);
$router->register('/commandes', [EquipierController::class, 'displayOrderInterface']);
$router->register('/commande/detail/{number_order}', [EquipierController::class, 'displayDetailOrder']);
$router->register('/cartOrder', [EquipierController::class, 'createOrder']);
$router->register('/addOrderDetails', [EquipierController::class, 'addOrderDetails']);
$router->register('/getJsonOrderDetails', [EquipierController::class, 'getJsonOrderDetails']);
$router->register('/deleteOrderDetail', [EquipierController::class, 'deleteOrderDetail']);
$router->register('/validateOrder', [EquipierController::class, 'validateOrder']);
$router->register('/abandonOrder', [EquipierController::class, 'abandonOrder']);
$router->register('/deliveryOrder', [EquipierController::class, 'deliveryOrder']);
// route pour les API
$router->register('/API_wacdo_categories', [APIController::class, 'createAPICategories']);
$router->register('/API_wacdo_produits', [APIController::class, 'createAPIProducts']);
$router->register('/API_wacdo', [APIController::class, 'addOrderDetailsWacdo']);
// route du controlleur test
$router->register('/test', [TestController::class, 'testControl']);
// try catch pour capturer les erreurs
try{
    echo $router->resolve(($_SERVER['REQUEST_URI']));
}catch(RouteNotFound $e){
    $error = $e->getMessage();
    $code = $e->getCode();
    include ('templates/error.php');
}catch(ForbiddenPage $e){
    $error = $e->getMessage();
    $code = $e->getCode();
    include ('templates/error.php');
}catch(ErrorConnexion $e){
    $error = $e->getMessage();
    include ('templates/connexion/connexion.php');
}
