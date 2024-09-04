<?php

namespace App\Controllers;

use App\Utils\Session;

class HomeController extends BaseController
{

    /**
     * Affiche la page d'accueil ou redirige l'utilisateur connecté vers l'interface appropriée
     * en fonction de son rôle (ADMIN, MANAGER, EQUIPIER).
     *
     * Si l'utilisateur est connecté, il est redirigé vers l'interface correspondante à son statut :
     * ADMIN : redirection vers l'interface d'administration des produits.
     * MANAGER : redirection vers l'interface de gestion du manager.
     * EQUIPIER : redirection vers l'interface de l'équipier.
     *
     * Si l'utilisateur n'est pas connecté, la page de connexion est affichée.
     *
     * @return mixed une redirection vers une autre route en fonction du statut utilisateur.
     */
    public function index()
    {
        if (Session::isconnected()) {
            if (Session::session_userconnect()->get('status') === 'ADMIN') {
                return $this->redirectToRoute('admin_produit');
            } else if (Session::session_userconnect()->get('status') === 'MANAGER') {
                return $this->redirectToRoute('interface_manager');
            } else if (Session::session_userconnect()->get('status') === 'EQUIPIER') {
                return $this->redirectToRoute('interface_equipier');
            }
        }
        return $this->render('connexion/connexion');
    }
}
