console.log('cart.js loaded', performance.now());


const classBtnAdd = 'add-to-cart';

const updateBadgeCart = nbrItems => {
    const badge = document.querySelector('#badge-cart');
    if(badge === null || badge === undefined) {
        return;
    }
    badge.innerText = nbrItems;
    if(nbrItems <= 0) {
        badge.classList.add('d-none');
    } else {
        badge.classList.remove('d-none');
    }
}

const notyf = new Notyf({
    position: {x: 'right', y: 'top'},
    ripple: false,
    dismissible: true,
});

const refreshCart = (isError=false) => {

    //get the uri
    const uri = window.location.pathname;
if(uri === '/cart') {
    //refresh page
    if(isError === false) {
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }
    return;
}


    fetch('/apic/steam/cart', {
        method: 'GET',
    })
        .then(res => res.json())
        .then(data => {
            console.log('data', data);
            if(data.error === true){
                throw new Error(data.message);
            }
            updateBadgeCart(data.cart.items.length)
        })
        .catch(err => {
            console.log(err.message)
            notyf.error(err.message);
        })
    ;
}

const addToCart = evt => {
    evt.preventDefault();
    editCart( evt.target?.dataset?.id, 'add');
}

const removeFromCart = evt => {
    evt.preventDefault();
    editCart( evt.target?.dataset?.id, 'remove');
}

const editCart = (game_id, action='add') => {
    let method = 'POST';
    let urlTarget = '/apic/steam/cart';
    if (action === 'remove') {
        method = 'DELETE';
        urlTarget = '/apic/steam/cart/' + game_id;
    }
    const formData = new FormData();
    formData.append('game_id', game_id);
    fetch(urlTarget, {
        method: method,
        body: formData,
    })
        .then(res => res.json())
        .then(data => {
            console.log('data', data);
            if(data.error === true){
                throw new Error(data.message);
            }
            notyf.success(data.message);
            // todo refresh cart icon or refresh page
            refreshCart();
        })
        .catch(err => {
            console.log(err.message)
            notyf.error(err.message)
            refreshCart(true);
            }
        );
}

const initAddToCart = () => {
    const allBtns = document.querySelectorAll(`.add-to-cart`);
    console.log('allBtns', allBtns);
    allBtns.forEach(btn => {
        btn.addEventListener('click', addToCart);
    });
}

const initRemoveFromCart = () => {
    const allBtns = document.querySelectorAll(`.remove-from-cart`);
   // console.log('allBtns', allBtns);
    allBtns.forEach(btn => {
        btn.addEventListener('click', removeFromCart);
    });
}


document.addEventListener('DOMContentLoaded', (evt) => {
    //the event occurred
    console.log('event', evt);
    initAddToCart();
    initRemoveFromCart()
})