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

    // Free delivery message (orders over 200 SAR)
    var cartDelivery = document.getElementById("cart-delivery");
    if (cartDelivery) {
        if (grandTotal > 200) {
            cartDelivery.textContent = "You qualify for free delivery!";
        } else {
            cartDelivery.textContent = "Add " + (200 - grandTotal).toFixed(2) + " SAR more to get free delivery.";
        }
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

// ===== Login / Account Functions (demo only - data is kept in the browser) =====

var EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// Read the list of registered users (empty array if missing or corrupted)
function getUsers() {
    try {
        var users = JSON.parse(localStorage.getItem("matjarUsers"));
        if (Array.isArray(users)) {
            return users;
        }
    } catch (e) {
        // corrupted data - start fresh
    }
    return [];
}

// Get the logged-in user's name (or null)
function getCurrentUser() {
    return localStorage.getItem("matjarCurrentUser");
}

// Hash the password with SHA-256 so it is not stored as plain text
function hashPassword(password) {
    var data = new TextEncoder().encode(password);
    return crypto.subtle.digest("SHA-256", data).then(function (buffer) {
        var bytes = new Uint8Array(buffer);
        var hex = "";
        for (var i = 0; i < bytes.length; i++) {
            hex += ("0" + bytes[i].toString(16)).slice(-2);
        }
        return hex;
    });
}

function setError(id, message) {
    document.getElementById(id).textContent = message;
}

// Update the navigation link and the login page depending on login state
function updateAuthUI() {
    var user = getCurrentUser();
    var link = document.getElementById("auth-link");
    if (link) {
        link.textContent = user ? "Logout (" + user + ")" : "Login";
        link.href = user ? "#" : "login.html";
        link.onclick = user ? function (e) { e.preventDefault(); logout(); } : null;
    }
    var forms = document.getElementById("auth-forms");
    var message = document.getElementById("logged-in-message");
    if (forms && message) {
        forms.style.display = user ? "none" : "block";
        message.style.display = user ? "block" : "none";
        document.getElementById("logged-in-name").textContent = user || "";
    }
}

function registerUser(event) {
    event.preventDefault();
    var name = document.getElementById("reg-name").value.trim();
    var email = document.getElementById("reg-email").value.trim().toLowerCase();
    var password = document.getElementById("reg-password").value;
    var confirmPassword = document.getElementById("reg-confirm").value;
    var isValid = true;

    setError("reg-name-error", "");
    setError("reg-email-error", "");
    setError("reg-password-error", "");
    setError("reg-confirm-error", "");

    if (name === "") {
        setError("reg-name-error", "Please enter your name.");
        isValid = false;
    }
    if (!EMAIL_PATTERN.test(email)) {
        setError("reg-email-error", "Please enter a valid email address.");
        isValid = false;
    } else {
        var users = getUsers();
        for (var i = 0; i < users.length; i++) {
            if (users[i].email === email) {
                setError("reg-email-error", "This email is already registered.");
                isValid = false;
            }
        }
    }
    if (password.length < 6) {
        setError("reg-password-error", "Password must be at least 6 characters.");
        isValid = false;
    }
    if (confirmPassword !== password) {
        setError("reg-confirm-error", "Passwords do not match.");
        isValid = false;
    }
    if (!isValid) {
        return false;
    }

    hashPassword(password).then(function (hash) {
        var allUsers = getUsers();
        allUsers.push({ name: name, email: email, password: hash });
        localStorage.setItem("matjarUsers", JSON.stringify(allUsers));
        localStorage.setItem("matjarCurrentUser", name);
        alert("Account created! Welcome, " + name + ".");
        updateAuthUI();
    });
    return false;
}

function loginUser(event) {
    event.preventDefault();
    var email = document.getElementById("login-email").value.trim().toLowerCase();
    var password = document.getElementById("login-password").value;
    var isValid = true;

    setError("login-email-error", "");
    setError("login-password-error", "");

    if (!EMAIL_PATTERN.test(email)) {
        setError("login-email-error", "Please enter a valid email address.");
        isValid = false;
    }
    if (password === "") {
        setError("login-password-error", "Please enter your password.");
        isValid = false;
    }
    if (!isValid) {
        return false;
    }

    hashPassword(password).then(function (hash) {
        var users = getUsers();
        for (var i = 0; i < users.length; i++) {
            if (users[i].email === email && users[i].password === hash) {
                localStorage.setItem("matjarCurrentUser", users[i].name);
                alert("Welcome back, " + users[i].name + "!");
                updateAuthUI();
                return;
            }
        }
        setError("login-password-error", "Incorrect email or password.");
    });
    return false;
}

function logout() {
    localStorage.removeItem("matjarCurrentUser");
    updateAuthUI();
}

// Run on every page once the DOM is ready
document.addEventListener("DOMContentLoaded", updateAuthUI);

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
