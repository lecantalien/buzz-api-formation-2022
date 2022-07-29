console.log('search.js loaded', performance.now());
const searchInput = document.querySelector('#search-bar');

// detect press enter on searchInput
searchInput.addEventListener('keyup', (e) => {
    if (e.keyCode === 13) {
        // get value of searchInput
        const searchValue = searchInput.value;
        // clear searchInput
        searchInput.value = '';
        // redirect to search page
        window.location.href = `/search?q=${searchValue}`;
    }
});