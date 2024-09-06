// =========================================================
// SOMMAIRE
// =========================================================
// 1. Configuration des URL
// 2. Configuration des variables
// 3.1 Gestion des données produits
// 3.2 Gestion des données categories
// 4. Gestion AJAX
//      Function *4.1* sendDataServer() Envoi les details de la commande au sereur
//      Function *4.2* getDetailCommande() reccupere les detail de la commande
//      Function *4.3* deleteArticleServer() supprime un article en bdd
//      Function *4.4* abandonOrder() abandonne la commande
//      Function *4.5* sendValidationOrder() envoi la validation de la commande
//      Function *4.6* createOrder() creer une nouvelle commande en bdd
// 5. Functions
//      Function *5.1* isOrderLanched() verifie si une commande est lancé
//      Function *5.2* cardSelected() ajoute la classe actice a la catre qui est séléctionné
//      Function *5.3* constructTemplateCategories() construit le template aves les cartes categories
//      Function *5.4* constructTemplateItem() construit le templates avec les cartes produits
//      Function *5.5* constructOrderInfo() construit la parti avec les infos de la commandes
//      Function *5.6* constructContentOrder() construit le contenu de la commande
// 6. Ecouteur d'evenement

// =========================================================
// 1. Configuration des URL
// =========================================================
const API_BASE_URL = 'http://exam-back.mdaszczynski.mywebecom.ovh';
const API_CATEGORIES_URL = `${API_BASE_URL}/API_wacdo_categories`;
const API_PRODUCTS_URL = `${API_BASE_URL}/API_wacdo_produits`;
const API_ORDER_DETAILS_URL = `${API_BASE_URL}/getJsonOrderDetails`;
const API_ADD_ORDER_DETAILS_URL = `${API_BASE_URL}/addOrderDetails`;
const API_CREATE_ORDER_URL = `${API_BASE_URL}/cartOrder`;
const API_DELETE_ARTICLE_URL = `${API_BASE_URL}/deleteOrderDetail`;
const API_VALIDATE_ORDER_URL = `${API_BASE_URL}/validateOrder`;
const API_ABANDON_ORDER_URL = `${API_BASE_URL}/abandonOrder`;

// ==========================================================
// 2. declaration des variables
// ==========================================================
const choiceCategorieEquipier = document.querySelector('.choice-categorie-equipier')
let order_num = ""; // creer un numero de commande (chainde de caractere vide par defaut)
let isOrderEventCreated = false;  // Variable pour vérifier si l'événement est déjà attaché
//déclaration de la variable price pour reccuperer le prix
let price = 0;
let statutOrder= ""
// ==========================================================
// 3.1 Gestions des données produits
// ==========================================================
/**
 * extraire la donnée, appel constructTamplateItem()
 * @param {string} category 
*/
async function extractDatasProduits(category) {
    try {
        const response = await fetch(API_PRODUCTS_URL);
        const datas = await response.json();
        console.log(datas);
        await construcTemplateItem(datas[category], category);
    } catch (error) {
        console.error('Erreur lors de la récupération des données des produits :', error);
    }
}
// ==========================================================
// 3.2 Gestion des données categories
// ==========================================================
//Appel de la methode fetch pour traiter les données
fetch(API_CATEGORIES_URL)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.json();
    }).then(datas => {
        console.log(datas);
        construcTemplateCategory(datas)
    })
    .catch((error) => {
        console.error('Erreur lors de la récupération des données des produits :', error);
    })

// ==========================================================
// 4. Gestions des appelles ajax
// ==========================================================
/**
 * FUNCTION *4.1*
 * Envoi les details de la commande au sereur
 * @param {} data 
 */
async function sendDataServer(data) {
    try {
        const response = await fetch(API_ADD_ORDER_DETAILS_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        const responseData = await response.json();
        console.log('Réponse du serveur:', responseData);
        await getDetailCommande();  // Rafraîchir les détails de la commande après envoi des données
    } catch (error) {
        console.error('Erreur lors de l\'envoi des détails de la commande :', error);
    }
}
/**
 * FUNCTION *4.2*
 * fetch qui recupere le detail de la commande
 */
async function getDetailCommande() {
    try {
        const response = await fetch(API_ORDER_DETAILS_URL)
        const datas = await response.json();
        await constructContentOrder(datas)
        console.log(datas);
    } catch (error) {
        console.error('Erreur lors de la récupération des données des produits :', error);
    }
}
/**
 * FUNCTION *4.3*
 * supprimer un article du server
 * @param int id
 */
async function deleteArticleServer(id) {
    try {
        const response = await fetch(API_DELETE_ARTICLE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id })
        });
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        const responseData = await response.json();
        console.log('Réponse du serveur:', responseData);
        await getDetailCommande();
    } catch (error) {
        console.error('Erreur lors de la suppression de l\'article :', error);
    }
}
/**
 * FUNCTION *4.4*
 * role : abandonner une commande
 *  @param int id de la commande a annulé
 */
async function abandonOrder(order_num) {
    console.log('Corps de la requête envoyé :', JSON.stringify({ order: order_num }));
    try {
        const response = await fetch(API_ABANDON_ORDER_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ order: order_num })
        });
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        const responseData = await response.json();
        console.log('Réponse du serveur:', responseData);
    } catch (error) {
        console.error('Erreur lors de l abandon de la commande :', error);
    }
}
/**
 * FUNCTION *4.5*
 * role : envoyer la validation de la commande au serveur
 * @param int price 
 */
async function sendValidationOrder(price) {
    console.log('Corps de la requête envoyé :', JSON.stringify({ price: price }));
    try {
        const response = await fetch(API_VALIDATE_ORDER_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ price: price })
        });
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        const responseData = await response.json();
        console.log('Réponse du serveur:', responseData);
        await getDetailCommande();
    } catch (error) {
        console.error('Erreur lors de la validation de la commande :', error);
    }
}
/**
 * FUNCTION *4.6*
 * creer un nouvveau numero de commande 
 */
function createOrder() {
    const btnNewOrder = document.querySelector('.new-order');
    if (!btnNewOrder) {
        console.error("Le bouton '.new-order' est introuvable.");
        return;
    }
    if (!isOrderEventCreated) {
        btnNewOrder.addEventListener('click', (e) => {
            e.preventDefault();
            fetch(API_CREATE_ORDER_URL)
                .then(rep => rep.json())
                .then(data => {
                    constructOrderInfo(data);
                    constructContentOrder();
                    sessionStorage.setItem('id_order', data)
                    order_num = data;
                    sendDataServer({ id_order: order_num })
                })
        });
        isOrderEventCreated = true;
    }
}
createOrder();

// ==========================================================
// 5. FUNCTIONS
// ==========================================================
/**
 * FUNCTION *5.1*
 * verifie qu'une commande est lancé sinon empeche d'autre action
 */
function isOrderLaunched() {
    if (order_num === "") {
        alert('Vous devez créer une nouvelle commande !')
        location.reload()
    }
}
/**
 * FUNCTION *5.2*
 * Ajoute l'etat active a une carte
 * @param {string} selectorClass (selectaur de class au format ('.selectorClass'))
 */
function cardSelected(selectorClass) {
    let cards = document.querySelectorAll(selectorClass);
    cards.forEach(card => {
        card.addEventListener('click', () => {
            cards.forEach(otherCard => otherCard.classList.remove('active'));
            card.classList.add('active');
        });
    });
}
/**
 * FUNCTION *5.3*
 * role : genere les boutons categories
 * @param {*} $data 
 */
function construcTemplateCategory(data) {
    const choiceCategorieEquipier = document.querySelector('.choice-categorie-equipier')
    data.forEach(elt => {
        choiceCategorieEquipier.innerHTML +=
            `<button class="choice btn-category" data-id=${elt.id} data-category=${elt.title} >${elt.title}</button>`
    });
    cardSelected('.btn-category')
    const btnCategory = document.querySelectorAll('.btn-category')
    btnCategory.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault()
            const dataCategory = btn.getAttribute('data-category')
            extractDatasProduits(dataCategory)
            isOrderLaunched()
        })
    })
}
/**
 *  FUNCTION *5.4*
 * Role : construire le template qui contient les cartes produits
 * @param {array} datas 
 * @param {string} category 
 */
async function construcTemplateItem(datas, category) {

    const containerCardItem = document.querySelector('.choice-products-equipier')
    const modelMenu = document.querySelector('.dialog-menu')
    const boissonlMenu = document.querySelectorAll('.card-boisson')
    const sidelMenu = document.querySelectorAll('.card-side')
    const sizelMenu = document.querySelectorAll('.choice-size')
    const valideMenu = document.querySelector('#valideMenu')
    let menu = new Object;
    menu.quantite = 1
    menu.id_order = order_num
    containerCardItem.innerHTML = ''
    datas.forEach(elt => {
        const card = document.createElement('div');
        card.classList = 'card-product-equipier';
        card.setAttribute('data-id', elt.id);
        card.setAttribute('data-category', category);
        card.innerHTML = `
        <p>${elt.nom}</p>
        <div class="image-product-equipier">
        <img src="${elt.image}" alt=" photo d'un ${elt.nom}">
        </div>
        `;
        containerCardItem.appendChild(card);
        card.addEventListener('click', (e) => {
            e.preventDefault()
            const id = card.getAttribute('data-id');
            const category = card.getAttribute('data-category');
            console.log('click');
            menu.id_produit = id
            category === 'menus' && modelMenu.showModal();
            if (category !== 'menus') {
                sendDataServer({ id_order: order_num, id_produit: id, quantite: 1 })
                getDetailCommande()
                isOrderLaunched()

            }


        });
    })
    cardSelected('.card-boisson');
    cardSelected('.card-side');
    cardSelected('.choice-size');
    cardSelected('.card-product-equipier');

    const btnCloseModal = document.getElementById('closeModalMenu');
    btnCloseModal.addEventListener('click', () => {
        console.log('click');
        modelMenu.close()
    });
    boissonlMenu.forEach(boisson => {
        boisson.addEventListener('click', () => {
            console.log(boisson.getAttribute('data-id'));
            menu.id_boisson = boisson.getAttribute('data-id')
            isOrderLaunched()

        })
    })
    sidelMenu.forEach(side => {
        side.addEventListener('click', () => {
            console.log(side.getAttribute('data-id'));
            menu.id_side = side.getAttribute('data-id')
            isOrderLaunched()

        })
    })
    sizelMenu.forEach(size => {
        size.addEventListener('click', () => {
            console.log(size.getAttribute('data-size'));
            menu.size = size.getAttribute('data-size')
            console.log(menu);
            isOrderLaunched()

        })
    })
    valideMenu.addEventListener('click', () => {
        console.log('click');
        console.log(menu);
        sendDataServer(menu)
        getDetailCommande()
        isOrderLaunched()
        modelMenu.close()
    })
}
/**
 * FUNCTION *5.5*
 * affiche le numero de commande
 * @param {string} data (numero de commande)
 */
function constructOrderInfo(data) {
    const orderInfo = document.querySelector('.order-info');
    if (!orderInfo) {
        console.error("L'élément avec la classe 'order-info' est introuvable.");
        return;
    }
    orderInfo.innerHTML = `
        <div><p>Commande numéro</p></div>
        <div><p><span class="font-size42px">${data}</span></p></div>    `;
}
/**
 * FUNCTION *5.6*
 * Remplit les informations du panier
 * @returns {void}
 */
function constructContentOrder(datas = []) {
    price = 0;
    const orderContent = document.querySelector('.order-content');
    if (!orderContent) {
        console.error("L'élément avec la classe 'order-content' est introuvable.");
        return;
    }
    // Vider le contenu précédent avant de construire le nouveau
    orderContent.innerHTML = '';
    if (datas.length > 0) {
        console.log('data n\'est pas vide');
        datas.forEach(data => {
            console.log(data);
            // S'assurer que libelle_product est défini et non nul
            if (data.libelle_product) {
                let str = data.libelle_product;
                const str2 = str.split(' ');
                // On gère le prix comme un nombre
                let productPrice = parseFloat(data.price);
                // on gerre le statut
                statutOrder = data.statut;
                
                let orderItem = document.createElement('div');
                if (str2[0] === 'Menu') {
                    price += productPrice;
                    if (data.size === 'MAXI_BEST_OF') {
                        price += 0.50;
                    }
                    // Construction dynamique d'un élément de commande
                    orderItem.classList.add('menu', 'padding-bottom20px');
                    let itemHTML = `
                    <div class="flex item-center space-between">
                        <h3>${data.quantite} ${data.libelle_product}</h3>
                        <img class='delete' src="./public/wacdo/images/trash.png" alt="logo d'une poubelle pour la suppression" data-id="${data.id}" data-price="${productPrice}">
                    </div>
                    <ul>
                        <li>${data.libelle_side || ''}</li>
                        <li>${data.libelle_boisson || ''}</li>
                    </ul>
                `;
                    orderItem.innerHTML += itemHTML;
                } else {
                    price += productPrice;
                    orderItem.classList.add('menu', 'padding-bottom20px');
                    let itemHTML = `
                        <div class="flex item-center space-between">
                            <h3>${data.quantite} ${data.libelle_product}</h3>
                            <img class='delete' src="./public/wacdo/images/trash.png" alt="logo d'une poubelle pour la suppression" data-id="${data.id}" data-price="${productPrice}">
                        </div>`;
                    orderItem.innerHTML += itemHTML;
                }
                orderContent.appendChild(orderItem);
            } else {
                console.log('erreur dans le data');
            }
        });
    } else {
        // Si aucune donnée, afficher un message
        orderContent.innerHTML = '<p>Aucun produit dans la commande.</p>';
    }

    const btnDeletes = document.querySelectorAll('.delete');
    btnDeletes.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            let id = btn.getAttribute('data-id');
            let priceProduct = parseFloat(btn.getAttribute('data-price')); // Conversion en nombre
            price -= priceProduct;
            deleteArticleServer(id);
            console.log(id);
        });
    });

    const boxPrice = document.querySelector('.price');
    boxPrice.innerHTML = `${price.toFixed(2)} €`; // Affichage du total
}
// ==========================================================
// 6. Ecouteur d'evenement
// ==========================================================
// Gestion du bouton de pay
const statutPay = document.querySelector('.statut')
const btnPay = document.getElementById('pay');
const btnAbandon = document.getElementById('abandon');
btnPay.addEventListener('click', () => {
    console.log("btn pay cliqué");
    if (order_num != "") {
        console.log(`order_num est rempli ${order_num}`);
        sendValidationOrder(price);
        btnPay.classList.add('d-none')
        btnAbandon.classList.add('d-none')
        statutPay.classList.remove('d-none')
        setTimeout(() => {
            location.reload()
        }, 2000);
    } else {
        console.log(`order_num est vide`);
    }
});
//Gestion du bouton abandonner
btnAbandon.addEventListener('click', () => {
    console.log("btn abandon cliqué");
    abandonOrder(order_num)
    location.reload()
    });

