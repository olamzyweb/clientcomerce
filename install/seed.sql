INSERT INTO `store_info` (`name`, `phone`, `whatsapp`, `email`, `address`, `delivery_areas`, `bank_name`, `account_number`, `account_name`, `primary_color`) VALUES
('Naija Luxe Store', '+2348000000000', '+2348000000000', 'hello@naijaluxe.com', '12 Admiralty Way, Lekki Phase 1, Lagos', 'Lagos (Island & Mainland), Abuja, Port Harcourt', 'Zenith Bank', '1010101010', 'Naija Luxe Ventures', '#832232');

-- password is 'password123'
INSERT INTO `admin_users` (`username`, `password_hash`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO `categories` (`name`, `slug`) VALUES
('Clothing', 'clothing'),
('Skincare', 'skincare'),
('Shoes', 'shoes'),
('Electronics', 'electronics'),
('Food Items', 'food-items');

INSERT INTO `products` (`name`, `slug`, `description`, `price`, `old_price`, `category_id`, `stock_status`, `featured`) VALUES
('Premium Adire Two-Piece Set', 'premium-adire-two-piece-set', 'Authentic hand-dyed adire fabric tailored into a comfortable and stylish two-piece set. Perfect for owambe or casual outings.', 25000.00, 30000.00, 1, 'in_stock', 1),
('Organic Shea Butter Mix', 'organic-shea-butter-mix', 'Whipped shea butter enriched with essential oils for glowing, moisturized skin. 250g jar.', 5500.00, NULL, 2, 'in_stock', 1),
('Men''s Leather Loafers', 'mens-leather-loafers', 'Handcrafted genuine leather loafers with comfortable soles. Available in sizes 41-45.', 35000.00, 42000.00, 3, 'in_stock', 1),
('Rechargeable Mini Fan', 'rechargeable-mini-fan', 'Beat the heat with this powerful, portable 10000mAh rechargeable fan. Lasts up to 12 hours.', 18000.00, 22000.00, 4, 'in_stock', 0),
('Nigerian Ofada Rice (5kg)', 'nigerian-ofada-rice-5kg', 'Destoned, high-quality local Ofada rice. Unpolished and very healthy.', 12500.00, NULL, 5, 'in_stock', 1),
('Luxury Ankara Midi Dress', 'luxury-ankara-midi-dress', 'Beautiful vibrant Ankara fabric tailored to perfection.', 18000.00, 21000.00, 1, 'in_stock', 0);

INSERT INTO `product_images` (`product_id`, `image_path`, `is_primary`) VALUES
(1, 'demo1.jpg', 1),
(2, 'demo2.jpg', 1),
(3, 'demo3.jpg', 1),
(4, 'demo4.jpg', 1),
(5, 'demo5.jpg', 1),
(6, 'demo6.jpg', 1);

INSERT INTO `settings` (`key_name`, `key_value`) VALUES
('seo_title', 'Naija Luxe Store | Premium Quality Products'),
('seo_description', 'Shop the best clothing, skincare, and food items in Nigeria. Fast delivery nationwide.'),
('currency_symbol', '₦');
