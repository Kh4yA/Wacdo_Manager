<?php

namespace App\Utils;

use App\Models\User;

class Session
 {
    protected $userConnected;

    /**
     * Role : Activer la session
     */
    public static function session_activation()
    {
        session_start();
        if(self::isConnected()){
            global $userConnected;
            $userConnected = new User(self::session_idconnect());
        }
    }
    /**
     * est ce q'un utilisateur est connecter 
     * @return bool true si $_SESSION a la cle "id" n'est pas vide
     */
    public static function isconnected()
    {
        return !empty($_SESSION['id']);
    }
    /**
     * connecter un utilisateur
     * @param int $id
     */
    public static function connected($id)
    {
        $_SESSION["id"] = $id;
    }
    /**
     * vide et detruit la session en cours
     */
    public static function session_deconnected()
    {
        session_unset();
        session_destroy();
    }
    /**
     * @return int ($id de la session) si non null
     */
    public static function session_idconnect()
    {
        if (self::isconnected()) {
            return $_SESSION['id'];
        }
        return false;
    }
    /**
     * charge un nouvel objet 
     * @return object si connecté object charger sinon un objet vide
     */
    public static function session_userconnect()
    {
        if (self::isconnected()) {
            return new user(self::session_idconnect());
        }else{
            return new user();
        }
    }
    /**
     * affiche le statut de l'ulisateur connecte
     */
    public static function getStatus(){
        if (self::isconnected()) {
            self::session_userconnect()->get('status');
        }else{
            return false;
        }
    }
 }
