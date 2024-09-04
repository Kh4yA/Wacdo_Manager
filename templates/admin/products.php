<?php

// template Admin/products
// role : met en forme la page admin avec les produit / fragment formulaire
// param $listeCategories( tableau indexé par un id), $listeProduit(tableau d'objet indexé par l'id)
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="icon" href="/public/wacdo/images/logo.png" type="image/png">

    <script src="/public/js/app.js" defer></script>

    <title>Admin</title>
</head>

<body>
    <!-- include le template header -->
    <?php include "templates/fragments/header.php" ?>
    <main class="flex space-between flex-wrap">
        <!-- Gestion des choix produits ou utilisateurs -->
        <div class="choice-admin flex justify-center item-center gap20px">
            <a class="<?= (explode('/', $_SERVER['REQUEST_URI'])[1] === 'admin_produit') ? 'active ' : 'choice' ?> choice" href="/admin_produit">Products</a>
            <a class="choice" href="/admin_utilisateur">Utilisateur</a>
        </div>
        <!-- Gestion des boutons categories -->
        <div class="choice-category flex justify-center item-center gap20px">
            <?php
            foreach ($listeCategories as $category) {
                $uri = isset($_SERVER['REQUEST_URI']) ? explode('/', $_SERVER['REQUEST_URI']) : [];
                $categoryCurrent = isset($uri[2]) ? $uri[2] : "";
                            ?>
                <a class="<?= $categoryCurrent !== $category->get('label') ? ' choice' : 'active choice' ?> " href="/admin_produit/<?= htmlspecialchars($category->get('label')) ?>">
                    <?= htmlspecialchars($category->get('label')) ?>
                </a>
            <?php
            }
            ?>
        </div>
        <!-- gestion de la modal -->
        <dialog id="modalAdd">
                <div class="close-modal flex image justify-center"><p><span class="color-red">&#10060;</span></p></div>
                <form action="/addProduct" method="POST" class="flex item-center space-between">
                    <div>
                        <label for="name">Nom :</label>
                        <input type="text" name="name" id="name">
                    </div>
                    <div>
                        <label for="price">Prix en(€) :</label>
                        <input type="text" name="price" id="price">
                    </div>
                    <div class="flex">
                        <label for="pictures">Image :</label>
                        <div class="box-picture flex">
                            <input type="file" name="pictures" id="pictures" accept="image/*">
                        </div>
                    </div>
                    <div>
                        <label for="description">Description :</label>
                        <textarea name="description" id="description" cols="30" rows="5" placeholder="ajouter une description optionnel"></textarea>
                    </div>
                    <div>
                        <label for="categories_id">Choix de la categories</label>
                        <select name="categories_id" id="categories_id">
                            <?php
                            foreach($listeCategories as $category){
                                ?>
                                <option value="<?= $category->getId()?>"><?= $category->get('label') ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="flex">
                        <label for="dispo">Dispo :</label>
                        <div class="flex justify-center">
                            <label for="1">oui</label>
                            <input type="radio" name="dispo" id="1" value="1">
                        </div>
                        <div class="flex">
                            <label for="0">non</label>
                            <input type="radio" name="dispo" id="0" value="0">
                        </div>
                    </div>
                    <button type="submit">Ajouter le produit</button>
                </form>


        </dialog>
        <!-- table avec les produits -->
        <div class="product-container">
            <div class=" margin-bottom10px"><button class="choice add-new">Ajouter un nouveau produit</button></div>
            <table>
                <thead>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>description</th>
                    <th>dispo</th>
                    <th>action</th>
                </thead>
                <tbody>
                    <?php
                    foreach ($listeProducts as $produit) {
                    ?>
                        <tr>
                            <td><?= $produit->get('name') ?></td>
                            <td><?= $produit->get('price') ?></td>
                            <td><?= $produit->get('description') ?></td>
                            <td><?= $produit->get('dispo') ?></td>
                            <td class="flex justify-center gap20px"><a href="/admin_produit/<?= htmlspecialchars($produit->get('label')) ?>/<?= htmlspecialchars($produit->getId()) ?>">details</a><a href="">supprimer</a></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="form-container">
            <form action="/modifProduct/<?= isset($productCurrent) ? $productCurrent->getId() : '' ?>" method="POST" class="flex item-center space-between" enctype="multipart/form-data">
                <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                <input type="hidden" name="category" value=<?= isset($productCurrent) ? $productCurrent->get('category') : '' ?>>
                <div>
                    <label for="name">Nom :</label>
                    <input type="text" name="name" id="name" value="<?= isset($productCurrent) ? htmlentities($productCurrent->get('name')) : '' ?>">
                </div>
                <div>
                    <label for="price">Prix en(€) :</label>
                    <input type="text" name="price" id="price" value="<?= isset($productCurrent) ? htmlentities($productCurrent->get('price')) : '' ?>">
                </div>
                <div class="flex">
                    <label for="pictures">Image :</label>
                    <div class="box-picture flex">
                        <input type="file" name="pictures" id="pictures" accept="image/*">
                        <div class="image">
                            <img src="./../../public/wacdo/<?= htmlentities($productCurrent->get('pictures')) ?>" alt="">
                        </div>
                    </div>

                </div>
                <div>
                    <label for="">Description :</label>
                    <textarea name="" id="" cols="30" rows="5"></textarea value="<?= isset($productCurrent) ? htmlentities($productCurrent->get('description')) : '' ?>">
                </div>
                <div class="flex">
                <label for="dispo">Dispo :</label>
                <div class="flex justify-center">
                    <label for="1">oui</label>
                    <input type="radio" name="dispo" id="1" value="1" 
                        <?php
                        if (isset($productCurrent) && $productCurrent->get('dispo') === "1") {
                            echo 'checked';
                        }
                        ?>
                    >
                </div>
                <div class="flex">
                    <label for="0">non</label>
                    <input type="radio" name="dispo" id="0" value="0" 
                        <?php
                        if (isset($productCurrent) && $productCurrent->get('dispo') === "0") {
                            echo 'checked';
                        }
                        ?>
                    >
                </div>
            </div>
                <button type="submit">modifier</button>
            </form>
        </div>
    </main>
</body>

</html>