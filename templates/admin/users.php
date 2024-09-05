<?php
    //template users
    //role : met en forme la page utilisateurs section admin
    // param : $listUser(tableau indexé par l'id)
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="icon" href="/public/wacdo/images/logo.png" type="image/png">

    <script src="/public/js/app.js" defer></script>

    <title>Admin</title>
</head>

<body>
    <!-- Insertion du fargment  header.php-->
    <?php include "templates/fragments/header.php" ?>
    <main class="flex space-between flex-wrap">
    <dialog id="modalAdd">
                <div class="close-modal flex image justify-center"><p><span class="color-red">&#10060;</span></p></div>
                <form action="/addUser" method="POST" class="flex item-center space-between">
                    <div>
                        <label for="id_connexion">Identifiant :</label>
                        <input type="text" name="id_connexion" id="id_connexion">
                    </div>
                    <div>
                        <label for="password">Mot de passe par defaut :</label>
                        <input type="password" name="password" id="password">
                    </div>
                    <div>
                        <label for="last_name">Nom :</label>
                        <input type="text" name="last_name" id="last_name">
                    </div>
                    <div>
                        <label for="first_name">Prenom :</label>
                        <input type="text" name="first_name" id="first_name">
                    </div>
                    <div>
                        <label for="mail">Email :</label>
                        <input type="text" name="mail" id="mail">
                    </div>
                    <div>
                        <label for="create_at">Creer le :</label>
                        <input type="date" name="create_at" id="create_at">
                    </div>
                    <div>
                        <label for="status">Choix du statut</label>
                        <select name="status" id="status">
                            <option value="EQUIPIER">Equipier</option>
                            <option value="MANAGER">Manager</option>
                            <option value="ADMIN">Admin</option>
                        </select>
                    </div>
                    <div class="flex">
                        <label for="actif">Actif :</label>
                        <div class="flex justify-center">
                            <label for="1">oui</label>
                            <input type="radio" name="actif" id="1" value=1 checked>
                        </div>
                        <div class="flex">
                            <label for="0">non</label>
                            <input type="radio" name="actif" id="0" value=0>
                        </div>
                    </div>
                    <button type="submit">Ajouter l'utilisateur</button>
                </form>
        </dialog>

        <!-- Gestion des choix produits ou utilisateurs -->
        <div class="choice-admin flex justify-center item-center gap20px">
            <a href="/admin_produit" class="choice">Products</a>
            <a class="<?= (explode('/', $_SERVER['REQUEST_URI'])[1] !== 'admin_produit') ? 'active ' : 'choice' ?> choice" href="/admin_utilisateur">Utilisateur</a>
        </div>
        </div>
        <div class="user-container">
        <div class=" margin-bottom10px"><button class="choice add-new">Ajouter un nouvel utilisateur</button></div>

            <table>
                <thead>
                    <th>Identification</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Email</th>
                    <th>Date de creation</th>
                    <th>Statut</th>
                    <th>actif</th>
                    <th>action</th>
                </thead>
                <tbody>
                    <?php
                    foreach ($listUser as $user) {
                    ?>
                        <tr>
                            <td><?= htmlentities($user->get('id_connexion')) ?></td>
                            <td><?= htmlentities($user->get('last_name')) ?></td>
                            <td><?= htmlentities($user->get('first_name')) ?></td>
                            <td><?= htmlentities($user->get('mail')) ?></td>
                            <td><?= htmlentities($user->get('create_at')) ?></td>
                            <td><?= htmlentities($user->get('status')) ?></td>
                            <td><?= htmlentities($user->get('actif')) ?></td>
                            <td class="flex justify-center gap20px"><a href="/admin_utilisateur/<?= htmlspecialchars($user->getId()) ?>">details</a><a href="">supprimer</a></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="form-container-user">
            <!-- Insertion du fargment  form_modif_user.php-->
            <?php require "templates/fragments/form_modif_user.php" ?>
        </div>
    </main>
</body>

</html>