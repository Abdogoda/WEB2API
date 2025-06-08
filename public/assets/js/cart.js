// Get cart from localStorage or initialize an empty cart
    function getCart() {
        return JSON.parse(localStorage.getItem("cart")) || [];
    }

    // Save cart to localStorage
    function saveCart(cart) {
        localStorage.setItem("cart", JSON.stringify(cart));
    }

    // Update cart badge quantity
    function updateCartBadge() {
        let cart = getCart();
        let totalQuantity = cart.reduce((sum, item) => sum + item.quantity, 0);
        let cartBadge = document.getElementById("cart-badge");

        if (cartBadge) {
            if (totalQuantity > 0) {
                cartBadge.innerText = totalQuantity;
                cartBadge.classList.remove("hidden");
            } else {
                cartBadge.classList.add("hidden");
            }
        }
    }

    // Add product to cart
    function addToCart(productId, name, price, quantity = 1, image = "") {
        let cart = getCart();
        let existingProduct = cart.find(item => item.id === productId);

        if (existingProduct) {
            existingProduct.quantity += quantity;
        } else {
            cart.push({ id: productId, name, price, quantity, image });
        }

        saveCart(cart);
        updateCartBadge();
    }

    // Remove product from cart
    function removeFromCart(productId) {
        let cart = getCart().filter(item => item.id !== productId);
        saveCart(cart);
        updateCartBadge();
        displayCartItems(); // Update the UI
    }

    // Update product quantity in cart
    function updateCartItem(productId, quantity) {
        let cart = getCart();
        let product = cart.find(item => item.id === productId);

        if (product) {
            product.quantity = quantity > 0 ? quantity : 1;
        }

        saveCart(cart);
        updateCartBadge();
        displayCartItems(); // Refresh cart items
    }

    // Get total price of cart
    function getTotalPrice() {
        let cart = getCart();
        return cart.reduce((total, item) => total + item.price * item.quantity, 0);
    }

    // Clear the entire cart
    function clearCart() {
        localStorage.removeItem("cart");
        updateCartBadge();
        displayCartItems();
        closeModal('clearCartModal');
    }

    // Display cart items in the cart page
    function displayCartItems() {
        let cart = getCart();
        let cartContainer = document.getElementById("cart-items");
        let totalPriceContainer = document.getElementById("cart-total");

        if (!cartContainer || !totalPriceContainer) return;

        cartContainer.innerHTML = "";
        
        if (cart.length === 0) {
            cartContainer.innerHTML = "<p class='text-center text-gray-600'>Your cart is empty.</p>";
            totalPriceContainer.innerText = "Total: $0.00";
            return;
        }

        cart.forEach(item => {
            cartContainer.innerHTML += `
                <div class="cart-item flex justify-between items-center border-b border-gray-700 py-3">
                    <div class="flex items-center gap-4">
                        <img src="${item.image}" alt="${item.name}" class="w-16 h-16 rounded">
                        <div>
                            <p class="font-semibold">${item.name}</p>
                            <p class="text-blue-400">$${item.price}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="number" value="${item.quantity}" min="1" 
                            class="w-14 text-center bg-gray-700 border border-gray-600 p-1 rounded text-gray-300"
                            onchange="updateCartItem(${item.id}, this.value)">
                        <button class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded" 
                            onclick="removeFromCart(${item.id})">
                            Remove
                        </button>
                    </div>
                </div>
            `;
        });

        totalPriceContainer.innerText = `Total: $${getTotalPrice().toFixed(2)}`;
    }

    function getCartItemsWithQuantity() {
        let cart = getCart();
        return cart.map(item => ({
            id: item.id,
            quantity: item.quantity
        }));
    }

    function updateCheckoutInputs() {
        let cartItems = getCartItemsWithQuantity();
        let cartInputsContainer = document.getElementById("cart-hidden-inputs");

        cartInputsContainer.innerHTML = "";

        cartItems.forEach((item, index) => {
            cartInputsContainer.innerHTML += `
                <input type="hidden" name="cart_items[${index}][id]" value="${item.id}">
                <input type="hidden" name="cart_items[${index}][quantity]" value="${item.quantity}">
            `;
        });
    }
    
    // Initialize cart badge & items on page load
    document.addEventListener("DOMContentLoaded", () => {
        updateCartBadge();
        displayCartItems();
    });