<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../../public/css/style.css">
    <link rel="icon" href="/public/wacdo/images/logo.png" type="image/png">

    <script src="/public/js/interfaceEquipier.js" defer></script>
    <title>Interface equipier</title>
</head>

<body>
    <?php include "templates/fragments/header.php" ?>
    <main class="flex space-between flex-wrap">
        <!-- Gestion des choix produits ou utilisateurs -->
        <div class="equipier-container flex space-between flex-wrap">
            <!-- choix interface -->
            <div class="choice-interface-equipier flex flex-wrap item-center justify-center">
                <a class="choice" href="/commandes">Toutes le commandes</a>
                <a class="choice" href="/interface_equipier">Passer une commande</a>
            </div>
            <div class="choice-categorie-equipier flex item-center">
                <?php
                /*
                foreach($listeCategories as $category){
                    ?>
                    <button class="choice"><?= $category->get('label') ?></button>
                    <?php
                }
                */
                ?>
                <!-- modal Menu-->
                <dialog class="dialog-menu">
                    <div>
                        <div class="padding20px">
                            <div class="close-modal flex image justify-center" id="closeModalMenu">
                                <p><span class="color-red">&#10060;</span></p>
                            </div>
                        </div>
                        <div class="modal-menu">
                            <div class=" boissons flex item-center">
                                <?php
                                foreach ($listeBoissons as $boisson) {
                                ?>
                                    <div class="card flex justify-center card-boisson" data-id=<?= $boisson->getId() ?>><img src="/public/wacdo<?= $boisson->get('pictures') ?>" alt=""></div>
                                <?php
                                }
                                ?>
                            </div>
                            <div class=" side flex item-center">
                                <?php
                                foreach ($listeSide as $side) {
                                    if ($side->getId() === 36 || $side->getId() === 39 || $side->getId() === 60) {
                                ?>
                                        <div class="card flex justify-center card-side" data-id=<?= $side->getId() ?>><img src="/public/wacdo<?= $side->get('pictures') ?>" alt=""></div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                            <div class="size flex">
                                <div class="choice choice-size" data-size="BEST_OF">
                                    <p>best of</p>
                                </div>
                                <div class="choice choice-size" data-size="MAXI_BEST_OF">
                                    <p>maxi-best of</p>
                                </div>
                            </div>
                            <div class="modal-button"><button class="btn-first" id="valideMenu">Valider menu</button></div>
                        </div>
                    </div>
                </dialog>
            </div>
            <div class="choice-products-equipier flex flex-wrap">
                <?php
                /*
                foreach($listeProduit as $produit){
                    ?>
                    <div class="card-product-equipier flex item-center">
                        <p><?= $produit->get('name') ?></p>
                        <div class="image-product-equipier">
                            <img class=" margin-autoLR" src="/public/wacdo<?= $produit->get('pictures') ?>" alt=" photo d'un <?= $produit->get('label') ?>">
                        </div>
                </div>
                    <?php
                */
                ?>
            </div>
        </div>
        <!-- cart -->
        <div class="cart-container">
            <div class="cart-header">
                
                <div class="logo-cart flex justify-center space-arround"><button class="choice new-order">Creer une nouvel commande</button><img src="/public/wacdo/images/logo.png" alt="logo de wacdo">
                </div>
                <div class="order-number flex space-between padding20px item-center">
                    <div class="order-info flex space-between width100 item-center">
                        <!-- 
                        <div><p>Commande numéro</p></div>
                        <div><p> <span class="font-size42px">72</span></p></div>
                    -->
                    </div>
                </div>
            </div>
            <div class="line"></div>
            <div class="order-content">
                <!-- Menu -->
                <div class="menu padding-bottom20px">
                <p>Aucun produit dans la commande.</p>
                            </div>
            </div>
            <!-- Prix de la commande -->
            <div class="order-price">
                <div class="line"></div>
                <div class="flex space-between item-center padding20px">
                    <div>
                        <p><b>TOTAL (ttc)</b></p>
                    </div>
                    <div class="price">
                        <!-- 
                            <p><span class="font-size42px">0,00€</span></p>
                        -->
                    </div>
                </div>
                <div class="statut d-none">
                <p>PAYÉ</p>
                </div>
                <div class="cart-btn flex space-between flex-wrap">
                    <button class="btn-second clear" id="abandon">Abandon</button>
                    <button class="btn-first" id="pay">Payer</button>
                </div>
            </div>
        </div>
    </main>
</body>

</html>