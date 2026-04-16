<?php
require_once 'includes/db.php';

// Handle AJAX POST request to place an order in the database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'place_order') {
    header('Content-Type: application/json');
    try {
        $name = $_POST['customer_name'] ?? '';
        $phone = $_POST['customer_phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $payment = $_POST['payment_method'] ?? '';
        $items_raw = $_POST['items_json'] ?? '[]';
        $total = $_POST['total'] ?? 0;
        
        $items_decoded = json_decode($items_raw, true);
        
        // Bundle address & payment into the items JSON for admin view
        $payload = [
            'items' => $items_decoded,
            'address' => $address,
            'payment' => $payment
        ];
        
        $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_phone, items_json, total) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $phone, json_encode($payload), $total]);
        
        echo json_encode(['success' => true]);
    } catch(Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

$page_title = 'Your Cart';
require_once 'includes/header.php';
?>

<div class="page-banner">
    <div class="container">
        <h1>Shopping Cart</h1>
        <p>Review your items and proceed to checkout.</p>
    </div>
</div>

<section class="section">
    <div class="container">
        
        <div class="cart-layout" id="cart-wrapper">
            <!-- Items Column -->
            <div class="cart-items" id="cart-items-container">
                <!-- Javascript will render items here -->
            </div>

            <!-- Summary & Checkout Column -->
            <div class="cart-summary" style="display:none;">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="cart-summary-total">₦0.00</span>
                </div>
                <div class="summary-row">
                    <span>Delivery</span>
                    <span style="color:#777; font-size:0.95rem;">Calculated via WhatsApp</span>
                </div>
                
                <div class="summary-total" style="margin-top:20px; padding-top:20px; border-top:2px dashed #eee; font-weight:700;">
                    To complete your order, please fill your details below.
                </div>
                
                <form id="checkout-form" onsubmit="window.checkoutWhatsApp(event)">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="checkout-name" class="form-control" required placeholder="John Doe">
                    </div>
                    <div class="form-group">
                        <label>Delivery Address</label>
                        <textarea id="checkout-address" class="form-control" required placeholder="123 Example Street, Lagos" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" id="checkout-phone" class="form-control" required placeholder="+234...">
                    </div>
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select id="checkout-payment" class="form-control" required>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cash on Delivery">Cash on Delivery</option>
                        </select>
                    </div>
                    
                    <button type="submit" id="checkout-btn" class="btn btn-primary" style="width:100%; display:flex; gap:10px; padding:15px; font-size:1.1rem; align-items:center; justify-content:center; background:#25D366; border:none;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        Order via WhatsApp
                    </button>
                    <p style="text-align:center; font-size:0.85rem; color:#888; margin-top:15px;">You will be redirected to WhatsApp to confirm your order.</p>
                </form>
            </div>
        </div>
        
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
