<?php
require_once 'includes/db.php';

$page_title = 'About Us';
require_once 'includes/header.php';
?>

<div class="page-banner">
    <div class="container">
        <h1>Our Story</h1>
        <p>Learn more about <?= htmlspecialchars($store['name']) ?>.</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display:flex; flex-wrap:wrap; gap:50px; align-items:center;">
            <div style="flex:1; min-width:300px;">
                <img src="https://images.unsplash.com/photo-1615332251655-79e317e12ff2?q=80&w=1760&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Our Store" style="border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.1);">
            </div>
            <div style="flex:1; min-width:300px;">
                <h2 style="font-size:2.5rem; margin-bottom:20px; font-family:'Clash Display', sans-serif;">Welcome to <?= htmlspecialchars($store['name']) ?></h2>
                <p style="font-size:1.1rem; color:#555; margin-bottom:20px; line-height:1.8;">We are a proudly Nigerian business dedicated to providing you with premium, authentic products ranging from fashion, skincare to everyday essentials. Our goal is to bring the best directly to your doorstep with zero hassle.</p>
                <p style="font-size:1.1rem; color:#555; line-height:1.8;">Since our inception, our focus has been on customer satisfaction, high quality, and fast delivery across Nigeria.</p>
                
                <h3 style="margin-top:30px; margin-bottom:15px; font-size:1.5rem;">Our Core Values</h3>
                <ul style="list-style:none; padding:0; display:flex; flex-direction:column; gap:10px; color:#555;">
                    <li style="display:flex; align-items:center; gap:10px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary-color)" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Unmatched Quality
                    </li>
                    <li style="display:flex; align-items:center; gap:10px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary-color)" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Swift Nationwide Delivery
                    </li>
                    <li style="display:flex; align-items:center; gap:10px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary-color)" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Total Customer Satisfaction
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
