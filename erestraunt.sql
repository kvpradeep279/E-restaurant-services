-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 08:50 PM
-- Server version: 8.0.39
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erestraunt`
--
CREATE DATABASE IF NOT EXISTS `erestraunt` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `erestraunt`;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `added_at`, `status`) VALUES
(4, 1, '2024-11-30 17:24:02', 1),
(5, 1, '2024-11-30 17:24:21', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_id` int NOT NULL,
  `food_id` int NOT NULL,
  `qty` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_id`, `food_id`, `qty`) VALUES
(4, 2, 5),
(5, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `food_id` int NOT NULL,
  `food_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `price` float NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(25) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`food_id`, `food_name`, `price`, `url`, `category`) VALUES
(1, 'Plain Dosa', 89, '../images/plaindosa.jpg', 'Tiffins'),
(2, 'Masala Dosa', 119, '../images/masladosa.jpg', 'Tiffins'),
(3, 'Egg Dosa', 149, '../images/eggdosa.webp', 'Tiffins'),
(4, 'Idly', 79, '../images/idily.jpg', 'Tiffins'),
(5, 'Puri', 190, '../images/puri.jpg', 'Tiffins'),
(6, 'Mysore Bonda', 99, '../images/onda.jpg', 'Tiffins'),
(7, 'Upma', 79, '../images/upma.jpg', 'Tiffins'),
(8, 'Chitti Gaare', 119, '../images/vada.jpg', 'Tiffins'),
(9, 'Plain White Rice', 179, '../images/whiterice.webp', 'Main Course'),
(10, 'Veg Fried Rice', 190, '../images/VegFreidRice.webp', 'Main Course'),
(11, 'Panner Biriyani', 279, '../images/PannerBiryani.jpg', 'Main Course'),
(12, 'Kaaju Biriyani', 289, '../images/KaajuBiryani.webp', 'Main Course'),
(13, 'Mushroom Biriyani', 289, '../images/MushroomBiryani.webp', 'Main Course'),
(14, 'Egg Biriyani', 299, '../images/egg-biryani.jpg', 'Main Course'),
(15, 'Chicken Dum Biriyani', 339, '../images/chickendumbiryani.webp', 'Main Course'),
(16, 'Chicken Fry Piece Biriyani', 399, '../images/chickenfrypeiceBiyani.webp', 'Main Course'),
(17, 'Muttom Dum Biriyani', 399, '../images/Mutton-Biryani-Recipe.jpg', 'Main Course'),
(18, 'Sambar Rice', 259, '../images/sambar-rice-recipe.webp', 'Main Course'),
(19, 'Curd Rice', 239, '../images/curd rice.jpg', 'Main Course'),
(20, 'Paneer Tikka', 229, '../images/paneer-tikka.webp', 'Starters'),
(21, 'Chilli Paneer', 229, '../images/chillipaneer.jpg', 'Starters'),
(22, 'Aloo Tikki', 179, '../images/aloo-tikki.jpg', 'Starters'),
(23, 'Spring Rolls', 199, '../images/springrolls.jpg', 'Starters'),
(24, 'Crsipy Corn', 219, '../images/crispycorn.jpg', 'Starters'),
(25, 'Chilli Chicken', 299, '../images/chillichicken.jpg', 'Starters'),
(26, 'Chicken Majestic', 309, '../images/chickenmajestic.jpg', 'Starters'),
(27, 'Chicken Kabab', 289, '../images/Chicken-Kebab.jpg', 'Starters'),
(28, 'Fish Fry', 339, '../images/fishfry.jpg', 'Starters'),
(29, 'Mutton Ghee Rost', 399, '../images/muttongheeroast.jpg', 'Starters'),
(30, 'Prawns BBQ', 349, '../images/prawnsbbq.jpg', 'Starters'),
(31, 'Mixed Vegetable Curry', 190, '../images/mixedveg.webp', 'Curries'),
(32, 'Kaju Curry', 279, '../images/kajucurry.webp', 'Curries'),
(33, 'Methi Chaman', 279, '../images/methichaman,jpg', 'Curries'),
(34, 'Paneer Butter Masala', 279, '../images/paneerbuttermasala.jpg', 'Curries'),
(35, 'Palak Paneer', 279, '../images/palak-paneer.webp', 'Curries'),
(36, 'Kaju Paneer', 279, '../images/kajupaneer.webp', 'Curries'),
(37, 'Mushroom', 279, '../images/mushroom.webp', 'Curries'),
(38, 'Egg Curry', 279, '../images/eggcurry.webp', 'Curries'),
(39, 'Chicken Masala', 279, '../images/chickenmasala.webp', 'Curries'),
(40, 'Butter Chicken', 279, '../images/butterchicken.webp', 'Curries'),
(41, 'Murgh Masala', 279, '../images/murghmasala.jpg', 'Curries'),
(42, 'Mutton Kheema', 279, '../images/muttonkeema.webp', 'Curries'),
(43, 'Mutton Raghi Josh', 279, '../images/muttonragujosh.jpg', 'Curries'),
(44, 'Pulka', 39, '../images/fishcurry.jpg', 'Breads And Naans'),
(45, 'Roti', 39, '../images/roti.jpg', 'Breads And Naans'),
(46, 'Tawa Roti', 49, '../images/tawaroti.jpg', 'Breads And Naans'),
(47, 'Butter Roti', 59, '../images/butterroti.jpg', 'Breads And Naans'),
(48, 'Plain Naan', 49, '../images/plainnaan.jpg', 'Breads And Naans'),
(49, 'Butter Naan', 69, '../images/butternaan.jpg', 'Breads And Naans'),
(50, 'Garlic Naan', 69, '../images/garlicnaan.jpg', 'Breads And Naans'),
(51, 'Tea', 90, '../images/tea.jpg', 'Beverages'),
(52, 'Coffee', 99, '../images/coffee.jpg', 'Beverages'),
(53, 'Cold Coffee', 149, '../images/coldcoffee.jpg', 'Beverages'),
(54, 'Iced Tea', 169, '../images/icedtea.jpg', 'Beverages'),
(55, 'Black Coffee', 149, '../images/blackcoffee.jpg', 'Beverages'),
(56, 'Thumbs Up', 79, '../images/thumbsup.jpg', 'Beverages'),
(57, 'Sprite', 79, '../images/sprite.jpg', 'Beverages'),
(58, 'Coco-Cola', 79, '../images/cococola.jpg', 'Beverages'),
(59, 'Vanilla-Shake', 139, '../images/vanillashake.jpg', 'Beverages'),
(60, 'Chocolate Shake', 139, '../images/chocolateshake.jpg', 'Beverages'),
(61, 'Banana Shake', 109, '../images/banana.jpg', 'Beverages');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `cart_id` int NOT NULL,
  `order_status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `cart_id`, `order_status`) VALUES
(2, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(25) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `name`) VALUES
(1, 'kolla', 'kolla', 'Kolla'),
(1216, 'suri11', '11111', 'surya raju'),
(1217, 'rags', '2222', 'raghav');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD KEY `fk_cart` (`cart_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`food_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `usernme` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `food_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1218;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
