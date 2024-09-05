<?php

namespace App\Models;

use App\Utils\_Model;
/**
 * class qui gere la table utilisateur en bdd
 */
class User extends _Model
{
    protected $table = "user";
    protected $fields = ['id_connexion', 'password', 'last_name', 'first_name', 'mail', 'create_at', 'status', 'actif'];

    const ADMIN = 'ADMIN';
    const MANAGER = 'MANAGER';
    const EQUIPIER = 'EQUIPIER';

    /**
     * Verifie si une adresse email et deja enregisrter
     * @param string $email 
     * @param bool (true si existe false si non)
     */
    function emailExists($email)
    {
        $sql = "SELECT `id`,`mail` FROM `$this->table` WHERE `mail` = :mail";
        $param = [":mail" => $email];
        global $bdd;
        $user = $bdd->fetch($sql, $param);
        if ($user) {
            $this->id = $user["id"];
            return true;
        } else {
            return false;
        }
    }
    /**
     * Verifie que le mot de passe corespond bien
     * @param string $login (l'identifiant du compte a verfié)
     * @param string $password ( Le mot de passe a verifié )
     * @param bool (true si existe false si non)
     */
    function verif_connexion($login, $password): bool
    {
        $sql = "SELECT `id`, " . $this->listField() . " FROM `$this->table` WHERE `id_connexion` = :idConnexion";
        $param = [':idConnexion' => $login];
        global $bdd;
        $data = $bdd->fetch($sql, $param);
        if (empty($data)) {
            return false;
        }
        if (password_verify($password, $data["password"])) {
            $this->id = $data["id"];
            $this->loadFromTab($data);
            return true;
        } else {
            return false;
        }
    }
}
