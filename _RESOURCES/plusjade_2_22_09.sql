-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 22, 2009 at 02:59 PM
-- Server version: 5.1.30
-- PHP Version: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `plusjade`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` mediumint(3) NOT NULL AUTO_INCREMENT,
  `fk_site` smallint(3) unsigned NOT NULL,
  `type` smallint(2) unsigned NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `position` tinyint(2) unsigned NOT NULL,
  `enable` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `fk_site`, `type`, `display_name`, `value`, `position`, `enable`) VALUES
(66, 1, 2, 'Email', 'plusjade@gmail.com', 1, 'yes'),
(2, 1, 1, 'Phone', '626-433-3534', 4, 'no'),
(3, 1, 3, 'Drop in', '709 w Ramona Rd<br> Alhambra, Ca 91803', 5, 'no'),
(4, 1, 4, 'Business Hours', '<span style="font-style: italic;">Call support</span><br><div style="margin-left: 40px;">Monday - Sat: 8am - 8pm<br></div><div style="margin-left: 40px;"> Sunday: Closed</div><p><span style="font-style: italic;">Email support</span><span style="font-weight: bold;"></span><br></p><div style="margin-left: 40px;">Daily response time: within one hour<span style="font-weight: bold;"></span><br></div><div style="margin-left: 40px;"><span style="font-weight: bold;"></span></div><div style="margin-left: 40px;"><br></div>', 3, 'yes'),
(13, 1, 5, 'AIM', 'superjadex12', 2, 'yes'),
(76, 12, 1, 'phone', '626-433-3434', 0, 'yes'),
(77, 12, 2, 'email', 'bob@djshrek.com', 0, 'yes'),
(78, 12, 3, 'address', 'UpInDaClub <br>Santa Fe, NM 93243', 0, 'yes'),
(79, 16, 1, 'phone', ' 626.288.6607', 1, 'yes'),
(80, 16, 2, 'email', 'help@motorcycles-plus.com', 4, 'yes'),
(81, 19, 1, 'mobile', '626 - 433 - 3534', 0, 'yes'),
(82, 19, 2, 'email', 'superjadex12@gmail.com', 0, 'yes'),
(83, 19, 5, 'aim', 'superjadex12', 0, 'yes'),
(84, 1, 8, 'map', '', 0, 'yes'),
(85, 16, 8, 'map', '<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;q=729+E+Garvey+Ave,+Monterey+Park,+Los+Angeles,+California+91755&amp;sll=34.077119,-118.130043&amp;sspn=0.011801,0.01781&amp;g=709+W+Ramona+Rd,+Alhambra,+CA+91803&amp;ie=UTF8&amp;cd=1&amp;geocode=FULBBwId57_1-A&amp;split=0&amp;ll=34.07328,-118.108091&amp;spn=0.047207,0.071239&amp;z=14&amp;iwloc=addr&amp;output=embed&amp;s=AARTsJpITF_ZNK9olySinFQZsaskXOxDfQ"></iframe><br /><small><a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;q=729+E+Garvey+Ave,+Monterey+Park,+Los+Angeles,+California+91755&amp;sll=34.077119,-118.130043&amp;sspn=0.011801,0.01781&amp;g=709+W+Ramona+Rd,+Alhambra,+CA+91803&amp;ie=UTF8&amp;cd=1&amp;geocode=FULBBwId57_1-A&amp;split=0&amp;ll=34.07328,-118.108091&amp;spn=0.047207,0.071239&amp;z=14&amp;iwloc=addr" style="color:#0000FF;text-align:left">View Larger Map</a></small>', 5, 'yes'),
(86, 16, 3, 'address', 'Motorcycles Plus<br>729 E. Garvey Ave.<br>Monterey Park, CA 91755', 2, 'yes'),
(87, 16, 4, 'Business Hours', 'Monday - Saturday: 9am - 6pm<br>Sunday: Please Call', 3, 'yes'),
(90, 3, 3, 'Address', 'Pandaland Drive', 1, 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `contact_types`
--

CREATE TABLE IF NOT EXISTS `contact_types` (
  `type_id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(25) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `contact_types`
--

INSERT INTO `contact_types` (`type_id`, `type`) VALUES
(1, 'phone'),
(2, 'email'),
(3, 'address'),
(4, 'hours'),
(5, 'aim'),
(6, 'skype'),
(7, 'oovoo'),
(8, 'map');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` smallint(3) unsigned NOT NULL,
  `question` varchar(200) NOT NULL,
  `answer` text NOT NULL,
  `position` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=78 ;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `fk_site`, `question`, `answer`, `position`) VALUES
(2, 1, 'How much does your service cost?', '$30.00 per month. You can also pay a year upfront and save $60.00 \n<p>\nA monthly fee allows you to preserver cashflow and is actually the lowest cost of ownership.\n</p>\nThis is because your website is <b>not</b> a one time deal and it needs to be maintained in a professional manner. This ensures the highest ROI.', 2),
(3, 1, 'Where are you located?', 'We are based in Alhambra, a small suburb of Los Angeles, California.', 3),
(73, 1, 'What do you guys do again?', 'In short, our service allows you to create a proactive, results oriented, website for your business. \n\nIt''s simple, fast, and its effectiveness can be tested and thus optimized. \n\nWe give you all the technical tools you need so you can focus on what matters; your business.', 0),
(74, 1, 'Wait, so I can get a customized website for $120.00... why so cheap?', 'Good question. \nI think it is because we are badass!!!', 0),
(66, 12, 'What type of music do you play?', 'Rock and techno, that is all.', 0),
(67, 12, 'What are your rates?', '$1,000 per night', 0),
(72, 16, 'All in ?', 'Maybe ?', 0),
(71, 16, 'one', 'answer to one', 0),
(75, 19, 'Are you cool?', 'Very much so', 0),
(76, 19, 'Do you like techno', 'Exclusively', 0),
(77, 1, 'how are you ?', 'aasdfsadf', 0);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` smallint(5) unsigned NOT NULL,
  `page_id` int(3) NOT NULL,
  `page_name` varchar(25) NOT NULL,
  `display_name` varchar(25) DEFAULT NULL,
  `position` tinyint(2) unsigned NOT NULL DEFAULT '99',
  `group` varchar(25) NOT NULL DEFAULT '1',
  `enable` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `fk_site`, `page_id`, `page_name`, `display_name`, `position`, `group`, `enable`) VALUES
(36, 1, 18, 'faq', 'FAQ', 4, '1', 'no'),
(7, 16, 0, 'home', 'home', 1, '1', 'yes'),
(8, 16, 0, 'product', 'Services', 2, '1', 'yes'),
(9, 16, 0, 'about', 'About', 3, '1', 'yes'),
(40, 1, 24, 'examples', 'Examples', 2, '1', 'yes'),
(39, 1, 23, 'about', 'About', 7, '1', 'yes'),
(13, 12, 8, 'home', 'Home', 0, '1', 'yes'),
(42, 12, 26, 'contact', 'Contact', 2, '1', 'yes'),
(21, 1, 11, 'learn', 'How it Works', 3, '1', 'yes'),
(28, 19, 0, 'water', 'BASIC', 2, '1', 'yes'),
(29, 19, 0, 'home', '', 1, '1', 'yes'),
(30, 1, 13, 'pricing', 'Pricing', 1, '1', 'yes'),
(34, 1, 19, 'home', 'Home', 0, '1', 'yes'),
(35, 1, 20, 'contact', 'Contact', 6, '1', 'yes'),
(41, 16, 0, 'contact', 'Contact', 4, '1', 'yes'),
(44, 20, 0, 'home', 'Home', 0, '1', 'yes'),
(45, 20, 0, 'contact', 'Contact', 1, '1', 'yes'),
(47, 3, 32, 'about', 'About', 2, '1', 'yes'),
(53, 3, 38, 'home', 'Home Sweet Home =)', 1, '1', 'yes'),
(58, 1, 43, 'why', 'Why Us', 5, '1', 'yes'),
(59, 3, 44, 'box', 'Box', 3, '1', 'yes'),
(75, 12, 61, 'appearances', 'Set times', 1, '1', 'yes'),
(81, 38, 67, 'home', 'Home', 0, '1', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`) VALUES
(1, 'contact'),
(2, 'faq'),
(3, 'slide_panel'),
(4, 'reviews'),
(5, 'gallery'),
(6, 'showroom');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` mediumint(3) NOT NULL AUTO_INCREMENT,
  `fk_site` smallint(3) unsigned NOT NULL,
  `page_name` varchar(30) NOT NULL,
  `number` int(1) NOT NULL DEFAULT '1',
  `module` varchar(30) NOT NULL,
  `title` varchar(50) NOT NULL,
  `meta` varchar(50) NOT NULL,
  `top` text NOT NULL,
  `body` text NOT NULL,
  `bottom` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `fk_site`, `page_name`, `number`, `module`, `title`, `meta`, `top`, `body`, `bottom`) VALUES
(3, 16, 'home', 1, '', 'Moto Home Page teee hee', '', '<br>', '<div id="shell"><div style="text-align: left;">\n	</div><div style="text-align: left;" id="headline">San Gabriel Valley motorcycle maintenance, repair, and parts!<br></div>\n		<div style="text-align: left;"><img src="http://moto.localhost.com/data/moto/assets/images/front.jpg" id="frontpic">\n</div></div>', '<br>'),
(4, 16, 'about', 1, '', 'reviews sample page for moto', '', '', 'yippity skippity this is my reviews page in simple page mode', ''),
(6, 16, 'product', 1, '', 'Motorcycles Plus Waterglass Services', 'services for motorcycles in the san gabriel valley', '', 'This is the waterglass page <br><br><h1><span style="font-family: trebuchet ms;">Tee hee!</span></h1>', ''),
(26, 12, 'contact', 1, '', '', '', '', 'To edit this page, login and click "edit page"!', ''),
(24, 1, 'examples', 1, 'showroom', '', '', '<div style="margin-left: 40px;">Here is a quick sample of some of our client accounts we currently manage.<br>We are still in development mode so we are focusing on customer satisfaction rather than customer acquisition. If these sites look like something you could use, just give us a call!<br></div>', 'To edit this page, login and click "edit page"!', ''),
(8, 12, 'home', 1, '', '', '', '', 'Hi, welcome to your source for antique furniture in the greater San Gabriel Valley.<br><br>We have all those one of a kind pieces that make your room complete.<br><br>Stop by today!<br>', ''),
(11, 1, 'learn', 1, '', '+Jade breakdown of our website structure', '+Jade breakdown of our website structure', '<br>', 'First we establish our goals for your website:<br><br><h3 style="color: rgb(0, 153, 0); margin-left: 40px;">1. \n<span style="text-decoration: underline;">Connect with New Customers</span></h3>\n<ul style="margin-left: 40px;"><li><span style="font-weight: bold;">Market </span>\nyour products and services. <br>Clearly and concisely convey the benefits of your product or service.<br><br></li>\n<li><span style="font-weight: bold;">Get Found.<br></span>Increase exposure to your business. Then make it painfully easy and convenient for people to find you. List company location, areas of service, business hours, useful history, company goals, etc.<br><br></li>\n<li><span style="font-weight: bold;">Encourage action.</span><br>Exposure must be directed to action.<br>Do I want my customers to call me, email, or stop by our store?<br>Lead the customer to a specific action, then provide a simple and easy way to carry out that action:<br>Clear and easy to read:<br>Important contact phone numbers and email addresses.<br>Address with linkable map and driving directions. <br>Easy forms, mailing list signup, etc.<br>\n</li></ul><h3 style="color: rgb(0, 153, 0); margin-left: 40px;">2. <span style="text-decoration: underline;">Enhance Customer Relationships</span></h3><ul style="margin-left: 40px;">\n<li><span style="font-weight: bold;">Streamline </span>channels of communication. <br>Provide a directed method for customers to communicate with your business.<br>Do you prefer email, telephone, chat?<br><br></li>\n<li><span style="font-weight: bold;">Assist </span>clients better by providing useful content for self and community based help.<br>Frequently asked questions, tutorials, useful product info, all help your customers get the answers they need while reducing strain on your customer service department.<br><br></li><li>\n<span style="font-weight: bold;">Establish </span>your reputation by building your content base online and encouraging opt-in customer reviews and testimonials. People trust external reviews and referrals much more than isolated sales pitches.<br></li></ul><h3 style="color: rgb(0, 153, 0); margin-left: 40px;">\n3. <span style="text-decoration: underline;">Grow</span></h3><div style="margin-left: 40px;"><br></div>\n<div style="margin-left: 80px;">As your business reputation grows online, \nyou will be able to connect with more new customers, and build stronger relationships\n with current ones. The more happy customers you have, the better your business does.<br></div><br><h2>How We Do This:</h2><div style="margin-left: 40px;">We have studied tons of sites and they all fall back to a basic core structure.<br>We encorporate this 6 page core structure into your site.<br>You can also add as many other pages as you like later.<br><br><span style="font-weight: bold;">Homepage</span><br><br></div><div style="margin-left: 80px;">Main sales pitch. Who are you, what your product/service does and why<br>it benefits me (the viewer).  Visuals are crucial. That''''s why we have image galleries, slideshow modules and other crazy spotlight tools.<br></div><div style="margin-left: 40px;"><br><span style="font-weight: bold;">Product/Services page</span><br><br></div><div style="margin-left: 80px;">Further information about your product/service.<br></div><div style="margin-left: 40px;"><br><span style="font-weight: bold;">Reviews</span><br><br></div><div style="margin-left: 80px;">Customers value and trust  3rd party sources more than they do your pitch. It is therefore crucial to enlist this trust building tool. Secondly, reviews both internally on your site, and on external sites help “get the word out” about your company. More content on the web = more exposure.<br></div><div style="margin-left: 40px;"><br><span style="font-weight: bold;">Faq</span><br><br></div><div style="margin-left: 80px;">Give current and new customers the facts they need, efficiently and without any interaction on your part.<br></div><div style="margin-left: 40px;"><br><span style="font-weight: bold;">About</span><br><br></div><div style="margin-left: 80px;">Doing business with a complete stranger is awkward to say the least. <br>Your business identity can not afford to be faceless, so give it face and make it affable!<br></div><div style="margin-left: 40px;"><br><span style="font-weight: bold;">Contact</span><br><br></div><div style="margin-left: 80px;">Provide users with a clear CALL TO ACTION, which will almost always be contacting you in some form.<br>Email, phone, chat, in person, or via sales agent, these methods need to be optimized for the least amount of friction possible.<br><br></div><div style="margin-left: 40px;"><br><br>Once the structure is in place, our marketing and analytics tools provide you the means to grow your exposure to a consistent level. This is no magic potion, you must make a commitment to marketing and growing your site. <br>At +Jade all your technical needs are taken care of. Your time is spent on getting customers, and managing your business, not learning how to be a computer scientist. <br></div><br><br><h2>The Team</h2><div style="margin-left: 40px;">There are tons of web companies out there, but we are in the business of saving you time and resources. If you want something simple that just works and will grow. Consider joining our team.<br><br></div><h1 style="text-align: center; text-decoration: underline; margin-left: 40px;">Benefits of Joining Our Team</h1><div style="margin-left: 40px;"><br></div><h3 style="margin-left: 40px;">Stop the Hassle – Just Get it Done!</h3><div style="margin-left: 80px;">Custom solutions take tons of man-hours to implement, and of course you pay for it. But most initial business needs are pretty direct and relatively similiar. We eliminate uncertainty and procrastination and you get a testable working version done and implemented.<br></div><div style="margin-left: 40px;"><br></div><h3 style="margin-left: 40px;">Drastically Lower Your Costs:</h3><div style="margin-left: 80px;">There is a clear, time tested, and structured way to building a site that gets you the exposure you need. It has worked for others, it can work for you. <br>If you centralize these needs and set up a team dedicated to creating solutions, you get a system that hurdles the prices per user crashing downward. <br></div><div style="margin-left: 40px;"><br></div><h3 style="margin-left: 40px;">Centralize Management:</h3><div style="margin-left: 80px;">With +Jade you don''''t have to know anything about tech. <br>We host your website, we manage your code-base, we continually test and optimize your resources, and we UPDATE your tools so your site never gets old. <br>Your time is thus spent on things that actually produce results: getting  customers! <br></div><div style="margin-left: 40px;"><br></div><h3 style="margin-left: 40px;">Centralize Knowledge:</h3><p style="margin-left: 80px;">+Jade loves the internet. Can you believe that even we lose our minds trying to learn the latest in: <br></p><div style="margin-left: 80px;"><ul><li>Coding practices</li><li>Server management</li><li>Web interface and usability</li><li>Web marketing and internet based campaigning</li><li>Web testing and analytics</li><li>Web code and content optimization </li><li>Internet techniques and strategies for... everything.<br></li></ul>The internet is constantly evolving.<br>I am pretty sure you can spot an “old” site from a site that looks “fresh, alive, and active”. You might not know exactly why that is, but it still hits you spot on.<br><br>With +Jade you get centralized proactive knowledge that you can use RIGHT NOW to accomplish your goals.<br></div><br><br>\n', '<br>'),
(12, 19, 'water', 1, '', 'Water and all its miracles', 'Research paper on water ', '', '<h3>Start Simple</h3>Is your business new to the Internet? Do you have more questions than answers?<br>+Jade can help:<br><br>We build and manage websites. And websites are about people.<br>A website helps your business connect with new customers and build loyalty with existing ones.<br><br>1. <span style="text-decoration: underline;">Connect with New Customers</span><br><ul><li><span style="font-weight: bold;">Market </span>your products and services.<br>What''s so good about you!?<br></li><li><span style="font-weight: bold;">Get found:</span> company location, areas of service, business hours,company history, goals, etc<br></li><li><span style="font-weight: bold;">Encourage action:</span> phone number, email, driving directions, sales rep, mailing list. Forms, surveys.<br></li></ul>2. <span style="text-decoration: underline;">Enhance Customer Relationships</span><br><ul><li><span style="font-weight: bold;">Streamline </span>channels of communication. Email, telephone, chat support.</li><li><span style="font-weight: bold;">Assist </span>clients using helpful faq or knowledge base of information.</li><li><span style="font-weight: bold;">Establish </span>your reputation using an opt-in reviews system.<br></li></ul>3. <span style="text-decoration: underline;">Grow</span><br><br><div style="margin-left: 40px;">As\nyour business reputation grows online, you will be able to connect with\nmore new customers, and build stronger relationships with current ones.\nThe more happy customers you have, the better your business does.<br></div><br>Sound like something you need? That is +Jade.<br>Very simple, but crucial to building a sustainable, longterm reputation for your business.<br><br><h3>Start Simple</h3>Is your business new to the Internet? Do you have more questions than answers?<br>+Jade can help:<br><br>We build and manage websites. And websites are about people.<br>A website helps your business connect with new customers and build loyalty with existing ones.<br><br>1. <span style="text-decoration: underline;">Connect with New Customers</span><br><ul><li><span style="font-weight: bold;">Market </span>your products and services.<br>What''s so good about you!?<br></li><li><span style="font-weight: bold;">Get found:</span> company location, areas of service, business hours,company history, goals, etc<br></li><li><span style="font-weight: bold;">Encourage action:</span> phone number, email, driving directions, sales rep, mailing list. Forms, surveys.<br></li></ul>2. <span style="text-decoration: underline;">Enhance Customer Relationships</span><br><ul><li><span style="font-weight: bold;">Streamline </span>channels of communication. Email, telephone, chat support.</li><li><span style="font-weight: bold;">Assist </span>clients using helpful faq or knowledge base of information.</li><li><span style="font-weight: bold;">Establish </span>your reputation using an opt-in reviews system.<br></li></ul>3. <span style="text-decoration: underline;">Grow</span><br><br><div style="margin-left: 40px;">As\nyour business reputation grows online, you will be able to connect with\nmore new customers, and build stronger relationships with current ones.\nThe more happy customers you have, the better your business does.<br></div><br>Sound like something you need? That is +Jade.<br>Very simple, but crucial to building a sustainable, longterm reputation for your business. <br> ', ''),
(13, 1, 'pricing', 1, '', '+Jade - Breakdown of website service package', 'website hosting service package from +Jade', '<span style="font-weight: bold;">Why is this like this ?<br></span>', '<span style="font-weight: bold;"><br></span><h3><span style="font-weight: bold;">Custom Website Design and Page Creation.</span></h3><h3><span style="font-weight: bold;"></span></h3><span style="font-weight: bold;"><br></span><div style="margin-left: 40px;">Your website is hand designed to your custom specifications. <br>We also personally configure your pages with your content. <br>Includes a consultation for discussing main business content and images. <br>We provide copy and image editing/optimization. This is needed to ensure your content clearly and concisely conveys your business message.  <br>Page creation includes the 6 core page modules. Extra pages can be implemented at any time for a small fee. <br><br>Pricing for this service ranges from $200 - $500 depending on design and content complexity. <br>This is a one-time setup fee. If at any time you decide to cancel your hosting and management services (see below) with +Jade, your website and its content will still belong to you. <br>You can easily export a full static rendition of your website and all its pages.<br><br>At this early stage this is our only option. Shortly we will provide page editing and creation tools to our users. This will allow you to edit and configure your pages yourself from within your browser window. There will be no extra cost for self-editing.<br><br></div><span style="font-weight: bold;"></span><br><h3>Full Website Hosting, Management, and Optimization Services.</h3><ul><li>Full website hosting (link describing what hosting is)</li><li>Step-by-step plan of action tutorials and documention for marketing your website and collecting and analyzing data.</li><li>Web analytics dashboard used to track and measure your data.</li><li>User interface for responding to data by changing elements of your site.</li><li>A/B split testing platform. Easily create 2 different pages and test them against eachother to see which interests/compels users more effectively.</li><li>Progress reports, for showing how much you have grown and steps to improve.<br><br>Team -<br><br></li><li>Continual codebase refinement and optimization. </li><li>Continual rollout of website functions and toolsets. (available to all users)</li><li>Up to date technology - your website never gets old. Always roll out latest practices.</li><li>Knowledge base - Proactive tutorials regarding things that produce results for you. <br>(Not learning how to be a computer scientist)<br></li></ul><div style="margin-left: 40px;"><br><span style="font-weight: bold;">Price:</span> $12.00 monthly<br><br>OR $108 per year if paid upfront. A 25% cost savings or $9.00 monthly.<br><br><br><br></div>', '<h2><span style="color: rgb(153, 0, 51);"><br></span></h2>'),
(38, 3, 'home', 1, '', '', '', '', 'Hello Jello !!', ''),
(14, 19, 'home', 1, 'faq', '', '', '<h1 style="text-align: center;">Are you gonna save the day ?</h1>', '<h3>Be Effective</h3><h1>GET THE PEOPLE!</h1>+Jade employs a different, but very logical approach to your website needs. <br>Most companies focus on web <span style="font-style: italic; font-weight: bold;">design</span>,\ngetting your site to look exactly how you want and then upselling\nvarious extended functionality such as shopping cart software,\necommerce, forums, social networks, etc.<br><br>But the fact is your\nwebsite is about people. Having tons of tools integrated into your\nwebsite still does not address the need for people to actually come and\nuse these awesome tools.<br><br>+Jade is about getting the people first. <br>Getting people first is not only a better use of your resources, it is also much cheaper and more results oriented.<br><br>Web design firms cost a lot. Designers create designs from scratch and charge hefty per hour fees.<br>They have much longer lead times.<br><br>We\ndon''t doubt rich user functionality and great web design is useful, but\nwhy put the cart before the horse? Why spend $5,000 on tools with\nnobody to use them? (yet!)  <br><br>Our plan of action is to give you a plan of action. ', '<div style="text-align: center;"><h1>As soon as we can!</h1></div>'),
(23, 1, 'about', 1, 'slide_panel', '+Jade managed website hosting | About', '', '<form action="http://panda.localhost.com" method="post">\n<input name="panda" value="love" type="hidden">\n<input type="submit"></form>', 'Tee hee!', ''),
(18, 1, 'faq', 1, 'faq', '+Jade Frequently asked questions', '+Jade Frequently asked questions', '<br>', ' ', '<br>'),
(19, 1, 'home', 1, 'slide_panel', '+Jade managed website hosting for small businesses', '+Jade managed website hosting for small businesses', '<h1 style="font-weight: normal; text-align: center;">STOP putting your website plans on hold!<br></h1>\n<div style="text-align: center;">Get an effective website up and running for your business by day''s end, guaranteed!<br><br>No web experience? No problem! </div>', '<h3>Create</h3>\n<ul>\n<li>Create a fully custom theme or choose a free template.<br><br>\n</li><li>Easily create, edit, and manage up to 100 pages (soft limit).<br>Specify page name, title, content, and meta data.<br><br>\n</li><li>Use our 6 core page modules to optimize marketability.<br><br>\n<ul>\n<li>Home page\n</li><li>Product(s) page \n</li><li>Reviews/Testimonials \n</li><li>Frequently Asked Questions\n</li><li>About\n</li><li>Contact<br></li></ul><br>\n</li><li>Drop advanced features into any page such as photo galleries, widgets, etc.<br><br>\n</li><li>Inject custom javascript or other code into any page.<br></li></ul><br>\n<h3>Market</h3>\n<ul>\n<li>Receive support and consultation on how to properly market your website and how to organically grow your user base from scratch. We provide tips and tutorials for various marketing campaigns.<br><br>\n</li><li>All code is search engine optimized and contains semantic markup. Pages contain a high concentration of content rather than code.<br></li></ul>\n<h3>Analyze </h3>\n<ul>\n<li>Easily enable Google or Clicky analytics trackers to measure hits to your site.<br><br>\n</li><li>We tell you how to use the data to improve user response.<br></li></ul><br>\n<h3>Grow</h3>\n<ul>\n<li>Measure and respond to user data, make adjustments, add content, and your site exposure will grow.<br><br>\n</li><li>+Jade is continually working on new tools and features to add to your site. You grow as we grow.</li></ul>', '<div style="text-align: center;" jquery1234417452080="89">\n<h2>Take the Tour!</h2>\n<h2>Sign up!<br></h2>=)<br><br>skippy!<br></div>'),
(20, 1, 'contact', 1, 'contact', 'Contact +Jade', '', '<h5>Thank you for contacting us! =D<br></h5><br>', 'Who am I !!!??', ''),
(25, 16, 'contact', 1, 'contact', '', '', '<br>', 'To edit this page, login and click "edit page"!', '<br>'),
(28, 20, 'home', 1, '', 'Sample Home page', '', '', 'Hello Jello !', ''),
(29, 20, 'contact', 1, 'gallery', '', '', '', 'To edit this page, login and click "edit page"!', ''),
(32, 3, 'about', 1, 'contact', '', '', '<h2>Please contact me in the form below, thanks!</h2>', 'To edit this page, login and click "edit page"!', ''),
(43, 1, 'why', 1, '', 'Core Benefits for choosing +Jade to solve your bus', 'Core Benefits for choosing +Jade to solve your bus', '<div style="text-align: center;"><h1>It''s a roller coaster kinda rush =)</h1></div>', '<h2>"Not all businesses have <span style="font-style: italic; font-weight: bold; text-decoration: underline;">effective </span>websites."</h2>This is the core problem +Jade is trying to provide a solution for.<br><br>You may not have any website at all, or you may have had a website done years ago that sits there and does nothing. You may have a website that does stuff but has no visitors. <br><br>If you can identify with or know someone with this problem let me tell you why +Jade is the best solution for the small business owner.<br><br>In order to set up a proper competitive evalution we must establish a baseline for what a proposed "ideal" solution should be. <br><br><span style="font-weight: bold;">Here at +Jade our ideal solution is this:</span><br><br>Make the process of <span style="font-weight: bold; font-style: italic;">creating </span>and <span style="font-style: italic; font-weight: bold;">managing </span>a website non-technical. <br>Not everyone wants to be tech nerds like us, but businesses can all benefit from technology.<br><br><span style="font-weight: bold;">Get to the point. </span><br>It is fun to think about how beautiful your site will be and pick out color palettes but we both know the bottom line is your site has to yield results. <br><br>Websites are marketing tools. They do things. And as technology gets better, they should do more and more. <br><br><span style="font-weight: bold;">Be affordable; save time and money.</span><br><br>Sure we can code you an exact working replica of amazon.com to sell all your goods, but we''ll need a lot of time and whole lot of money to do it. <br><br>How about a solution that can be used RIGHT NOW, for a ridiculously low entry price, but can be scaled as you grow.  Why pay $7,000 for a completely custom dooodaa website when you don''t have any visitors (yet)?<br>We feel it quite frequently too big of a risk for most businesses to take, to invest in a project they do not fully understand and without any proven userbase.<br><br>Most web firms want to sell you that big ticket package (for obvious reasons).<br>So long as you have the cahs, you get the fancy site, and the visitors will follow (we hope). We call that the top-down approach. <br><br>Our approach is bottom-up. Leverage your capital, focus time and resources where they count - getting customers, then grow into fanciness!<br><br><br><br><br><br><br><br><br>Your needs are specific, but standard.<br><br><br>Standard needs should not cost "custom" money.<br><br>Websites are marketing tools.<br><br>Websites get old.<br><br><br><br> ', '<div style="text-align: center;"><h1>What up!</h1></div>'),
(44, 3, 'box', 1, 'slide_panel', 'Smello', 'Jello', '<h1>Hello</h1>', 'To edit this page go to "Pages &amp; Menu"', '<h1>Smello !</h1>'),
(61, 12, 'appearances', 1, '', 'My current dj schedule', 'blah', 'All the set times I have going on sucka!', '<h1>=&gt;</h1>', ''),
(67, 38, 'home', 1, '', '', '', '', '<p><h2>My Home Page!</h2></p>', '');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` mediumint(3) NOT NULL AUTO_INCREMENT,
  `fk_site` smallint(3) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `primary` text NOT NULL,
  `secondary` text NOT NULL,
  `position` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `fk_site`, `title`, `primary`, `secondary`, `position`) VALUES
(1, 1, 'What a website can do for you.', 'A website accomplishes some very basic, yet very essential business needs:\r\n<p></p>\r\n<h3>Communicate company and product benefits.</h3>\r\n<div>\r\nWebsites allow your business to use rich multimedia assets  (images and video) to present your product in a tangible way that makes clear sense to your customers.  What you sell and how it benefits your customers is the most important and essential piece of information about your company.  You can now broadcast this message unlimitedly, in an extremely cost-effective manner, with a potential world market reach.\r\n</div>\r\n		\r\n<h3>Connectivity.</h3>\r\n<div>\r\nA website allows anyone access to your business 24 hours a day, 7 days a week. When you create an easy way for clients to connect to your business, you make it easier for these clients to do business with you. Think of your website as a collaboration tool. How would a constant connection to your customers benefit you? What would it do for your marketing campaigns?  Furthermore how would it benefit your clients (and in turn improve customer satisfaction and loyalty) if there was always a convenient, easy, and effective way to reach you with questions, concerns, referrals, feedback, and more business!\r\n</div>\r\n\r\n<h3>Testing and analysis.</h3>\r\n<div>\r\nA properly deployed website will allow you to gather data about your customers and your products without any effort on your part.  An improved connection  to your customers will give you insight on their likes and dislikes. It will key you into what is working and what isn''t. Your customers have money waiting for you. What better way to earn it then by asking them what they need!  \r\nYou will also be able to track the number of visitors coming to your site, how they are getting there, and from where they come. You will know how long they stayed and on which page they left. Are you trying to gain market share in a new city or state? A website will record and present demographic data beautifully! \r\nDo you know how well that local newspaper ad is performing? Use your website to record how many people actually saw and acted on your ad. \r\n</div>\r\n\r\n<h3>Development</h3>\r\n<div>\r\nYour website is and should be responsible for the development of your business. The internet is  growing with more and more users getting online every day. Your website NEEDS to provide the tools necessary to grow your business. Technology will get better, new concepts and strategies will inevitably develop. Trust in a professional team to act as your guide and to make sure your business is equipped with the right strategy and the right tools. \r\n</div>', '', 0),
(2, 1, 'What +Jade can do for you.', '	+Jade does not create custom one-of-a-kind websites for its clients. \r\n	By not creating custom one-off websites, deploy juicy is able to promise its clients these benefits:\r\n<ul>\r\n<li>A website that never gets old.</li>\r\n<li>A website that is professionally maintained, secured, and optimized \r\nby a dedicated team of tech professionals for the life of your account.</li>\r\n<li>The lowest cost of ownership on the planet.</li>\r\n<li>A proactive and quantifiable approach to website ROI.</li>\r\n<li>The power and yield of a collective network that benefits when you benefit.</li>\r\n</ul>\r\n\r\n', '', 0),
(3, 1, 'Does a custom site work for you?', '<p>\r\nBefore you consider any web vendor that charges a big one time "package fee" for custom creating a website consider these precautions:\r\n</p>\r\n\r\n<h3>Higher cost</h3>\r\n<div>\r\nThrow the word "custom" into any product, and the price goes up dramatically. On the internet, when it comes to businesses specifically, the costs greatly outweigh the benefits. The reason is not many web firms take business needs into account and specifically optimize your website for them. The firms that do acknowledge these needs enlist fees that are too steep for most small businesses just getting started online.\r\n</div>\r\n\r\n<h3>Longer lead time.</h3>\r\n<div>\r\nYou''ll need to talk to a project manager that will ask you tons of questions about your goals for the website, the color scheme, style, layout, whether you want e-commerce, SEO, blogging, CMS, front-end/back-end management, tech support, email campaigns, user logins, all while being billed for their time! \r\n</div>\r\n\r\n<h3>Knowledge not included.</h3>\r\n<div>\r\nFrom our experiences, most businesses looking to create a website know little to nothing about the web world, (which is why they are researching professional companies in the first place). We think it is just plain bad business to sell a client a product they do not fully understand. There is also little incentive for a company who already has your money, to continue to provide top notch service for the life of your business.\r\n</div>\r\n\r\n<h3>Is it a proven system?</h3>\r\n<div>\r\nWhile it''s nice to get things tailor made, we have realized that too many times a customer DOES NOT KNOW what they want, and why should they; we are supposed to be the experts! There are many variables involved online, and it would be wrong to make customers blindly construct their own path.\r\n<p>\r\nDeploy Juicy takes our job seriously and as such, we are committed to offering our clients <b>what works</b>, what is proven, and setting them down a well established path to growing and optimizing your business online.\r\n</p>\r\nOur vision has always been to establish and grow fruitful relationships with our clients, \r\n and grow as a team.\r\n</div>\r\n\r\n<h3>Who will maintain your website?</h3>\r\n<div>\r\nPerhaps the biggest reason deploy juicy first came about was because we were amazed at how web companies could leave so many websites abandoned with no one to care for them. Only to seep into uselessness, because their owners were not equipped with the tools to maintain them. \r\n<p>\r\nYour website WILL get old, and stop being effective if you do not maintain it. \r\nWebsites are not magic miracle marketing potions. A website is a tool that is only as useful as the commitment\r\nand effort you put into it. \r\n</p>\r\nWhen you sign up for the deploy Juicy service, you are acquiring a dedicated team that will professionally upkeep, manage, optimize, and develop your website for as long as our relationship exists.\r\nThe core system that runs your website is managed by us on a daily basis. You will never have to work with code,\r\nyet you''ll always have the freshest most optimized version. \r\nYou must know by now how much we love the internet, it''s our lives, we dream about it, we go to the ends of the earth consuming all the latest concepts.... all so you don''t have to...\r\n</div>\r\n\r\n<h3>Will the site accomplish your goals?</h3>\r\n<div>\r\nThe reason we work specifically with businesses is because there exist a core of necessary tools that all business websites NEED if they want a tangible ROI. We know this because we are a business, and we use these same tools. We have done the research and in actuality all major, successful websites do in fact employ the same main functionalities. \r\nSo before a company persuades you into why you need a pretty blog, or a $10,000 e-commerce engine, why not start off with an extremely low cost alternative to get your feet wet and have the option to grow as your confidence grows?  We understand ROI so all your test data is there for you to help you decide for yourself.\r\n</div>\r\n\r\n<h3>The desire to be different.</h3>\r\n<div>\r\nAdhering to a scientifically proven and analyzable system does not mean your site will be bland and cookie cutter. In fact, we commission professional web design experts to create drop in templates with unlimited color variations. Our design experts don''t simply let their artistry run wild, they must also take into consideration usability issues, and marketing principles.\r\nDo not be tempted into assuming "custom" is inherently better. Realize that a systematic team approach to any project usually yields the most beneficial, and most quantifiable results.\r\nWe consider ourselves along with all our clients, one big inter-related team. All improvements and feature additions and upgrades will always be instantly available to our clients, forever! Websites were not made to be static; embrace the benefits of having deploy Juicy on your team!\r\n</div>', '', 0),
(5, 3, 'Cookies', 'MMMm Tasty cookies!', '', 0),
(6, 3, 'Cupcakes', 'The best cheesecake cupcakes u ever had!', '', 0),
(7, 3, 'Brownies', 'Can you get any better?', '', 0),
(8, 3, 'Pumpkin Pie', 'Tasty!!', '', 0),
(9, 2, 'Company uniforms and branding', 'Yeah why not huh!', '', 0),
(10, 2, 'Business retail and merchandising', 'Look slick sucka!', '', 0),
(11, 2, 'School apparel', 'asfdasfsafsfs\r\nsafasf\r\nasfs\r\nf', '', 0),
(12, 2, 'sdfasdfasfd', 'adfasdfafas', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'admin', 'Administrative user, has access to everything.');

-- --------------------------------------------------------

--
-- Table structure for table `roles_users`
--

CREATE TABLE IF NOT EXISTS `roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles_users`
--

INSERT INTO `roles_users` (`user_id`, `role_id`) VALUES
(1, 1),
(6, 1),
(9, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `showroom`
--

CREATE TABLE IF NOT EXISTS `showroom` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(3) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `price` float unsigned NOT NULL,
  `image` varchar(18) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `showroom`
--

INSERT INTO `showroom` (`id`, `fk_site`, `name`, `body`, `price`, `image`) VALUES
(1, 1, 'http://plusjade.com', 'Plusjade (this site) is entirely created and run by our system. <br>', 500, '1233651188.jpg'),
(2, 1, 'http://motorcycles-plus.com', 'Motorcycle shop needed simple website to convey shop hours, rates, services, and preferred contact method. <div><br class="webkit-block-placeholder"></div><div>Makes use of the gallery module for sweet effect</div>', 200, '1233651256.jpg'),
(3, 1, 'http://webemb.com', 'Embroidery shop was looking into getting a professional website done. Too many solutions and not enough time to sit down and talk out his take-over-the-world plans ; we finally convinced the owner to just get something up already! Webemb is now in our system currently getting made!<br>', 45, '1233651385.jpg'),
(4, 1, 'http://pasadena-furniture.com', 'Local furniture store just added to the system today. This is a good example of how easy it is to port a free theme over to our system. Thanks to the great guys at <a target="" title="" href="http://www.freecsstemplates.org/">http://www.freecsstemplates.org/</a> the furniture company did not have to pay a cent toward design costs and can now worry about gathering content for their pages and of course: marketing!<br>', 4456, '1233651275.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE IF NOT EXISTS `sites` (
  `site_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(25) NOT NULL,
  `domain` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `banner` varchar(50) NOT NULL,
  `theme` varchar(50) NOT NULL,
  PRIMARY KEY (`site_id`),
  KEY `url` (`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`site_id`, `url`, `domain`, `name`, `address`, `phone`, `email`, `banner`, `theme`) VALUES
(1, 'jade', 'plusjade.com', '', '', '626-433-3534', '', 'plus_jade.jpg', 'redcross'),
(2, 'webemb', 'webemb.com', 'Dexterious Industries', '709 w Ramona Rd<br>Alhambra, Ca 91803', '626-433-3534', 'sales@webemb.com', 'logo_black.png', 'redcross'),
(3, 'panda', 'basegroups.com', 'PandaLand', '1115 MountBatten ave<br>Glendale, Ca 91207', '818-219-1494', 'Panda@pandaland.com', 'cute_panda.jpg', 'redcross'),
(12, 'bob', 'pasadena-furniture.com', 'What it do', 'Hip hop Alley<br> Westide, Ca', '123-555-3434', 'djboby@sucka.com', 'logo_deploy.png', 'nonzero'),
(16, 'moto', 'motorcycles-plus.com', '', '729 e. Garvey Ave.  Montery Park, Ca 91755', '626.288.6607', 'service@motorycles-plus.com', '1233375317_ban.png', 'custom'),
(19, 'basic', 'blockbasic.com', 'basic', 'waterPolo', '626-433-3534', '', '1232757176_ban.png', 'custom'),
(20, 'dad', '', 'dad', '', '', '', '', 'misc'),
(38, 'pinky', '', '', '', '', '', '', 'redcross');

-- --------------------------------------------------------

--
-- Table structure for table `slide_panels`
--

CREATE TABLE IF NOT EXISTS `slide_panels` (
  `id` mediumint(3) NOT NULL AUTO_INCREMENT,
  `fk_site` smallint(3) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `position` tinyint(1) unsigned NOT NULL,
  `page` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `slide_panels`
--

INSERT INTO `slide_panels` (`id`, `fk_site`, `title`, `body`, `position`, `page`) VALUES
(1, 1, '1. Create', '<h2>1. Create and Customize Your Website.</h2>\n<div class="image_placeholder"></div>\n<span style="font-weight: bold;">Design -</span>\n<br><br>\n<div style="margin-left: 40px;">Choose from one of our many quick-start themes and be on your way in no time, or\n\n<span style="font-weight: bold;"></span> use our theme panel to easily customize and edit the markup and css.<br><br>A professionally designed custom theme can be easily uploaded to your account to give you a truly custom professional look <span style="font-style: italic; font-weight: bold;">without</span> the outrageous custom programming price.<br></div><span style="font-weight: bold;"><br></span><span style="font-weight: bold;">Content -</span><br><br><div style="margin-left: 40px;">Each site automatically loads 6 core pages commonly found in the best of business websites:<br></div><ul style="margin-left: 40px;"><li>Home page</li><li>Product(s) page </li><li>Reviews/Testimonials </li><li>Frequently Asked Questions</li><li>About</li><li>Contact</li></ul>Every account is a full fledged hosted website. We manage all the technology so you can focus on content and ... your customers! =D<br>\n', 1, 19),
(2, 1, '2. Market', '<h2><span style="font-weight: bold;">2. Market Your Website.</span></h2>\n<div class="image_placeholder"></div>Follow step-by-step tutorials on various kinds of internet marketing and growing your userbase from the ground up.<br><br>Marketing and promotion are the most crucial part of creating a website.<br>Otherwise what is the point?<br><div style="margin-left: 40px;"><br></div>', 2, 19),
(3, 1, '3. Analyze', '<h2><span style="font-weight: bold;">3. Track and Analyze Data.</span></h2><div class="image_placeholder"></div>Seamless integration with both free and premium web analytics trackers including google, clicky, and crazyegg. <br><br>We show you how to use this data to build better pages, launch a/b split tests, and organically optimize your website. Properly reading and responding to data is crucial to growing your business presence online.', 3, 19),
(4, 1, '4. Grow', '<h2><span style="font-weight: bold;">4. Respond and Grow.</span></h2><div class="image_placeholder"></div>As a business, you know the importance of getting down to business. Your website needs to produce an ROI. <br><br>Create your content -&gt; <br>Market your site -&gt; <br>Respond to data -&gt;<br>Grow your customer base!<br><br>Sure it takes work and planning. <br>Join our team and instantly eliminate all your technology obstacles,<br>Oh, and we have the plan too, so let''s get started...', 4, 19),
(9, 2, 'Business', 'Custom branded apparel to give your business the professional look it deserves. \nWe offer all the top brands in a wide range of colors. Give us your logo, wait 10 days,\nand enjoy!', 0, 0),
(10, 2, 'Retail', 'Personal solutions and consultations to launch a retail clothing campaign.\nWhether you are an established business or planning your first launch, we offer personal experience-backed solutions at reasonable prices, and even better quality offerings.', 0, 0),
(11, 2, 'Branding', 'Promotional products meet high end. We offer unique solutions to keeping your companies high value image, while reasonably giving away promotional branding products.', 0, 0),
(16, 2, 'Schools', 'Custom school wear for senior packages, and hoodies galore. We specialize in unique custom applications of tackle twill and college style hoodies.\n<p>\nWe also incorporate mixed media such printed designs over embroidery for a truly one-of-a-kind finish.\n</p>', 0, 0),
(22, 16, 'benefit', '<div id="shell">\n	<div id="headline">San Gabriel Valley motorcycle maintenance, repair, and parts!</div>\n		\n</div>', 0, 0),
(23, 16, 'TEE HEE!', 'adfasfd<br>', 0, 0),
(24, 19, 'one benefit', 'yippy<br>', 0, 0),
(25, 19, 'two benefit', 'skippy<br>', 0, 0),
(27, 1, 'hgfjf', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_site_id` int(3) NOT NULL,
  `email` varchar(127) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` char(50) NOT NULL,
  `token` varchar(32) NOT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `client_site_id`, `email`, `username`, `password`, `token`, `logins`, `last_login`) VALUES
(1, 1, 'jade@jade.com', 'jade', '62a69de60a2d1bb13bf2edf2bd976870b72597985683325617', 'bvAkj6hoXDQrly9c9UTFZh6IthD7NuGs', 245, 1235292402),
(6, 3, 'panda@panda.com', 'panda', 'c02579ffc93034639d51302de51929d5eaaf7500919956aa7f', 'LzY1HCX22CnOy04uKiRzcN0wokbDsKJk', 24, 1235180402),
(9, 38, 'pinky@pinky.com', 'pinky', 'bd87948b911056bef5c9b803ceae2b01e79418f1907cd2e6fd', 'UGm5XB6trg8UBtytYh6JVT7T2n5OzGon', 3, 1235183072);

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_agent` varchar(40) NOT NULL,
  `token` varchar(32) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `user_tokens`
--

INSERT INTO `user_tokens` (`id`, `user_id`, `user_agent`, `token`, `created`, `expires`) VALUES
(1, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'Ard3ZedHRWZkVZyPrJk23ohgVLLjjmQZ', 1235108458, 1236318058),
(2, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'IXD96hWMVuNZn2XyMQePSLAN4RdXfENR', 1235109079, 1236318679),
(3, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'ug1SEzcpRcV3F1IgEHiiZq9cMhNFadGz', 1235110024, 1236319624),
(4, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'ikMBwWl7PTN8RRue60k4DwUEZZEWxVsQ', 1235113085, 1236322685),
(5, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'MvlBwkSsVb0xZ4b0A7s2NShT0gDg2gzf', 1235113365, 1236322965),
(6, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'KBImnJiZnAg994cOfpwbJrjFrDQ0dpzL', 1235115219, 1236324819),
(8, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'VH6SzhPGN3AELfaqVAdLcOO7DW1b1tOB', 1235115448, 1236325048),
(10, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'fR5ALtJxHNo6Te0WTLHRO9UDU5cyNAmO', 1235115598, 1236325198),
(13, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'tXL9lIWGc6yiPXtznoD4AODwgDNgApVK', 1235116800, 1236326400),
(14, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '9ApVERWuwebRuwAv3VtCfARjw4Wy0Ojm', 1235117589, 1236327189),
(15, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'Iq6SX70WRkDOrr6zCTv3wqojlfOtu0ve', 1235169987, 1236379587),
(16, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'fryOwWjqro9MHaFzapfDGLSL5kt5yvVc', 1235173403, 1236383003),
(17, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'nBk1qzgy16Qd6ONWdfsvYgQYsICzsU2x', 1235176498, 1236386098),
(18, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '9x3ujjQGh2WEyDG4SXLsb1kDQ3eORg8z', 1235177176, 1236386776),
(19, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '8uIhzUEl1Bb1m64SP9JPQsu4EzI5juU2', 1235177243, 1236386843),
(20, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'bIyf50Mu6TBNd0GeluWW1eiyWBkXtSRT', 1235177405, 1236387005),
(21, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'an8lKDcbIVfpkZW24rmSWAqiCqlrx8la', 1235179546, 1236389146),
(22, 6, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'Vmhx7yy8pHmm5QlxReA8ud4jO24Cz8s6', 1235180109, 1236389709),
(23, 6, '18442aeab9cd632a5946133c9e268c6e253938d2', 'GIEcqo7AYVGRA4roranV9USFkFIW08vn', 1235180399, 1236389999),
(24, 9, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'fZtNzb7mdY6QvHxhsT2UK0vrpVnp8GmR', 1235183068, 1236392668),
(25, 1, '933721a538996ca3a6f9e3ef80481beadc1cea92', 'ymd1j3rt1S3uHoTuVg3wUwb0Hc2Ha1zr', 1235292284, 1236501884),
(26, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'HDFCwO1O8pWTwh3sjWUNfEkkDOs01Il6', 1235292399, 1236501999);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `roles_users`
--
ALTER TABLE `roles_users`
  ADD CONSTRAINT `roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
