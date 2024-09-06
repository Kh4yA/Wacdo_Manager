<?php

// role : fragment qui met en forme le formulairede modification d'un utilisateur
// param = $userCurrent

?>

<form action="/modifUser/<?= htmlspecialchars($userCurrent->getId()) ?>" method="POST" class="flex item-center space-between">
    <div>
        <label for="id_connexion">Identifiant :</label>
        <input type="text" name="id_connexion" id="id_connexion" value=<?= htmlentities(isset($userCurrent)) ? htmlentities($userCurrent->get('id_connexion')) : '' ?>>
    </div>
    <div>
        <label for="last_name">Nom :</label>
        <input type="text" name="last_name" id="last_name" value=<?= htmlentities(isset($userCurrent)) ? htmlentities($userCurrent->get('last_name')) : '' ?>>
    </div>
    <div>
        <label for="first_name">Prenom :</label>
        <input type="text" name="first_name" id="first_name" value=<?= htmlentities(isset($userCurrent)) ? htmlentities($userCurrent->get('first_name')) : '' ?>>
    </div>
    <div>
        <label for="mail">Email :</label>
        <input type="email" name="mail" id="mail" value=<?= htmlentities(isset($userCurrent)) ? htmlentities($userCurrent->get('mail')) : '' ?>>
    </div>
    <div>
        <label for="create_at">Date de creation :</label>
        <input type="text" name="create_at" id="create_at" value=<?= htmlentities(isset($userCurrent)) ? htmlentities($userCurrent->get('create_at')) : '' ?> <?= htmlentities(isset($userCurrent)) ? 'disabled="disabled"' : ''?>>
    </div>
    <div>
        <label for="status">Satut :</label>
        <select name="status" id="">
            <option value="ADMIN" <?= htmlentities(isset($userCurrent)) && htmlentities($userCurrent->get('status')) === 'ADMIN' ? 'selected' : '' ?>>Admin</option>
            <option value="MANAGER" <?= htmlentities(isset($userCurrent)) && htmlentities($userCurrent->get('status')) === 'MANAGER' ? 'selected' : '' ?>>Manager</option>
            <option value="EQUIPIER" <?= htmlentities(isset($userCurrent)) && htmlentities($userCurrent->get('status')) === 'EQUIPIER' ? 'selected' : '' ?>>Equipier</option>
        </select>
    </div>
    <div class="flex">
        <label for="actif">actif :</label>
        <div class="flex justify-center">
            <label for="1">oui</label>
            <input type="radio" name="actif" id="1" value="1"
                <?php
                if (isset($userCurrent) && $userCurrent->get('actif') === "1") {
                    echo 'checked';
                }
                ?>>
        </div>
        <div class="flex">
            <label for="0">non</label>
            <input type="radio" name="dispo" id="0" value="0"
                <?php
                if (isset($userCurrent) && $userCurrent->get('dispo') === "0") {
                    echo 'checked';
                }
                ?>>
        </div>
    </div>
    <button type="submit">modifier</button>
</form>