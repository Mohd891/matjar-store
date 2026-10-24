// main.js - JavaScript file for Matjar Website
// Handles form validation and shopping cart

// ===== Shopping Cart Functions =====

// Add a product to the cart
function addToCart(id, name, price) {
    var quantityInput = document.getElementById("quantity");
    var quantity = 1;

    if (quantityInput) {
        quantity = parseInt(quantityInput.value);
        if (isNaN(quantity) || quantity < 1) {
            alert("Please enter a valid quantity.");
            return;
        }
    }

    // Get cart from localStorage
    var cart = localStorage.getItem("matjarCart");
    if (cart === null) {
        cart = [];
    } else {
        cart = JSON.parse(cart);
    }

    // Check if product already in cart
    var found = false;
    for (var i = 0; i < cart.length; i++) {
        if (cart[i].id === id) {
            cart[i].quantity = cart[i].quantity + quantity;
            found = true;
            break;
        }
    }

    // If not found, add new item
    if (found === false) {
        var product = {
            id: id,
            name: name,
            price: price,
            quantity: quantity
        };
        cart.push(product);
    }

    localStorage.setItem("matjarCart", JSON.stringify(cart));
    alert(name + " has been added to your cart!");
}

// Display cart items on the cart page
function displayCart() {
    var cartTable = document.getElementById("cart-table-body");
    var cartTotal = document.getElementById("cart-total");
    var cartVat = document.getElementById("cart-vat");
    var cartGrand = document.getElementById("cart-grand-total");
    var emptyMessage = document.getElementById("empty-cart-message");
    var checkoutBtn = document.getElementById("checkout-btn");

    var cart = localStorage.getItem("matjarCart");

    if (cart === null || cart === "[]") {
        if (emptyMessage) {
            emptyMessage.style.display = "block";
        }
        if (checkoutBtn) {
            checkoutBtn.style.display = "none";
        }
        return;
    }

    cart = JSON.parse(cart);

    if (cart.length === 0) {
        if (emptyMessage) {
            emptyMessage.style.display = "block";
        }
        if (checkoutBtn) {
            checkoutBtn.style.display = "none";
        }
        return;
    }

    if (emptyMessage) {
        emptyMessage.style.display = "none";
    }

    var total = 0;

    for (var i = 0; i < cart.length; i++) {
        var subtotal = cart[i].price * cart[i].quantity;
        total = total + subtotal;

        var row = document.createElement("tr");

        var nameCell = document.createElement("td");
        nameCell.textContent = cart[i].name;
        row.appendChild(nameCell);

        var priceCell = document.createElement("td");
        priceCell.textContent = cart[i].price.toFixed(2) + " SAR";
        row.appendChild(priceCell);

        var qtyCell = document.createElement("td");
        qtyCell.textContent = cart[i].quantity;
        row.appendChild(qtyCell);

        var subtotalCell = document.createElement("td");
        subtotalCell.textContent = subtotal.toFixed(2) + " SAR";
        row.appendChild(subtotalCell);

        var removeCell = document.createElement("td");
        var removeBtn = document.createElement("button");
        removeBtn.textContent = "Remove";
        removeBtn.className = "btn btn-red";
        removeBtn.setAttribute("data-index", i);
        removeBtn.onclick = function() {
            removeFromCart(parseInt(this.getAttribute("data-index")));
        };
        removeCell.appendChild(removeBtn);
        row.appendChild(removeCell);

        cartTable.appendChild(row);
    }

    // Calculate VAT (15%)
    var vat = total * 0.15;
    var grandTotal = total + vat;

    if (cartTotal) {
        cartTotal.textContent = total.toFixed(2) + " SAR";
    }
    if (cartVat) {
        cartVat.textContent = vat.toFixed(2) + " SAR";
    }
    if (cartGrand) {
        cartGrand.textContent = grandTotal.toFixed(2) + " SAR";
    }

    // Put cart data in hidden field for checkout
    var cartDataField = document.getElementById("cart-data");
    if (cartDataField) {
        cartDataField.value = JSON.stringify(cart);
    }
}

// Remove item from cart
function removeFromCart(index) {
    var cart = localStorage.getItem("matjarCart");
    if (cart !== null) {
        cart = JSON.parse(cart);
        cart.splice(index, 1);
        localStorage.setItem("matjarCart", JSON.stringify(cart));
        location.reload();
    }
}

// Clear all items from cart
function clearCart() {
    var confirmClear = confirm("Are you sure you want to clear your cart?");
    if (confirmClear) {
        localStorage.removeItem("matjarCart");
        location.reload();
    }
}

// ===== Contact Form Validation =====

function validateContactForm() {
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var phone = document.getElementById("phone").value;
    var message = document.getElementById("message").value;

    // Clear old errors
    document.getElementById("name-error").textContent = "";
    document.getElementById("email-error").textContent = "";
    document.getElementById("phone-error").textContent = "";
    document.getElementById("message-error").textContent = "";

    var isValid = true;

    if (name === "") {
        document.getElementById("name-error").textContent = "Please enter your name.";
        isValid = false;
    }

    if (email === "") {
        document.getElementById("email-error").textContent = "Please enter your email.";
        isValid = false;
    } else if (email.indexOf("@") === -1 || email.indexOf(".") === -1) {
        document.getElementById("email-error").textContent = "Please enter a valid email address.";
        isValid = false;
    }

    if (phone === "") {
        document.getElementById("phone-error").textContent = "Please enter your phone number.";
        isValid = false;
    } else if (phone.length < 10) {
        document.getElementById("phone-error").textContent = "Phone must be at least 10 digits.";
        isValid = false;
    }

    if (message === "") {
        document.getElementById("message-error").textContent = "Please enter your message.";
        isValid = false;
    }

    if (isValid === false) {
        alert("Please fix the errors in the form.");
    }

    return isValid;
}

// ===== Registration Form Validation =====

function validateRegisterForm() {
    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;
    var email = document.getElementById("reg-email").value;
    var pwd = document.getElementById("reg-pwd").value;

    var isValid = true;

    if (fname === "") {
        alert("Please enter your first name.");
        isValid = false;
    } else if (lname === "") {
        alert("Please enter your last name.");
        isValid = false;
    } else if (email === "" || email.indexOf("@") === -1) {
        alert("Please enter a valid email address.");
        isValid = false;
    } else if (pwd === "" || pwd.length < 4) {
        alert("Password must be at least 4 characters.");
        isValid = false;
    }

    return isValid;
}

// ===== Login Form Validation =====

function validateLoginForm() {
    var email = document.getElementById("login-email").value;
    var pwd = document.getElementById("login-pwd").value;

    if (email === "" || pwd === "") {
        alert("Please enter your email and password.");
        return false;
    }
    return true;
}
