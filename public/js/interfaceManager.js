const API_BASE_URL = 'http://exam-back.mdaszczynski.mywebecom.ovh';
const API_READY_ORDER_URL = `${API_BASE_URL}/readyOrder`;

/**
 * role : passer une commande a livrer, envoi uen request en methode POST a l'API 
 * @param  int order_num
 */
async function passOrderToReady(order_num) {
    console.log('Corps de la requête envoyé :', JSON.stringify({ order: order_num }));
    try {
        const response = await fetch(API_READY_ORDER_URL, {
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
        console.error('Erreur lors du changement de statut de la commande :', error);
    }

}


//gestion du btn livrer
const btnPrepare = document.getElementById('prepare');
btnPrepare.addEventListener('click', () => {
    console.log("btn livrer cliqué");
    let order_num = btnPrepare.getAttribute('data-order')
    console.log(btnPrepare);
    passOrderToReady(order_num)
    location.href = '/interface_manager'
    });
