<?php
require_once 'includes/db.php';

$page_title = 'Contact Us';
require_once 'includes/header.php';
?>

<div class="page-banner">
    <div class="container">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you.</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display:flex; flex-wrap:wrap; gap:50px;">
            <div style="flex:1; min-width:300px;">
                <h2 style="font-size:2rem; margin-bottom:30px; font-family:'Clash Display', sans-serif;">Get in Touch</h2>
                
                <div style="display:flex; flex-direction:column; gap:25px;">
                    <div style="display:flex; align-items:flex-start; gap:15px;">
                        <div style="background:#f4f4f4; padding:15px; border-radius:12px; color:var(--primary-color);">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <div>
                            <h4 style="font-size:1.1rem; margin-bottom:5px;">Address</h4>
                            <p style="color:#555;"><?= htmlspecialchars($store['address']) ?></p>
                        </div>
                    </div>
                    
                    <div style="display:flex; align-items:flex-start; gap:15px;">
                        <div style="background:#f4f4f4; padding:15px; border-radius:12px; color:var(--primary-color);">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <div>
                            <h4 style="font-size:1.1rem; margin-bottom:5px;">Phone / WhatsApp</h4>
                            <p style="color:#555; margin-bottom:5px;"><?= htmlspecialchars($store['phone']) ?></p>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $store['whatsapp']) ?>" style="color:#25d366; font-weight:600; display:inline-flex; align-items:center; gap:5px;">Chat on WhatsApp &rarr;</a>
                        </div>
                    </div>

                    <div style="display:flex; align-items:flex-start; gap:15px;">
                        <div style="background:#f4f4f4; padding:15px; border-radius:12px; color:var(--primary-color);">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                        <div>
                            <h4 style="font-size:1.1rem; margin-bottom:5px;">Email Support</h4>
                            <p style="color:#555;"><a href="mailto:<?= htmlspecialchars($store['email']) ?>"><?= htmlspecialchars($store['email']) ?></a></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="flex:1; min-width:300px; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.05); border:1px solid #eee;">
                <!-- Embed Google Maps purely as an iframe placeholder or with real location search -->
                <iframe 
                    width="100%" 
                    height="100%" 
                    style="border:0; min-height:400px;" 
                    loading="lazy" 
                    allowfullscreen 
                    src="https://www.google.com/maps/embed/v1/place?key=REPLACE_WITH_API_KEY&q=<?= urlencode($store['address']) ?>&zoom=14">
                </iframe>
                <!-- Using a generic embed map since we don't have API key. Usually users can swap this iframe -->
                <script>
                    // Fallback iframe for presentation without API key
                    document.querySelector('iframe').src = "https://maps.google.com/maps?q=" + encodeURIComponent("<?= htmlspecialchars($store['address']) ?>") + "&t=&z=13&ie=UTF8&iwloc=&output=embed";
                </script>
            </div>
        </div>
    </div>
</section>

<!-- JSON-LD LocalBusiness -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "<?= htmlspecialchars($store['name']) ?>",
  "image": "<?= htmlspecialchars("http://".$_SERVER['HTTP_HOST']."/uploads/".$store['logo']) ?>",
  "@id": "<?= "http://".$_SERVER['HTTP_HOST'] ?>",
  "url": "<?= "http://".$_SERVER['HTTP_HOST'] ?>",
  "telephone": "<?= htmlspecialchars($store['phone']) ?>",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "<?= htmlspecialchars($store['address']) ?>",
    "addressCountry": "NG"
  }
}
</script>

<?php require_once 'includes/footer.php'; ?>
