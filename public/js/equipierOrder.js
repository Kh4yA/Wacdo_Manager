const API_BASE_URL = 'http://exam-back.mdaszczynski.mywebecom.ovh';
const API_DELIVERY_ORDER_URL = `${API_BASE_URL}/deliveryOrder`;

/**
 * role : passer une commande a livrer
 * @param  int order_num
 */
async function passOrderToDelivery(order_num) {
    console.log('Corps de la requête envoyé :', JSON.stringify({ order: order_num }));
    try {
        const response = await fetch(API_DELIVERY_ORDER_URL, {
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
        console.error('Erreur lors du chagment de statut (passaga a livré) de la commande :', error);
    }

}


//gestion du btn livrer
const btnLivrer = document.getElementById('livre');
btnLivrer.addEventListener('click', () => {
    console.log("btn livrer cliqué");
    let order_num = btnLivrer.getAttribute('data-number')
    console.log(btnLivrer);
    passOrderToDelivery(order_num)
    location.href = '/commandes'
    });
