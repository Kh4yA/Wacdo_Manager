<?php

// role : frangment header 

use App\Utils\Session;

?>

<header class="flex item-center space-between">
    <div class="box-logo-title flex item-center space-between">
        <div class="flex item-center">
            <img src="./../../public/images/logo.png" alt="Logo du wacdo">
            <p>acDo Manager</p>
        </div>
    </div>
    <div class="flex item-center gap20px">
        <?php
        if (Session::isconnected()) {
        ?>
        <div><p>Utilisateur connecté : <?= Session::session_userconnect()->get("id_connexion") ?></p></div>
             <a href="/deconnexion" class="deconnexion">Deconnexion</a>
        <?php
        }
        ?>
    </div>
</header>