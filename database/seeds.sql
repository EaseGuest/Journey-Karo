-- Journey Karo — Seed Data
-- Run after schema.sql
-- Admin default: username journeykaro_admin — run database/set-admin-password.php on server for Admin@2025

USE `journeykaro_db`;

-- Admin user (change password via database/set-admin-password.php after import)
INSERT INTO `users` (`username`, `email`, `password`, `name`, `role`, `status`) VALUES
('journeykaro_admin', 'admin@journeykaro.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'Journey Karo Admin', 'admin', 'active')
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

-- Settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('site_tagline', 'Explore Gujarat with Local Experts', 'general'),
('contact_phone', '9586605635', 'contact'),
('contact_email', 'booking@journeykaro.com', 'contact'),
('contact_address', 'Near Science Center, Bhuj, Gujarat 370001', 'contact'),
('whatsapp_number', '919586605635', 'contact'),
('ga4_measurement_id', '', 'analytics'),
('gsc_verification', '', 'analytics')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);

-- Destinations (7)
INSERT INTO `destinations` (`name`,`slug`,`icon`,`short_description`,`description`,`attractions`,`highlights`,`best_time`,`climate`,`duration_label`,`starting_price`,`featured_image`,`is_featured`,`sort_order`,`status`,`meta_title`,`meta_description`) VALUES
('Bhuj','bhuj','🏜️','Gateway to Kutch heritage and the White Rann.','Bhuj is the cultural heart of Kutch — heritage palaces, handicraft villages, and access to the magical White Rann of Kutch.','["Prag Mahal","Aina Mahal","Bhujia Fort","Handicraft Villages"]','["Heritage Walks","Local Cuisine","Craft Shopping","Rann Access"]','Oct–Mar','Mild','3N/4D',12999,'https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=800&q=80',1,1,'active','Bhuj Tour Packages | Rann of Kutch Gateway | Journey Karo','Explore Bhuj with Journey Karo — heritage sights, craft villages, and White Rann tours from Bhuj office.'),
('White Rann of Kutch','white-rann-of-kutch','🤍','The surreal white salt desert under moonlight.','The White Rann is a vast salt marsh that transforms into a dreamscape during Rann Utsav and full moon nights.','["White Rann","Rann Utsav","Full Moon Night","Handicraft Market"]','["Tent Stay","Camel Ride","Cultural Music","Photography"]','Nov–Feb','Cool','3N/4D',15999,'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',1,2,'active','White Rann of Kutch Tour | Rann Utsav Packages | Journey Karo','Book White Rann of Kutch packages — tent stays, full moon experiences, and Rann Utsav tours with local experts.'),
('Dwarka','dwarka','🛕','Sacred coastal city — one of the Char Dham.','Dwarka is one of India\'s holiest cities with the ancient Dwarkadhish temple and Beyt Dwarka island.','["Dwarkadhish Temple","Beyt Dwarka","Nageshwar Jyotirlinga","Rukmini Temple"]','["Temple Darshan","Coastal Drive","Island Ferry","Spiritual Guide"]','Oct–Mar','Warm','2N/3D',9499,'https://images.unsplash.com/photo-1605649487212-47bdab064df7?auto=format&fit=crop&w=800&q=80',1,3,'active','Dwarka Tour Package | Pilgrimage Gujarat | Journey Karo','Dwarka devotion tours — temple darshan, Beyt Dwarka, and coastal Gujarat with Journey Karo.'),
('Somnath','somnath','🕌','First among the twelve Jyotirlingas.','Somnath temple on the Arabian Sea coast is a pilgrimage of immense spiritual significance.','["Somnath Temple","Triveni Ghat","Bhalka Teerth","Light & Sound Show"]','["Jyotirlinga Darshan","Evening Aarti","Coastal Views","Pilgrimage Support"]','All Year','Warm','1N/2D',6999,'https://images.unsplash.com/photo-1581799764979-dcf3fbc9f52e?auto=format&fit=crop&w=800&q=80',1,4,'active','Somnath Tour Package | Jyotirlinga Gujarat | Journey Karo','Somnath pilgrimage packages with darshan arrangements and coastal Gujarat tours.'),
('Gir National Park','gir','🦁','Home of the last Asiatic lions.','Gir is India\'s only home for Asiatic lions — premium wildlife safaris and bird watching.','["Jeep Safari","Asiatic Lions","Bird Watching","Crocodile Centre"]','["Wildlife Guide","Lodge Stay","Morning Safari","Nature Walks"]','Nov–Jun','Warm','2N/3D',14500,'https://images.unsplash.com/photo-1615959189197-48400be37e26?auto=format&fit=crop&w=800&q=80',1,5,'active','Gir Safari Package | Asiatic Lion Tour | Journey Karo','Gir National Park safari packages — jeep safaris, lodges, and wildlife experts from Bhuj.'),
('Diu','diu','🏖️','Portuguese heritage and pristine beaches.','Diu offers palm-fringed beaches, forts, and a relaxed island atmosphere near Gujarat.','["Nagoa Beach","Diu Fort","St. Paul Church","Sea Shell Museum"]','["Beach Holiday","Water Sports","Seafood","Heritage Walk"]','Oct–Mar','Warm','2N/3D',12499,'https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?auto=format&fit=crop&w=800&q=80',1,6,'active','Diu Tour Package | Beach Holiday Gujarat | Journey Karo','Diu island beach packages — forts, Nagoa Beach, and coastal getaways with Journey Karo.'),
('Statue of Unity','statue-of-unity','🗽','World\'s tallest statue at 182 metres.','The Statue of Unity at Kevadia includes observation deck, Valley of Flowers, and laser show.','["Statue of Unity","Observation Deck","Valley of Flowers","Sardar Sarovar Dam"]','["Laser Show","Jungle Safari","Boat Ride","Museum Visit"]','All Year','Warm','1N/2D',10999,'https://images.unsplash.com/photo-1627856013091-fed6e4e30025?auto=format&fit=crop&w=800&q=80',1,7,'active','Statue of Unity Tour | Kevadia Package | Journey Karo','Statue of Unity visitor packages — tickets, transfers, and Gujarat tour add-ons from Journey Karo.')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Packages (linked to destinations)
INSERT INTO `packages` (`destination_id`,`name`,`slug`,`short_description`,`category`,`days`,`nights`,`price`,`rating`,`review_count`,`featured_image`,`inclusions`,`exclusions`,`is_featured`,`sort_order`,`status`,`meta_title`,`meta_description`) VALUES
((SELECT id FROM destinations WHERE slug='white-rann-of-kutch'),'White Desert Safari','white-desert-safari','Experience the White Rann with luxury tents and cultural shows.','Popular',4,3,12999,4.9,212,'https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=800&q=80','["Tent Stay","Cultural Show","Camel Ride","Village Tour"]','["Flights","Personal expenses"]',1,1,'active','White Desert Safari Package | Rann of Kutch | Journey Karo','4D/3N White Rann safari with tent stay, camel rides, and Kutch culture.'),
((SELECT id FROM destinations WHERE slug='gir'),'Asiatic Lion Trail','asiatic-lion-trail','Two Gir jungle safaris with expert naturalists.','Premium',3,2,14500,4.8,187,'https://images.unsplash.com/photo-1615959189197-48400be37e26?auto=format&fit=crop&w=800&q=80','["Jeep Safari","Wildlife Guide","Lodge Stay","Bird Watch"]','["Camera fees"]',1,2,'active','Gir Lion Safari Package | Asiatic Lion Trail | Journey Karo','Gir National Park lion safari package with lodge and expert guide.'),
((SELECT id FROM destinations WHERE slug='dwarka'),'Dwarka Devotion Tour','dwarka-devotion-tour','Complete Dwarka pilgrimage with temple darshan.','Spiritual',3,2,9499,4.9,256,'https://images.unsplash.com/photo-1605649487212-47bdab064df7?auto=format&fit=crop&w=800&q=80','["Temple Darshan","Beyt Dwarka","Nageshwar","Coastal Drive"]','["Flight tickets"]',1,3,'active','Dwarka Devotion Tour Package | Journey Karo','Dwarka temple tour with Beyt Dwarka and Nageshwar Jyotirlinga.'),
((SELECT id FROM destinations WHERE slug='somnath'),'Somnath Special Package','somnath-special','Quick Somnath Jyotirlinga pilgrimage.','Value',2,1,6999,4.7,143,'https://images.unsplash.com/photo-1581799764979-dcf3fbc9f52e?auto=format&fit=crop&w=800&q=80','["Light & Sound Show","Ghat Puja","Temple Tour"]','["Meals on travel days"]',0,4,'active','Somnath Special Package | Journey Karo','2D/1N Somnath pilgrimage with evening aarti and light show.'),
((SELECT id FROM destinations WHERE slug='diu'),'Diu Coastal Escape','diu-coastal-escape','Beach holiday with Portuguese heritage.','Popular',3,2,12499,4.8,178,'https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?auto=format&fit=crop&w=800&q=80','["Nagoa Beach","Diu Fort","Water Sports","Seafood Dinner"]','["Alcohol"]',1,5,'active','Diu Coastal Escape Package | Journey Karo','Diu beach package with fort visits and water sports.'),
((SELECT id FROM destinations WHERE slug='statue-of-unity'),'Statue of Unity Tour','statue-of-unity-tour','Full day Statue of Unity experience.','Iconic',2,1,10999,4.6,198,'https://images.unsplash.com/photo-1627856013091-fed6e4e30025?auto=format&fit=crop&w=800&q=80','["Observation Deck","Valley of Flowers","Laser Show","Dam Visit"]','["Entry tickets if changed"]',1,6,'active','Statue of Unity Tour Package | Journey Karo','Statue of Unity day tour with observation deck and laser show.'),
((SELECT id FROM destinations WHERE slug='bhuj'),'Culture of Kutch','culture-of-kutch','Handicraft villages and Kutchi heritage.','Cultural',4,3,8750,4.7,134,'https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&w=800&q=80','["Artisan Workshops","Ajrakh Prints","Rogan Art","Bhunga Hut"]','["Shopping purchases"]',0,7,'active','Culture of Kutch Package | Bhuj Tour | Journey Karo','Kutch craft village tour from Bhuj with artisan workshops.'),
((SELECT id FROM destinations WHERE slug='bhuj'),'Gujarat Grand Circuit','gujarat-grand-circuit','Ultimate 9-day Gujarat circuit.','Best Value',9,8,34999,5.0,98,'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=800&q=80','["Rann Safari","Lion Safari","Pilgrimage","Beach Holiday"]','["International flights"]',1,8,'active','Gujarat Grand Circuit 9 Days | Journey Karo','9D/8N Gujarat grand tour — Rann, Gir, Dwarka, Somnath, Diu, and more.')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Sample itinerary (White Desert Safari)
INSERT INTO `itineraries` (`package_id`,`day_number`,`title`,`description`,`activities`,`meals`,`sort_order`) VALUES
((SELECT id FROM packages WHERE slug='white-desert-safari'),1,'Arrival Bhuj & Heritage','Arrive at Bhuj, hotel check-in, visit Prag Mahal and Aina Mahal. Sunset at Bhujia Fort.','["Airport pick-up","Prag Mahal","Aina Mahal","Bhujia Fort sunset"]','Dinner',1),
((SELECT id FROM packages WHERE slug='white-desert-safari'),2,'White Rann Experience','Drive to White Rann, tent check-in, camel ride, cultural programme.','["White Rann visit","Camel ride","Cultural show"]','Breakfast, Dinner',2),
((SELECT id FROM packages WHERE slug='white-desert-safari'),3,'Kalo Dungar & Villages','Highest point in Kutch, handicraft villages, return to Bhuj.','["Kalo Dungar","Gandhi Nu Gam","Craft demo"]','Breakfast, Lunch',3),
((SELECT id FROM packages WHERE slug='white-desert-safari'),4,'Departure','Breakfast and transfer to Bhuj airport or station.','["Hotel checkout","Airport drop"]','Breakfast',4)
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- Blogs
INSERT INTO `blogs` (`title`,`slug`,`excerpt`,`content`,`featured_image`,`author_name`,`category`,`read_time_minutes`,`status`,`published_at`,`meta_title`,`meta_description`,`is_featured`) VALUES
('The Ultimate Guide to Rann of Kutch 2025','rann-of-kutch-guide-2025','Everything you need to know about visiting the White Rann.','<p>The White Rann of Kutch is one of India\'s most surreal landscapes. Best visited between November and February during Rann Utsav...</p>','https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=800&q=80','Rajesh Vaghasiya','Travel Guides',15,'published','2025-01-15 10:00:00','Rann of Kutch Guide 2025 | Journey Karo Blog','Complete guide to White Rann — best time, stays, and tours.',1),
('Top 10 Tips for Your First Gir Lion Safari','top-10-gir-safari-tips','Maximize your chances of spotting Asiatic lions.','<p>Gir National Park is the only place to see Asiatic lions in the wild. Book morning safaris, wear neutral colours...</p>','https://images.unsplash.com/photo-1615959189197-48400be37e26?auto=format&fit=crop&w=800&q=80','Mehul Raval','Wildlife',10,'published','2025-01-08 10:00:00','Gir Safari Tips | Journey Karo','Expert tips for your first Gir lion safari in Gujarat.',1),
('Complete Dwarka Pilgrimage Guide','dwarka-pilgrimage-complete','Temples, rituals, and travel tips for Dwarka.','<p>Dwarka is one of the Char Dham. Plan your darshan timings, Beyt Dwarka ferry, and nearby Nageshwar...</p>','https://images.unsplash.com/photo-1605649487212-47bdab064df7?auto=format&fit=crop&w=800&q=80','Priya Joshi','Pilgrimage',12,'published','2024-12-20 10:00:00','Dwarka Pilgrimage Guide | Journey Karo','Dwarka temple guide for pilgrims visiting Gujarat.',0)
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- Reviews
INSERT INTO `reviews` (`reviewer_name`,`reviewer_email`,`rating`,`destination`,`review_text`,`status`,`is_featured`,`reviewer_image`) VALUES
('Meera Patel','meera@example.com',5,'Rann of Kutch','Our trip to Rann of Kutch was absolutely magical. Journey Karo arranged everything perfectly from airport pick-up to luxury tent stay.','approved',1,'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=100&h=100&q=80'),
('Rajesh Shah','rajesh@example.com',5,'Dwarka & Somnath','Highly recommend their car rental and pilgrimage packages. Driver was safe and knew all the best stops.','approved',1,'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&h=100&q=80'),
('Dr. Ananya Rao','ananya@example.com',5,'Gir National Park','Gir safari was breathtaking. They rescheduled without extra charge when our flight was delayed.','approved',1,'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&h=100&q=80')
ON DUPLICATE KEY UPDATE `review_text` = VALUES(`review_text`);

-- Gallery
INSERT INTO `gallery` (`title`,`image_path`,`category`,`destination_id`,`sort_order`,`status`) VALUES
('White Rann Sunset','https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=800&q=80','rann',(SELECT id FROM destinations WHERE slug='white-rann-of-kutch'),1,'active'),
('Asiatic Lion Gir','https://images.unsplash.com/photo-1615959189197-48400be37e26?auto=format&fit=crop&w=800&q=80','wildlife',(SELECT id FROM destinations WHERE slug='gir'),2,'active'),
('Dwarkadhish Temple','https://images.unsplash.com/photo-1605649487212-47bdab064df7?auto=format&fit=crop&w=800&q=80','temples',(SELECT id FROM destinations WHERE slug='dwarka'),3,'active'),
('Nagoa Beach Diu','https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?auto=format&fit=crop&w=800&q=80','beaches',(SELECT id FROM destinations WHERE slug='diu'),4,'active'),
('Statue of Unity','https://images.unsplash.com/photo-1627856013091-fed6e4e30025?auto=format&fit=crop&w=800&q=80','monuments',(SELECT id FROM destinations WHERE slug='statue-of-unity'),5,'active')
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- SEO meta (pages)
INSERT INTO `seo_meta` (`page_slug`,`meta_title`,`meta_description`,`meta_keywords`,`og_image`) VALUES
('home','Journey Karo | Gujarat Tour Packages, Hotels & Car Rental Bhuj','Explore Gujarat with Journey Karo — Rann of Kutch, Dwarka, Somnath, Gir, Diu & Statue of Unity. Based in Bhuj.','Journey Karo, Gujarat tour, Bhuj travel, Rann of Kutch','https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=1200&q=80'),
('about','About Journey Karo | Gujarat Travel Experts Bhuj','Meet Journey Karo — local Gujarat travel experts based in Bhuj since day one.','Journey Karo about, Bhuj travel agency',''),
('destinations','Gujarat Destinations | Bhuj, Dwarka, Gir, Diu | Journey Karo','Discover 7 iconic Gujarat destinations with expert local guides.','Gujarat destinations, Bhuj, Dwarka, Gir',''),
('packages','Gujarat Tour Packages | Journey Karo','Curated Gujarat tour packages with transparent pricing.','Gujarat tour packages, Rann package',''),
('services','Travel Services | Hotels, Cars, Flights | Journey Karo','Tour packages, hotel booking, car rental, and flight assistance in Gujarat.','hotel booking Gujarat, car rental Bhuj',''),
('blog','Gujarat Travel Blog | Journey Karo','Travel guides, tips, and stories from Gujarat.','Gujarat travel blog',''),
('gallery','Photo Gallery | Gujarat Travel | Journey Karo','Stunning photos from Rann, Gir, Dwarka, Diu and more.','Gujarat photos',''),
('reviews','Customer Reviews | Journey Karo','5-star reviews from happy Gujarat travelers.','Journey Karo reviews',''),
('faq','FAQ | Journey Karo Gujarat Tours','Answers to common questions about booking Gujarat tours.','Gujarat tour FAQ',''),
('contact','Contact Journey Karo | Bhuj +91 9586605635','Contact our Bhuj office for tour bookings and custom plans.','contact Journey Karo Bhuj',''),
('custom-tour-planner','Custom Tour Planner | Design Your Gujarat Trip | Journey Karo','Free custom itinerary planning for your Gujarat adventure.','custom Gujarat tour','')
ON DUPLICATE KEY UPDATE `meta_title` = VALUES(`meta_title`);
