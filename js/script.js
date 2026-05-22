/**
 * DUMBLEDORE BOUTIQUE - JavaScript File
 * Premium Luxury Ecommerce Functionality
 */

// Initialize AOS (Animate on Scroll)
document.addEventListener('DOMContentLoaded', function() {
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    }
});

// Add to Cart with Notification
function addToCart(productId, quantity = 1) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);

    fetch('php/add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Added to cart!', 'success');
            updateCartCount();
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding to cart', 'error');
    });
}

// Add to Wishlist with Notification
function addToWishlist(productId) {
    const formData = new FormData();
    formData.append('product_id', productId);

    fetch('php/add_to_wishlist.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            updateWishlistCount();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Update Cart Count
function updateCartCount() {
    fetch('php/get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            const cartBadge = document.querySelector('[data-cart-count]');
            if (cartBadge) {
                cartBadge.textContent = data.count;
                cartBadge.style.display = data.count > 0 ? 'block' : 'none';
            }
        });
}

// Update Wishlist Count
function updateWishlistCount() {
    fetch('php/get_wishlist_count.php')
        .then(response => response.json())
        .then(data => {
            const wishlistBadge = document.querySelector('[data-wishlist-count]');
            if (wishlistBadge) {
                wishlistBadge.textContent = data.count;
                wishlistBadge.style.display = data.count > 0 ? 'block' : 'none';
            }
        });
}

// Show Notification
function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' : type === 'error' ? 'alert-danger' : 'alert-info';
    const alertHTML = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 100px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', alertHTML);

    // Auto remove after 3 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert:last-of-type');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 3000);
}

// Format Currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR'
    }).format(amount);
}

// Quantity Input Validation
function validateQuantity(input) {
    const value = parseInt(input.value);
    const max = parseInt(input.max);
    const min = parseInt(input.min) || 1;

    if (value < min) input.value = min;
    if (value > max) input.value = max;
}

// Search Product
function searchProduct(query) {
    if (query.trim() === '') {
        return;
    }
    window.location.href = 'search.php?q=' + encodeURIComponent(query);
}

// Smooth Scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#' && document.querySelector(href)) {
            e.preventDefault();
            document.querySelector(href).scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// WhatsApp Integration
function openWhatsApp() {
    const message = "Hello, I'm interested in your products!";
    const phone = "919876543210";
    window.open(`https://wa.me/${phone}?text=${encodeURIComponent(message)}`, '_blank');
}

// Filter Products (Client-side)
function filterProducts(category) {
    if (category === 'all') {
        document.querySelectorAll('.product-card').forEach(card => {
            card.style.display = 'block';
        });
    } else {
        document.querySelectorAll('.product-card').forEach(card => {
            if (card.dataset.category === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
}

// Lazy Load Images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => imageObserver.observe(img));
}

// Initialize tooltips (Bootstrap)
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Print Order
function printOrder(orderId) {
    window.print();
}

// Confirm Delete
function confirmDelete(message = 'Are you sure you want to delete this item?') {
    return confirm(message);
}

// Validate Email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Validate Phone
function validatePhone(phone) {
    const re = /^[0-9]{10,}$/;
    return re.test(phone);
}

// Format Date
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-IN', options);
}

console.log('DUMBLEDORE BOUTIQUE - Premium Ecommerce System Loaded');
