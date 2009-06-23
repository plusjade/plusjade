-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 20, 2009 at 01:31 AM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `plusjade`
--

-- Set appropriate auto-increment values.
ALTER TABLE pages AUTO_INCREMENT = 6;
ALTER TABLE sites AUTO_INCREMENT = 6;
ALTER TABLE users AUTO_INCREMENT = 6;
--
-- Dumping data for table `contact_types`
--

INSERT INTO `contact_types` (`type_id`, `type`, `desc`) VALUES
(1, 'phone', 'List your phone number.'),
(2, 'email', 'Provide an email contact. Features spam protection and embedded email submit form.'),
(3, 'address', 'List a business address.'),
(4, 'hours', 'List hours of operation/availabilty.'),
(5, 'aim', 'List your aim screenname.'),
(6, 'skype', 'List your skype phone number.'),
(8, 'map', 'Show an interactive map of any address.'),
(9, 'newsletter', 'Enable guests to sign up for your newsletter.');

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'admin', 'Administrative user, has access to everything.');

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`id`, `name`) VALUES
(1, 'base'),
(2, 'nonzero'),
(3, 'misc'),
(4, 'snowflakes'),
(5, 'green_web'),
(6, 'corporate'),
(7, 'intercraft'),
(8, 'beige'),
(9, 'custom');

--
-- Dumping data for table `tools_list`
--

INSERT INTO `tools_list` (`id`, `name`, `protected`, `enabled`, `desc`) VALUES
(1, 'Text', 'no', 'yes', 'Create a block of content anywhere on the page.'),
(2, 'Album', 'no', 'yes', 'Upload and organize images into an album. Display your album in various styles.'),
(3, 'Showroom', 'yes', 'yes', 'Installs a product gallery on this page.'),
(4, 'Slide_Panel', 'no', 'no', 'Create and organize content into "slides" that scroll vertically or horizontally.'),
(5, 'Contact', 'no', 'yes', 'Easy and robust contact page builder.'),
(6, 'Faq', 'no', 'yes', 'Create and manage a Frequently asked Questions repository.'),
(7, 'Calendar', 'yes', 'yes', 'Installs an Event Calendar to organize and display various events associated with your group.'),
(8, 'Navigation', 'no', 'yes', 'Create nested lists for navigation menus, or to highlight relevant content.'),
(9, 'Blog', 'yes', 'yes', 'Installs a blogging engine on this page.');
