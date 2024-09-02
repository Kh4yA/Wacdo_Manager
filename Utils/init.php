<?php

// initialisation fichier a inclure au debut de chaque fichier

// gestion des erreurs

use App\Utils\Database;
use App\Utils\AutoLoader;
use App\Utils\Session;

ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// utilisation de la base de donnée
require "Database.php";
try {
    global $bdd;
    $bdd = new Database("projets_exam-back_mdaszczynski");
} catch (Throwable $exception) {
    echo "Erreur dans la database $exception <br>";
}
// Mise en place de l'auto loader
require 'Autoloader.php';
if (class_exists("App\Utils\Autoloader")) {
    AutoLoader::register();
} else {
    echo "Erreur : class autoloader pas trouvée. <br>";
}
if (class_exists('App\Utils\Session')) {
    Session::session_activation();
} else {
    echo "Erreur : La classe session n'a pas été trouvée.<br>";
}
