// main.js - JavaScript file for Matjar Website
// This file handles form validation and shopping cart functionality

// ===== Shopping Cart Functions =====

// Read the cart from localStorage (returns an empty array if missing or corrupted)
function getCart() {
    try {
        var cart = JSON.parse(localStorage.getItem("matjarCart"));
        if (Array.isArray(cart)) {
            return cart;
        }
    } catch (e) {
        // corrupted data - fall through and start a fresh cart
    }
    return [];
}

// Function to add a product to the cart
function addToCart(name, price) {
    // Get the quantity from the input field
    var quantityInput = document.getElementById("quantity");
    var quantity = 1;

    // Check if quantity input exists on the page
    if (quantityInput) {
        quantity = parseInt(quantityInput.value);
        // Validate quantity
        if (isNaN(quantity) || quantity < 1 || quantity > 10) {
            alert("Please enter a quantity between 1 and 10.");
            return;
        }
    }

    // Get the current cart from localStorage
    var cart = getCart();

    // Check if the product is already in the cart
    var found = false;
    for (var i = 0; i < cart.length; i++) {
        if (cart[i].name === name) {
            cart[i].quantity = Math.min(cart[i].quantity + quantity, 10);
            found = true;
            break;
        }
    }

    // If the product is not in the cart, add it
    if (found === false) {
        var product = {
            name: name,
            price: price,
            quantity: quantity
        };
        cart.push(product);
    }

    // Save the cart back to localStorage
    localStorage.setItem("matjarCart", JSON.stringify(cart));

    // Show a message to the user
    alert(name + " has been added to your cart!");
}

// Function to display the cart on the cart page
function displayCart() {
    var cartTable = document.getElementById("cart-table-body");
    var cartTotal = document.getElementById("cart-total");
    var cartVat = document.getElementById("cart-vat");
    var cartGrand = document.getElementById("cart-grand-total");
    var emptyMessage = document.getElementById("empty-cart-message");

    // Get the cart from localStorage
    var cart = getCart();

    // If cart is empty, show a message
    if (cart.length === 0) {
        if (emptyMessage) {
            emptyMessage.style.display = "block";
        }
        if (cartTotal) {
            cartTotal.textContent = "0.00 SAR";
        }
        if (cartVat) {
            cartVat.textContent = "0.00 SAR";
        }
        if (cartGrand) {
            cartGrand.textContent = "0.00 SAR";
        }
        return;
    }

    // Hide the empty message
    if (emptyMessage) {
        emptyMessage.style.display = "none";
    }

    // Calculate the total
    var total = 0;

    // Loop through the cart items and add rows to the table
    for (var i = 0; i < cart.length; i++) {
        var subtotal = cart[i].price * cart[i].quantity;
        total = total + subtotal;

        // Create a new table row
        var row = document.createElement("tr");

        // Product name cell
        var nameCell = document.createElement("td");
        nameCell.textContent = cart[i].name;
        row.appendChild(nameCell);

        // Price cell
        var priceCell = document.createElement("td");
        priceCell.textContent = cart[i].price.toFixed(2) + " SAR";
        row.appendChild(priceCell);

        // Quantity cell
        var qtyCell = document.createElement("td");
        qtyCell.textContent = cart[i].quantity;
        row.appendChild(qtyCell);

        // Subtotal cell
        var subtotalCell = document.createElement("td");
        subtotalCell.textContent = subtotal.toFixed(2) + " SAR";
        row.appendChild(subtotalCell);

        // Remove button cell
        var removeCell = document.createElement("td");
        var removeBtn = document.createElement("button");
        removeBtn.textContent = "Remove";
        removeBtn.className = "btn btn-red";
        removeBtn.setAttribute("data-index", i);
        removeBtn.onclick = function () {
            removeFromCart(parseInt(this.getAttribute("data-index")));
        };
        removeCell.appendChild(removeBtn);
        row.appendChild(removeCell);

        // Add the row to the table
        cartTable.appendChild(row);
    }

    // Calculate VAT (15% in Saudi Arabia)
    var vat = total * 0.15;
    var grandTotal = total + vat;

    // Display the totals
    if (cartTotal) {
        cartTotal.textContent = total.toFixed(2) + " SAR";
    }
    if (cartVat) {
        cartVat.textContent = vat.toFixed(2) + " SAR";
    }
    if (cartGrand) {
        cartGrand.textContent = grandTotal.toFixed(2) + " SAR";
    }
}

// Function to remove a product from the cart
function removeFromCart(index) {
    var cart = getCart();
    cart.splice(index, 1);
    localStorage.setItem("matjarCart", JSON.stringify(cart));
    // Reload the page to update the cart display
    location.reload();
}

// Function to clear the entire cart
function clearCart() {
    var confirm_clear = confirm("Are you sure you want to clear your cart?");
    if (confirm_clear) {
        localStorage.removeItem("matjarCart");
        location.reload();
    }
}

// ===== Form Validation Function =====

function validateForm() {
    // Get form values
    var name = document.getElementById("name").value.trim();
    var email = document.getElementById("email").value.trim();
    var phone = document.getElementById("phone").value.trim();
    var message = document.getElementById("message").value.trim();

    // Clear previous error messages
    document.getElementById("name-error").textContent = "";
    document.getElementById("email-error").textContent = "";
    document.getElementById("phone-error").textContent = "";
    document.getElementById("message-error").textContent = "";

    var isValid = true;

    // Validate name - must not be empty
    if (name === "") {
        document.getElementById("name-error").textContent = "Please enter your name.";
        isValid = false;
    }

    // Validate email - must look like name@domain.ext
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === "") {
        document.getElementById("email-error").textContent = "Please enter your email.";
        isValid = false;
    } else if (!emailPattern.test(email)) {
        document.getElementById("email-error").textContent = "Please enter a valid email address.";
        isValid = false;
    }

    // Validate phone - must be numbers only and at least 10 digits
    if (phone === "") {
        document.getElementById("phone-error").textContent = "Please enter your phone number.";
        isValid = false;
    } else if (!/^\+?[0-9\s]+$/.test(phone) || phone.replace(/\D/g, "").length < 10) {
        document.getElementById("phone-error").textContent = "Phone number must contain at least 10 digits (numbers only).";
        isValid = false;
    }

    // Validate message - must not be empty
    if (message === "") {
        document.getElementById("message-error").textContent = "Please enter your message.";
        isValid = false;
    }

    // If everything is valid, show success message
    if (isValid) {
        alert("Thank you for contacting us! We will get back to you soon.");
        // There is no server yet, so reset the form instead of submitting it
        document.getElementById("name").form.reset();
        return false;
    } else {
        alert("Please fix the errors in the form.");
        return false;
    }
}
