<?php

namespace App\Controllers;

use App\Utils\Session;

class HomeController extends BaseController
{

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
