<?php

namespace App\Controllers;

use App\Exceptions\ErrorConnexion;
use App\Exceptions\RouteNotFound;
use App\Models\User;
use App\Source\RedirectToRoute;
use App\Utils\Session;

/**
 * Classe qui gère le contrôle d'accès aux fonctionnalitées de l'application
 */
class AccessController extends BaseController
{
    protected $user;


    /**
     * Constructeur de la classe
     * Initialise les instances de User et RouteNotFound
     */
    function __construct()
    {
        $this->user = new User();
    }
    /**
     * Vérifie la connexion de l'utilisateur et gère les redirections en fonction de son statut
     * @return mixed La réponse de la redirection ou le rendu de la vue de connexion
     * @throws ErrorConnexion Si les informations de connexion sont invalides
     */
    function verify_connexion()
    {
        // on verifie si un utilisateur n'est pas deja connecter
        if (!Session::isconnected()) {
            // on verifie que les données enregistrer dans le formulaire soit juste
            if ($this->user->verif_connexion($_POST['id_connexion'], $_POST['password'])) {
                Session::connected($this->user->getId());
                if ($this->user->get('status') === 'ADMIN') {
                    return $this->redirectToRoute('admin_produit');
                } elseif ($this->user->get('status') === 'MANAGER') {
                    return $this->redirectToRoute('interface_manager');
                } elseif ($this->user->get('status') === 'EQUIPIER') {
                    return $this->redirectToRoute('interface_equipier');
                }
            }
            throw new ErrorConnexion();
            return $this->render('connexion/connexion');
        }
        return $this->redirectToRoute("/");
    }
    /**
     * Déconnecte l'utilisateur en détruisant la session
     * @return mixed La réponse de la redirection
     */
    function deconnexion()
    {
        // Détruit la session de l'utilisateur
        Session::session_deconnected();
        return $this->redirectToRoute("");
        exit;
    }
}
