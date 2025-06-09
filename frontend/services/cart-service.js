// Cart Service - Add this to your services folder as cart-service.js
var CartService = (function() {
    
    var cart = { items: [], total: 0, itemCount: 0 };

    var products = [
        { id: 1, name: "Jordan 11 Cool Grey", price: 225.00, image: "assets/img/air-jordan-11-cool-grey-ct8012-005-release-date.webp", description: "Size 42 EU, Space Gray Colorway" },
        { id: 2, name: "Devin Booker Air Force 1 Low", price: 200.00, image: "assets/img/DBookAf1.avif", description: "Size 43 EU, Barely Grey/Moon Particle-Pale Ivory Colorway" },
        { id: 3, name: "Sneakers Air Jordan 4", price: 245.00, image: "assets/img/jordan4.webp", description: "size 45 EU, Black Thunder Colorway" },
        { id: 4, name: "Air Jordan 1 Retro Chicago Bulls", price: 40.00, image: "assets/img/Chicago Jordan1.jpg", description: "Classic Chicago Bulls colorway" },
        { id: 5, name: "Adidas Gazelle", price: 25.00, image: "assets/img/samba.webp", description: "Classic Adidas style" }
    ];

    function initCart() {
        var savedCart = localStorage.getItem('kickvault_cart');
        if (savedCart) {
            cart = JSON.parse(savedCart);
        }
        updateCartUI();
    }

    function saveCart() {
        localStorage.setItem('kickvault_cart', JSON.stringify(cart));
    }

    function addToCart(productId, quantity = 1) {
        var product = products.find(p => p.id == productId);
        if (!product) {
            console.error('Product not found:', productId);
            return;
        }
        var existingItem = cart.items.find(item => item.id == productId);
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cart.items.push({ id: product.id, name: product.name, price: product.price, image: product.image, description: product.description, quantity: quantity });
        }
        updateCartTotals();
        saveCart();
        updateCartUI();
        if (typeof toastr !== 'undefined') {
            toastr.success(product.name + ' added to cart!');
        }
    }

    // CHANGED: removeFromCart now accepts the event object to prevent default behavior
    function removeFromCart(event, productId) {
        event.preventDefault(); // This is crucial to stop the link from navigating
        var itemToRemove = cart.items.find(item => item.id == productId);
        if (itemToRemove && confirm(`Are you sure you want to remove ${itemToRemove.name} from the cart?`)) {
            cart.items = cart.items.filter(item => item.id != productId);
            updateCartTotals();
            saveCart();
            updateCartUI();
            renderCartItems();
            toastr.info(itemToRemove.name + ' removed from cart.');
        }
    }

    function updateCartTotals() {
        cart.total = 0;
        cart.itemCount = 0;
        cart.items.forEach(item => {
            cart.total += item.price * item.quantity;
            cart.itemCount += item.quantity;
        });
    }

    function updateCartUI() {
        var cartBadge = document.querySelector('.btn[href="#cart"] .badge');
        if (cartBadge) {
            if (cart.itemCount > 0) {
                cartBadge.textContent = cart.itemCount;
                cartBadge.style.display = 'inline-block';
            } else {
                cartBadge.style.display = 'none';
            }
        }
    }

    function renderCartItems() {
        var cartContainer = document.getElementById('cart-items-container');
        var itemCountEl = document.getElementById('cart-item-count');
        var subtotalEl = document.getElementById('cart-subtotal');
        var shippingEl = document.getElementById('cart-shipping');
        var totalEl = document.getElementById('cart-total');
        var checkoutTotalEl = document.getElementById('checkout-total');

        if (!cartContainer) return;

        cartContainer.innerHTML = '';
        itemCountEl.textContent = `You have ${cart.itemCount} items in your cart`;

        if (cart.items.length === 0) {
            cartContainer.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi-cart-x" style="font-size: 4rem; color: #6c757d;"></i>
                    <h4 class="mt-3">Your cart is empty</h4>
                    <p class="text-muted">Add some awesome sneakers to get started!</p>
                    <a href="#product" class="btn btn-primary mt-3">Continue Shopping</a>
                </div>`;
        } else {
            cart.items.forEach(function(item) {
                // CHANGED: The onclick handler now passes the 'event' object
                var itemHTML = `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex flex-row align-items-center">
                                    <div><img src="${item.image}" class="img-fluid rounded-3" alt="Shopping item" style="width: 65px;"></div>
                                    <div class="ms-3">
                                        <h5>${item.name}</h5>
                                        <p class="small mb-0">${item.description}</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-row align-items-center">
                                    <div style="width: 50px;"><h5 class="fw-normal mb-0">${item.quantity}</h5></div>
                                    <div style="width: 80px;"><h5 class="mb-0">$${(item.price * item.quantity).toFixed(2)}</h5></div>
                                    <a href="#" onclick="CartService.removeFromCart(event, ${item.id})" style="color: #cecece;"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>`;
                cartContainer.insertAdjacentHTML('beforeend', itemHTML);
            });
        }

        var shippingCost = cart.items.length > 0 ? 20.00 : 0;
        var finalTotal = cart.total + shippingCost;
        
        subtotalEl.textContent = `$${cart.total.toFixed(2)}`;
        shippingEl.textContent = `$${shippingCost.toFixed(2)}`;
        totalEl.textContent = `$${finalTotal.toFixed(2)}`;
        checkoutTotalEl.textContent = `$${finalTotal.toFixed(2)}`;
    }

    // Public API - expose functions to be used in HTML
    return {
        init: initCart,
        addToCart: addToCart,
        removeFromCart: removeFromCart, // Expose the updated function
        renderCartItems: renderCartItems
    };
})();

// Initialize the service when the document is ready
$(document).ready(function() {
    CartService.init();
    
    // CHANGED: The click handler now takes the event 'e' and prevents default behavior.
    // I also added the class .add-to-cart-btn to the selector to be more specific.
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault(); // Stop the link from navigating
        
        var card = $(this).closest('.card[data-product-id]'); // Ensure the card has the data attribute
        var productId = card.data('product-id');
        
        if (productId) {
            CartService.addToCart(productId);
        } else {
            console.error('Could not find product-id for this card. Make sure product-detail.html is updated.');
        }
    });
    
    // Rerender cart items when cart page is visited
    $(document).on('click', 'a[href="#cart"]', function() {
        setTimeout(function() {
            CartService.renderCartItems();
        }, 50);
    });
});

// Make CartService globally available so HTML onclick attributes can access it
window.CartService = CartService;