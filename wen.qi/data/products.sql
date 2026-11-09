-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 01, 2026 at 06:51 PM
-- Server version: 8.0.46-0ubuntu0.24.04.4
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `far_and_found`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `craft` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `material` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `finish` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Natural',
  `price` decimal(8,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'img/placeholder.png',
  `stock` int UNSIGNED NOT NULL DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `images` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `origin`, `country`, `category`, `craft`, `material`, `finish`, `price`, `description`, `image`, `stock`, `featured`, `created_at`, `images`) VALUES
(1, 'Handwoven Market Basket', 'Fujian, China', 'China', 'Baskets & Storage', 'Hand Weaving', 'Bamboo', 'Natural', 68.00, 'Woven from locally grown bamboo, each basket takes two days to shape by hand.', 'img/products/1.jpg', 14, 1, '2026-11-01 18:42:40', 'img/products/1.jpg,img/products/1-2.jpg,img/products/1-3.jpg'),
(2, 'Three Sisters Vessels', 'Oaxaca, Mexico', 'Mexico', 'Home Decor', 'Ceramics', 'Ceramic', 'Natural clay', 124.00, 'A trio of hand-built clay vessels, fired in the open air in the valleys of Oaxaca.', 'img/products/2.jpg', 6, 1, '2026-11-01 18:42:40', 'img/products/2.jpg,img/products/2-2.jpg,img/products/2-3.jpg'),
(3, 'Sandstone Serving Bowl', 'Java, Indonesia', 'Indonesia', 'Kitchen & Dining', 'Stonework', 'Stone', 'Natural', 52.00, 'Carved from a single block of sandstone and finished by hand.', 'img/products/3.jpg', 9, 1, '2026-11-01 18:42:40', 'img/products/3.jpg,img/products/3-2.jpg,img/products/3-3.jpg'),
(4, 'Sun Weave Wall Tray', 'Kigali, Rwanda', 'Rwanda', 'Home Decor', 'Coil Weaving', 'Natural Fiber', 'Natural', 86.00, 'Coiled from natural fibers in a pattern passed down through generations of weavers.', 'img/products/4.jpg', 5, 1, '2026-11-01 18:42:40', 'img/products/4.jpg,img/products/4-2.jpg,img/products/4-3.jpg'),
(5, 'Cloudline Ceramic Vase', 'Seto, Japan', 'Japan', 'Home Decor', 'Ceramics', 'Ceramic', 'Crackle glaze', 98.00, 'Wheel-thrown in Seto, with a soft crackle glaze that is different on every piece.', 'img/products/5.jpg', 7, 0, '2026-11-01 18:42:40', 'img/products/5.jpg,img/products/5-2.jpg,img/products/5-3.jpg'),
(6, 'Palm Fiber Carryall', 'Marrakesh, Morocco', 'Morocco', 'Baskets & Storage', 'Hand Weaving', 'Natural Fiber', 'Natural', 74.00, 'Woven from palm fiber and finished with leather handles.', 'img/products/6.jpg', 11, 0, '2026-11-01 18:42:40', 'img/products/6.jpg,img/products/6-2.jpg,img/products/6-3.jpg'),
(7, 'Alpaca Wool Throw', 'Cusco, Peru', 'Peru', 'Textiles & Wearables', 'Loom Weaving', 'Alpaca / Wool', 'Undyed', 138.00, 'Spun from alpaca fleece and woven on a foot loom in the Andean highlands. Warm, light and soft.', 'img/products/7.jpg', 4, 1, '2026-11-01 18:42:40', 'img/products/7.jpg,img/products/7-2.jpg,img/products/7-3.jpg'),
(8, 'Olive Wood Spoon Set', 'Tunis, Tunisia', 'Tunisia', 'Kitchen & Dining', 'Wood Carving', 'Wood', 'Oiled', 42.00, 'Three spoons carved from a single olive branch and rubbed with food-safe oil.', 'img/products/8.jpg', 20, 0, '2026-11-01 18:42:40', 'img/products/8.jpg,img/products/8-2.jpg,img/products/8-3.jpg'),
(9, 'Indigo Table Runner', 'Tokushima, Japan', 'Japan', 'Textiles & Wearables', 'Indigo Dyeing', 'Textile', 'Indigo', 64.00, 'Hand-dyed in a fermented indigo vat, so no two runners share the same shade of blue.', 'img/products/9.jpg', 8, 0, '2026-11-01 18:42:40', 'img/products/9.jpg,img/products/9-2.jpg,img/products/9-3.jpg'),
(10, 'Bat Trang Tea Bowl Pair', 'Bat Trang, Vietnam', 'Vietnam', 'Kitchen & Dining', 'Ceramics', 'Ceramic', 'Glazed', 56.00, 'A pair of small tea bowls from the village famed for its ceramics for over 500 years.', 'img/products/10.jpg', 12, 0, '2026-11-01 18:42:40', 'img/products/10.jpg,img/products/10-2.jpg,img/products/10-3.jpg'),
(11, 'Bamboo Steamer Set', 'Chiang Mai, Thailand', 'Thailand', 'Kitchen & Dining', 'Bamboo Weaving', 'Bamboo', 'Natural', 38.00, 'Two stackable steamer baskets and a lid, woven tightly from split bamboo.', 'img/products/11.jpg', 25, 0, '2026-11-01 18:42:40', 'img/products/11.jpg,img/products/11-2.jpg,img/products/11-3.jpg'),
(12, 'Kuba Cloth Cushion Cover', 'Kasai, DR Congo', 'DR Congo', 'Home Decor', 'Raffia Weaving', 'Textile', 'Natural', 59.00, 'Raffia cloth with a geometric pattern appliqued by hand, made into a cushion cover.', 'img/products/12.jpg', 10, 0, '2026-11-01 18:42:40', 'img/products/12.jpg,img/products/12-2.jpg,img/products/12-3.jpg'),
(13, 'Mango Wood Platter', 'Jaipur, India', 'India', 'Kitchen & Dining', 'Wood Carving', 'Wood', 'Oiled', 78.00, 'A wide serving platter carved from sustainably harvested mango wood.', 'img/products/13.jpg', 9, 0, '2026-11-01 18:42:40', 'img/products/13.jpg,img/products/13-2.jpg,img/products/13-3.jpg'),
(14, 'Felted Wool Coaster Set', 'Bishkek, Kyrgyzstan', 'Kyrgyzstan', 'Home Decor', 'Wool Felting', 'Alpaca / Wool', 'Natural', 34.00, 'Four coasters made from wool felted by hand, using a technique shaped by nomadic life.', 'img/products/14.jpg', 30, 0, '2026-11-01 18:42:40', 'img/products/14.jpg,img/products/14-2.jpg,img/products/14-3.jpg'),
(15, 'Raffia Storage Basket', 'Antananarivo, Madagascar', 'Madagascar', 'Baskets & Storage', 'Hand Weaving', 'Natural Fiber', 'Natural', 58.00, 'A lidded basket woven from raffia palm, sized for blankets and everyday clutter.', 'img/products/15.jpg', 0, 0, '2026-11-01 18:42:40', 'img/products/15.jpg,img/products/15-2.jpg,img/products/15-3.jpg'),
(16, 'Terracotta Planter', 'Impruneta, Italy', 'Italy', 'Home Decor', 'Ceramics', 'Ceramic', 'Terracotta', 72.00, 'Frost-resistant terracotta, thrown and fired by Tuscan potters who have made planters for centuries.', 'img/products/16.jpg', 6, 0, '2026-11-01 18:42:40', 'img/products/16.jpg,img/products/16-2.jpg,img/products/16-3.jpg'),
(17, 'Kantha Stitched Quilt', 'Dhaka, Bangladesh', 'Bangladesh', 'Textiles & Wearables', 'Embroidery', 'Textile', 'Hand-stitched', 168.00, 'Layers of reclaimed cotton joined with fine running stitches, each quilt taking weeks to finish.', 'img/products/17.jpg', 3, 1, '2026-11-01 18:42:40', 'img/products/17.jpg,img/products/17-2.jpg,img/products/17-3.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
