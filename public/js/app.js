/************* Script qui s'occupe dans les templates
 *  admin de la gestion du modal d'ajout ***************/

const addProduct = document.querySelector('.add-new')
const modalAddProduct = document.getElementById('modalAdd')
addProduct.addEventListener('click', () =>{
    console.log('click');
    modalAddProduct.showModal()
})
const closeModal = document.querySelector('.close-modal')
closeModal.addEventListener('click', ()=> {
    console.log('click');
    modalAddProduct.close()
})
