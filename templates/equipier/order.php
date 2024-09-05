<?php
//template manager 
// param : $listeOrder (tableau de $listOrder indexé par l'id), $DdetailOrder (tableau de $detailOrder indexé par l'id), $orderCurrent objet courant de la commande
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../../public/css/style.css">
    <link rel="icon" href="/public/wacdo/images/logo.png" type="image/png">

    <script src="/public/js/equipierOrder.js" defer ></script>
    <title>Interface manager</title>
</head>

<body>
    <?php include "templates/fragments/header.php" ?>
    <main class="flex space-between flex-wrap">
        <!-- Gestion des choix produits ou utilisateurs -->
        <div class="manager-container">
            <!-- choix interface -->
            <div class="choice-interface-equipier flex flex-wrap item-center justify-center">
                <a class="choice" href="/commandes">Toutes le commandes</a>
                <a class="choice" href="/interface_equipier">Passer une commande</a>
            </div>
            <!-- gestion des commandes -->
            <table>
                <thead>
                    <th>n° commande</th>
                    <th>date</th>
                    <th>details</th>
                    <th>statut</th>
                </thead>
                <tbody>
                    <?php foreach ($listOrder as $order) : ?>
                        <tr>
                            <td><?= htmlentities($order['number_order']) ?></td>
                            <td><?= htmlentities($order['date']) ?></td>
                            <td><a href="/commande/detail/<?= htmlspecialchars($order['number_order']) ?>">detail</a></td>
                            <td><?= htmlentities($order['statut']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- cart -->
        <div class="cart-container">
            <div class="cart-header">

                <div class="logo-cart flex justify-center space-arround"><img src="/public/wacdo/images/logo.png" alt="logo de wacdo">
                </div>
                <div class="order-number flex space-between padding20px item-center">
                    <div class="order-info flex space-between width100 item-center">
                        <div>
                            <p>Commande numéro</p>
                        </div>
                        <div>
                            <p> <span class="font-size42px"><?=isset($orderCurrent) ? htmlentities($orderCurrent->get('number_order')) : ""?></span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="line"></div>
            <div class="order-content">
                <!-- Menu -->
                <div class="menu padding-bottom20px">
                    <?php
                    if(isset($detailOrder)){
                    foreach ($detailOrder as $order) {
                        if (explode(" ", $order['libelle_product'][0]) === 'Menu') {
                    ?>
                            <div class="menu-item flex space-between padding20px">
                                <div class="flex item-center space-between">
                                    <h3><?= htmlentities($order['quantite']) ?><?= htmlentities($order['libelle_product']) ?></h3>
                                </div>
                                <ul>
                                    <li><?= htmlentities($order['libelle_side']) ?></li>
                                    <li><?= htmlentities($order['libelle_boisson']) ?></li>
                                </ul>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="flex item-center space-between">
                                <h3><?= htmlentities($order['quantite']) ?> <?= htmlentities($order['libelle_product']) ?></h3>
                            </div>
                        <?php
                        }
                        ?>

                    <?php
                    }}
                    ?>
                </div>
                <!-- Prix de la commande -->
                <div class="order-price">
                    <div class="line"></div>
                    <div class="flex space-between item-center padding20px">
                        <div>
                            <p><b>TOTAL (ttc)</b></p>
                        </div>
                        <div class="price">
                            <p><span class="font-size42px"><?=isset($orderCurrent) ? htmlentities($orderCurrent->get('price')) : ""?></span></p>
                        </div>
                    </div>
                    <div class="statut d-none">
                        <p>PAYÉ</p>
                    </div>
                    <div class="cart-btn flex space-between flex-wrap">
                        <button class="btn-first" id="livre" data-number="<?=isset($orderCurrent) ? htmlentities($orderCurrent->get('number_order')) : ""?>">livrer</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>