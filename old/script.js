document.addEventListener('DOMContentLoaded', () => {
    const sortDropdown = document.getElementById('sort');

    sortDropdown.addEventListener('change', () => {
        const sortValue = sortDropdown.value;
        sortProducts(sortValue);
    });

    function sortProducts(sortValue) {
        const productsContainer = document.querySelector('.products');
        const products = Array.from(productsContainer.children);

        products.sort((a, b) => {
            switch (sortValue) {
                case 'relevance':
                    return 0; 
                case 'price-low':
                    return getPrice(a) - getPrice(b);
                case 'price-high':
                    return getPrice(b) - getPrice(a);
                case 'discount':
                    return getDiscount(b) - getDiscount(a);
                case 'name':
                    return a.querySelector('.product-details p:first-child').textContent.localeCompare(
                        b.querySelector('.product-details p:first-child').textContent
                    );
                default:
                    return 0;
            }
        });

        products.forEach(product => productsContainer.appendChild(product));
    }

    function getPrice(product) {
        const priceElement = product.querySelector('.price span:first-child');
        return parseFloat(priceElement.textContent.replace('₹', ''));
    }

    function getDiscount(product) {
        const discountElement = product.querySelector('.discount');
        return discountElement ? parseFloat(discountElement.textContent.replace('% OFF', '')) : 0;
    }
});
