# Client E-Commerce Platform

A production-ready PHP & MySQL e-commerce store optimized for a Nigerian audience, built with pure PHP and custom CSS (no heavy framework dependencies like bootstrap) to support standard cPanel shared hosting seamlessly. Features a functional WhatsApp cart management implementation.

## Features
- **Client-Side Cart:** Fast Add to Cart flow with LocalStorage.
- **WhatsApp Checkout:** Construct and forward fully populated checkout texts directly to the seller's WhatsApp.
- **Micro-Animations & Premium UI:** Crafted custom CSS UI.
- **Full Admin Dashboard:** Robust backend to manage products, handle images, update settings, handle dynamic store info, and process order status updates.
- **Dynamic SEO:** Fully populated tags including structured JSON-LD format markup and XML sitemaps.

## Installation / Setup

1. **Upload Files:** Upload this directory to your cPanel `public_html` or Apache `htdocs` folder.
2. **Setup Database:**
   - Create a MySQL Database and note the credentials.
   - Using phpMyAdmin or similar, import the `install/schema.sql` to lay the core tables.
   - Import `install/seed.sql` for demo products and base settings.
3. **Configure Connection:**
   - Open `includes/db.php`
   - Update `$host`, `$dbname`, `$username`, `$password` with your Database variables.
4. **Login:**
   - Go to `http://your-site/admin/login.php`
   - Default Username: `admin`
   - Default Password: `password123`
5. **Configure WhatsApp & Info:**
   - Head into Settings from the Admin Dashboard and update WhatsApp routing, Store name, delivery addresses, and Bank accounts.

## Directory Structure
- `/assets`: Contains custom CSS and standalone main JS application files.
- `/admin`: All restricted content dashboards.
- `/includes`: Reusable connection parameters, utility function helpers, and repeating UI modules (header, footer).
- `/install`: Structure and seed records.
- `/uploads`: Dynamically populated storage for store branding and product imagery. Ensure this directory remains writable (`755` permissions).
