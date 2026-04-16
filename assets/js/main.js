// main.js - Core functionality

document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Toggle
    const menuBtn = document.getElementById('mobile-menu-btn');
    const navMenu = document.getElementById('nav-menu');
    if(menuBtn) {
        menuBtn.addEventListener('click', () => {
            menuBtn.classList.toggle('active');
            navMenu.classList.toggle('active');
            if(navMenu.classList.contains('active')){
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    }

    // Initialize cart state
    initCart();

    // Primary image switcher for single product
    const thumbs = document.querySelectorAll('.thumb-img');
    const mainImgNode = document.querySelector('.main-img img');
    if(thumbs.length > 0 && mainImgNode) {
        thumbs.forEach(thumb => {
            thumb.addEventListener('click', function() {
                thumbs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                mainImgNode.src = this.src;
            });
        });
    }
});

function getCart() {
    let cart = JSON.parse(localStorage.getItem('my_store_cart')) || [];
    
    // Normalize legacy images that might have saved without the base URL
    let changed = false;
    cart.forEach(item => {
        if (item.image && item.image.startsWith('/uploads')) {
            item.image = window.STORE_INFO.baseUrl + item.image;
            changed = true;
        }
    });
    
    if (changed) {
        localStorage.setItem('my_store_cart', JSON.stringify(cart));
    }
    
    return cart;
}

function saveCart(cart) {
    localStorage.setItem('my_store_cart', JSON.stringify(cart));
    updateCartCount();
}

function updateCartCount() {
    const cart = getCart();
    const count = cart.reduce((total, item) => total + parseInt(item.qty), 0);
    const badge = document.getElementById('cart-count');
    if(badge) badge.textContent = count;
}

function addToCart(product) {
    const cart = getCart();
    const existing = cart.find(i => i.id == product.id);
    const qtyToAdd = parseInt(product.qty || 1);
    
    if(existing) {
        existing.qty += qtyToAdd;
    } else {
        cart.push({...product, qty: qtyToAdd});
    }
    saveCart(cart);
    
    // Custom Toast
    showToast(`Added ${qtyToAdd}x ${product.name} to cart!`);
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.style.cssText = 'position:fixed; bottom:30px; left:50%; transform:translateX(-50%) translateY(100px); background:#111; color:#fff; padding:12px 28px; border-radius:30px; z-index:9999; transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); box-shadow:0 10px 30px rgba(0,0,0,0.2); font-weight:500; display:flex; align-items:center; gap:10px;';
    toast.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#25D366" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> ${message}`;
    document.body.appendChild(toast);
    
    // trigger animation
    setTimeout(() => { toast.style.transform = 'translateX(-50%) translateY(0)'; }, 10);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(-50%) translateY(100px)';
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}

function initCart() {
    updateCartCount();

    // Attach Add to Cart flow
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const qtyInput = document.getElementById('qty-input-' + btn.dataset.id);
            const productData = {
                id: btn.dataset.id,
                name: btn.dataset.name,
                price: parseFloat(btn.dataset.price),
                image: btn.dataset.image,
                qty: qtyInput ? qtyInput.value : 1
            };
            addToCart(productData);
        });
    });

    // Quantity adjusters in product page
    document.querySelectorAll('.qty-btn.minus').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.nextElementSibling;
            if(input.value > 1) input.value--;
        });
    });
    document.querySelectorAll('.qty-btn.plus').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.previousElementSibling;
            input.value++;
        });
    });

    // Render logic on Cart page context
    if(document.getElementById('cart-items-container')) {
        renderCartPage();
    }
}

function renderCartPage() {
    const cart = getCart();
    const container = document.getElementById('cart-items-container');
    const summaryTotalContainer = document.getElementById('cart-summary-total');
    let total = 0;

    if(cart.length === 0) {
        container.innerHTML = `
            <div style="text-align:center; padding: 80px 20px; background:#fff; border-radius:12px;">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#ddd" stroke-width="1.5" style="margin:0 auto 20px;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                <h2 style="font-family:'Clash Display'">Your cart is empty</h2>
                <p style="color:#777; margin:10px 0 25px; font-size:1.1rem;">Looks like you haven't added any products to your cart yet.</p>
                <a href="${window.STORE_INFO.baseUrl}/shop.php" class="btn btn-primary" style="padding:15px 30px;">Start Shopping</a>
            </div>`;
        document.querySelector('.cart-summary').style.display = 'none';
        return;
    }

    document.querySelector('.cart-summary').style.display = 'block';

    let html = '';
    cart.forEach((item, index) => {
        const itemSubtotal = item.price * item.qty;
        total += itemSubtotal;
        html += `
            <div class="cart-item">
                <img src="${item.image}" alt="${item.name}">
                <div class="cart-item-info">
                    <div class="cart-item-title">${item.name}</div>
                    <div class="cart-item-price">${window.STORE_INFO.currency ?? '₦'}${item.price.toFixed(2)} x ${item.qty}</div>
                    
                    <div class="qty-selector" style="height:35px; width:120px; margin-top:10px;">
                        <button class="qty-btn" style="padding:0 12px; font-size:1.2rem;" onclick="updateCartItemQty(${index}, -1)">-</button>
                        <input type="number" class="qty-input" value="${item.qty}" readonly>
                        <button class="qty-btn" style="padding:0 12px; font-size:1.2rem;" onclick="updateCartItemQty(${index}, 1)">+</button>
                    </div>

                </div>
                <div style="display:flex; flex-direction:column; align-items:flex-end;">
                    <div class="cart-item-subtotal" style="font-weight:700; font-size:1.2rem; color:var(--primary-color);">
                        ${window.STORE_INFO.currency ?? '₦'}${itemSubtotal.toFixed(2)}
                    </div>
                    <button class="cart-item-remove" onclick="removeCartItem(${index})">Remove</button>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    if(summaryTotalContainer) {
        summaryTotalContainer.textContent = `${window.STORE_INFO.currency ?? '₦'}${total.toFixed(2)}`;
    }
}

window.updateCartItemQty = function(index, change) {
    const cart = getCart();
    const newQty = cart[index].qty + change;
    if(newQty > 0) {
        cart[index].qty = newQty;
        saveCart(cart);
        renderCartPage();
    }
}

window.removeCartItem = function(index) {
    if(confirm("Remove item from cart?")){
        const cart = getCart();
        cart.splice(index, 1);
        saveCart(cart);
        renderCartPage();
    }
};

window.checkoutWhatsApp = function(event) {
    event.preventDefault();
    const cart = getCart();
    if(cart.length === 0) return;

    const name = document.getElementById('checkout-name').value;
    const address = document.getElementById('checkout-address').value;
    const phone = document.getElementById('checkout-phone').value;
    const payment = document.getElementById('checkout-payment').value;

    if(!name || !address || !phone) {
        showToast("Please complete all required fields");
        return;
    }

    const loadBtn = document.getElementById('checkout-btn');
    loadBtn.textContent = "Processing...";
    loadBtn.disabled = true;

    const total = cart.reduce((t, item) => t + (item.price * item.qty), 0);
    
    // Store order in db
    fetch(window.STORE_INFO.baseUrl + '/cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            'action': 'place_order',
            'customer_name': name,
            'customer_phone': phone,
            'address': address,
            'payment_method': payment,
            'items_json': JSON.stringify(cart),
            'total': total
        })
    }).then(res => res.json())
      .then(data => {
          if(data.success) {
            let msg = `Hi! I'd like to place an order:%0a%0a`;
            cart.forEach(item => {
                msg += `🛍️ *${item.name}* x${item.qty} — ${window.STORE_INFO.currency}${(item.price * item.qty).toFixed(2)}%0a`;
            });
            msg += `%0a💰 *Total: ${window.STORE_INFO.currency}${total.toFixed(2)}*%0a`;
            msg += `👤 Name: ${name}%0a`;
            msg += `📍 Deliver to: ${address}%0a`;
            msg += `📞 Phone: ${phone}%0a`;
            msg += `💳 Payment: ${payment}`;
            
            localStorage.removeItem('my_store_cart');
            window.location.href = `https://wa.me/${window.STORE_INFO.whatsapp}?text=${msg}`;
          } else {
              showToast("Error saving order. Please try again.");
              loadBtn.textContent = "Order via WhatsApp";
              loadBtn.disabled = false;
          }
      }).catch(err => {
          console.error(err);
          showToast("Network error. Try directly messaging us.");
          loadBtn.textContent = "Order via WhatsApp";
          loadBtn.disabled = false;
      });
};
