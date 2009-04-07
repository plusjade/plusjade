-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 23, 2009 at 04:21 AM
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
-- Table structure for table `about`
--

CREATE TABLE IF NOT EXISTS `about` (
  `id` mediumint(3) NOT NULL AUTO_INCREMENT,
  `fk_site` smallint(3) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`id`, `fk_site`, `title`, `body`) VALUES
(2, 1, 'About +Jade', 'Our name is +Jade because we needed something simple, short, and easy to spell, say, and remember, as outlined on our <a href="">choosing a domain name</a> documentation.\r\n<p>\r\nThe idea behind +Jade is to signify that our customers gain a team addition to their business. \r\nMy name just happens to be Jade, and though it might sound vain, I went with +Jade because that is quite literally what you get; my hard work, my determination, my effort, and my sweat  to make your company the best it possibly can be by making +Jade the best it can be. \r\n</p>\r\n+Jade is about a team effort, where collective energies are leveraged to benefit everyone.<br>\r\n<b>Your business +Jade.</b>\r\n</p>'),
(3, 0, 'About Page', 'sample about page'),
(4, 2, 'About', 'Founded in 1995 -  Dexterous Industries has a committed value to quality products. <p>\r\nNot just a generic statement Dexterous only sells specific branded products that customers can trust.</p>'),
(5, 3, 'About the Panda', 'The panda is pretty and nice.'),
(9, 10, 'Pinkys About Page', '<div style="text-align: center;"><span style="font-weight: bold;">Tittly winks!!</span><br><br>What it do scooby dooooo!!<br><div style="text-align: left;"><ol><li>Hello jello</li><li>Hello Smello</li><li>hello Butty<br></li></ol></div></div>'),
(10, 12, 'About DjShrek', 'DjShrek started with a dollar and a dream. <br>Where many people quit, I felt it important to keep on going. <br>'),
(12, 16, 'Ta da !!', 'motorcycle about page!<br>'),
(13, 18, 'About Page', 'sample about page');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `id` mediumint(3) NOT NULL AUTO_INCREMENT,
  `fk_site` smallint(3) unsigned NOT NULL,
  `type` smallint(2) unsigned NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `position` tinyint(2) unsigned NOT NULL,
  `enable` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=81 ;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `fk_site`, `type`, `display_name`, `value`, `position`, `enable`) VALUES
(66, 1, 2, 'Email', 'support@plusjade.com', 1, 'yes'),
(2, 1, 1, 'Phone', '626-433-3534', 2, 'yes'),
(3, 1, 3, 'Drop in', '709 w Ramona Rd<br> Alhambra, Ca 91803', 3, 'no'),
(4, 1, 4, 'Business Hours', '<b>Call support</b><br>Monday - Sat: 8am - 8pm<br> Sunday: Closed<p>Email support<br>', 3, 'yes'),
(6, 2, 1, 'Phone', '714-555-3344', 2, 'yes'),
(7, 2, 3, 'Address', '450 apache drive <br> Lake Forest, Ca 91803', 3, 'no'),
(8, 2, 4, 'Business Hours', 'Monday - Sat: 8am - 8pm<br> Sunday: Closed', 4, 'yes'),
(9, 3, 2, 'Email', 'panda@taketomo.com', 4, 'yes'),
(10, 3, 1, 'Phone', '818-219-1494', 3, 'yes'),
(11, 3, 3, 'Drop in', '1115 Mountbatten<br> Glendale, Ca 91803', 2, 'yes'),
(12, 3, 4, 'Business Hours', 'Monday - Sat: 8am - 8pm<br> Sunday: Closed', 1, 'no'),
(13, 1, 5, 'AIM', 'superjadex12', 5, 'no'),
(75, 10, 3, 'address', 'PinkyLand<br>709 W ramona rd<br>Alhambra, ca 91803', 0, 'yes'),
(74, 10, 2, 'email', 'pinky@cat.com', 0, 'yes'),
(73, 10, 1, 'phone', '1800.Pinky.Chu', 0, 'yes'),
(76, 12, 1, 'phone', '626-433-3434', 0, 'yes'),
(77, 12, 2, 'email', 'bob@djshrek.com', 0, 'yes'),
(78, 12, 3, 'address', 'UpInDaClub <br>Santa Fe, NM 93243', 0, 'yes'),
(79, 16, 1, 'phone', ' 626.288.6607', 1, 'yes'),
(80, 16, 2, 'email', 'help@motorcycles-plus.com', 2, 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `contact_type`
--

CREATE TABLE IF NOT EXISTS `contact_type` (
  `type_id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(25) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `contact_type`
--

INSERT INTO `contact_type` (`type_id`, `type`) VALUES
(1, 'phone'),
(2, 'email'),
(3, 'address'),
(4, 'hours'),
(5, 'aim'),
(6, 'skype'),
(7, 'oovoo');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=75 ;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `fk_site`, `question`, `answer`, `position`) VALUES
(2, 1, 'How much does your service cost?', '$30.00 per month. You can also pay a year upfront and save $60.00 \n<p>\nA monthly fee allows you to preserver cashflow and is actually the lowest cost of ownership.\n</p>\nThis is because your website is <b>not</b> a one time deal and it needs to be maintained in a professional manner. This ensures the highest ROI.', 2),
(3, 1, 'Where are you located?', 'We are based in Alhambra, a small suburb of Los Angeles, California.', 3),
(73, 1, 'What do you guys do again?', 'In short, our service allows you to create a proactive, results oriented, website for your business. \n\nIt''s simple, fast, and its effectiveness can be tested and thus optimized. \n\nWe give you all the technical tools you need so you can focus on what matters; your business.', 0),
(74, 1, 'Wait, so I can get a customized website for $120.00... why so cheap?', 'Good question. ', 0),
(60, 2, 'When all we knew was love', 'these are times, not that to always say', 0),
(61, 2, 'Do you embroiler?', 'Yes, but only in the summer because that''s when people typically like to eat embroilered food.', 0),
(62, 3, 'Do your cookies really taste that good?', 'Of course they do !!', 0),
(63, 3, 'Are your baked good healthy?', 'They make you feel happy and being happy is very healthy!', 0),
(64, 3, 'Can pandas eat your baked goods?', 'everyone can enjoy our tasty treats.', 0),
(65, 10, 'Is pinky cute?', 'Of course he is<br>', 0),
(66, 12, 'What type of music do you play?', 'Rock and techno, that is all.', 0),
(67, 12, 'What are your rates?', '$1,000 per night', 0),
(72, 16, 'All in ?', 'Maybe ?', 0),
(71, 16, 'one', 'answer to one', 0);

-- --------------------------------------------------------

--
-- Table structure for table `home`
--

CREATE TABLE IF NOT EXISTS `home` (
  `id` mediumint(3) NOT NULL AUTO_INCREMENT,
  `fk_site` smallint(3) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `desc` text NOT NULL,
  `position` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `home`
--

INSERT INTO `home` (`id`, `fk_site`, `title`, `desc`, `position`) VALUES
(1, 1, 'Simple', 'Your website needs to produce results.\n<p>\nThis is especially true for businesses. A website should have clearly constructed goals and effective calls to action, all of which translate to the bottom line.\n</p>\nOur system is pre-configured to to address these needs and is capable of recording data needed to analyze your results.<br><br><img alt="" src="http://jade.localhost.com/data/jade/assets/images/sample.jpg" align="none"><br>', 1),
(2, 1, 'Effective', 'Major companies spend millions of dollars gathering data, testing and refining their web presence. We learn what works and integrate proven concepts into our system. \n\nStart off on the right track; let us provide you with a map, and eliminate the need for costly trial and error.\n<p>\nRead more about how we incorporate proven web strategies into your site.\n</p>', 2),
(3, 1, 'Reliable', '+Jade does not create custom websites. \nWe deploy business-specific and optimized web cores, that we work together to build on your behalf. \n<p>\nOur system eliminates:\n</p><dl>\n<dt>high development costs</dt>\n<dt>guesswork</dt>\n<dt>long lead times</dt>\n<dt>Procrastination and stress</dt>\n</dl>\n\n\nWe encourage you to read our precautions regarding custom developed websites.', 3),
(4, 1, 'Trackable', 'Our tech-obsessed team maintains and optimizes your website engine on a daily basis.\n<p>\nBecause your website is managed at a central location, you benefit from all updates and feature additions for life! You grow as we grow, and your website <b>never</b> gets old!\n</p>\nThink of us as your dedicated tech team, without the price.', 4),
(5, 3, 'Cookies', 'MMMm Tasty cookies!', 0),
(6, 3, 'Cupcakes', 'The best cheesecake cupcakes u ever had!', 0),
(7, 3, 'Brownies', 'Can you get any better?', 0),
(8, 3, 'Pumpkin Pie', 'Tasty!!', 0),
(9, 2, 'Business', 'Custom branded apparel to give your business the professional look it deserves. \nWe offer all the top brands in a wide range of colors. Give us your logo, wait 10 days,\nand enjoy!', 0),
(10, 2, 'Retail', 'Personal solutions and consultations to launch a retail clothing campaign.\nWhether you are an established business or planning your first launch, we offer personal experience-backed solutions at reasonable prices, and even better quality offerings.', 0),
(11, 2, 'Branding', 'Promotional products meet high end. We offer unique solutions to keeping your companies high value image, while reasonably giving away promotional branding products.', 0),
(12, 1, 'Growable!', 'Not sure if a website is what you need? Don''t take our word for it, test it for yourself!\n<p>\nOur system was developed with results in mind. You have easy access to a wealth of data, that will <b>prove</b> what is working and what needs work.\n</p>\nDeploy juicy offers the lowest possible cost of ownership for a business website. The only thing left to do now is START!', 5),
(15, 3, 'Bunt Cake', 'The best cake around because it has a whole in it!', 0),
(16, 2, 'Schools', 'Custom school wear for senior packages, and hoodies galore. We specialize in unique custom applications of tackle twill and college style hoodies.\n<p>\nWe also incorporate mixed media such printed designs over embroidery for a truly one-of-a-kind finish.\n</p>', 0),
(17, 10, 'Convenient', 'He''s nice to pet<br>', 0),
(18, 10, 'Fast', 'He looks good.<br>', 0),
(19, 10, 'Great Prices', '<ol><li>Wouldn''t have it any other way</li><li>yipasd;fjaf</li><li>skippity<br></li></ol>', 0),
(20, 12, 'Sample', 'Sheeeeet<br>', 0),
(21, 12, 'Live Mixing', 'All day all night<br>', 0),
(22, 16, 'benefit', '<div id="shell">\n	<div id="headline">San Gabriel Valley motorcycle maintenance, repair, and parts!</div>\n		\n</div>', 0),
(23, 16, 'TEE HEE!', 'adfasfd<br>', 0),
(24, 19, 'one benefit', 'yippy<br>', 0),
(25, 19, 'two benefit', 'skippy<br>', 0);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` smallint(5) unsigned NOT NULL,
  `module` varchar(25) NOT NULL,
  `display_name` varchar(25) DEFAULT NULL,
  `position` tinyint(2) unsigned NOT NULL,
  `group` varchar(25) NOT NULL DEFAULT '1',
  `type` enum('module','custom') NOT NULL,
  `enable` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `fk_site`, `module`, `display_name`, `position`, `group`, `type`, `enable`) VALUES
(1, 1, 'home', NULL, 1, '1', 'module', 'yes'),
(2, 1, 'product', 'Learn More', 2, '1', 'module', 'yes'),
(3, 1, 'reviews', NULL, 3, '1', 'module', 'yes'),
(4, 1, 'faq', NULL, 4, '1', 'module', 'yes'),
(5, 1, 'about', NULL, 5, '1', 'module', 'yes'),
(6, 1, 'contact', NULL, 6, '1', 'module', 'yes'),
(7, 16, 'home', 'Start', 1, '1', 'module', 'yes'),
(8, 16, 'product', 'Services', 2, '1', 'module', 'yes'),
(9, 16, 'reviews', 'Reviews', 3, '1', 'module', 'yes'),
(10, 16, 'faq', 'FAQ', 4, '1', 'module', 'yes'),
(11, 16, 'about', 'About', 5, '1', 'module', 'yes'),
(12, 16, 'contact', 'Contact', 6, '1', 'module', 'yes'),
(13, 12, 'home', 'Home', 1, '1', 'module', 'yes'),
(14, 12, 'product', 'Products', 2, '1', 'module', 'yes'),
(15, 12, 'faq', 'Questions', 3, '1', 'module', 'yes'),
(16, 12, 'contact', 'Contact', 4, '1', 'module', 'yes'),
(22, 18, 'home', NULL, 1, '1', 'module', 'yes'),
(21, 1, 'compare', 'Competitors', 7, '1', 'custom', 'yes'),
(23, 18, 'product', NULL, 2, '1', 'module', 'yes'),
(24, 18, 'reviews', NULL, 1, '1', 'module', 'yes'),
(25, 18, 'faq', NULL, 2, '1', 'module', 'yes'),
(26, 18, 'about', '', 3, '1', 'module', 'no'),
(27, 18, 'contact', '', 6, '1', 'module', 'no'),
(28, 19, 'water', 'Water Foo!', 2, '1', 'custom', 'yes'),
(29, 19, 'home', '', 1, '1', 'module', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` mediumint(3) NOT NULL AUTO_INCREMENT,
  `fk_site` smallint(3) unsigned NOT NULL,
  `controller` varchar(30) NOT NULL,
  `number` int(1) NOT NULL DEFAULT '1',
  `title` varchar(50) NOT NULL,
  `meta` varchar(50) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `fk_site`, `controller`, `number`, `title`, `meta`, `body`) VALUES
(1, 0, '', 0, 'about', '', '<b>Founded in 1995</b> Dexterous Industries has a committed value to quality products. <p>\r\nNot just a generic statement Dexterous only sells specific branded products that customers can trust.</p>'),
(2, 1, '', 0, 'about', '', '<b>Founded in 1995</b> Dexterous Industries has a committed value to quality products. <p>\r\nNot just a generic statement Dexterous only sells specific branded products that customers can trust.</p>'),
(3, 16, 'home', 1, 'Moto Home Page teee hee', '', '<div id="shell">\n	<div id="headline">San Gabriel Valley motorcycle maintenance, repair, and parts!<br></div>\n		<img src="http://moto.localhost.com/data/moto/assets/images/front.jpg" id="frontpic">\n</div>'),
(4, 16, 'reviews', 1, 'reviews sample page for moto', '', 'yippity skippity this is my reviews page in simple page mode'),
(6, 16, 'product', 1, 'Motorcycles Plus Waterglass Services', 'services for motorcycles in the san gabriel valley', 'This is the waterglass page <br><br><h1><span style="font-family: trebuchet ms;">Tee hee!</span></h1>'),
(7, 1, 'about', 1, 'plusJade about page', 'About plusJade', '<font size="5">About </font><font size="5"><span style="font-weight: bold;">+Jade</span></font><br><br>The idea behind +Jade is to signify that our customers gain a team addition to their business. \nMy name just happens to be Jade, and though it might sound vain, I went with +Jade because that is quite literally what you get; my hard work, my determination, my effort, and my sweat  to make your company the best it possibly can be by making +Jade the best it can be. \n\n+Jade is about a team effort, where collective energies are leveraged to benefit everyone.<br>\n<b>Your business +Jade.</b>'),
(8, 12, 'home', 1, '', '', 'Hi, welcome to your source for antique furniture in the greater San Gabriel Valley.<br><br>We have all those one of a kind pieces that make your room complete.<br><br>Stop by today!<br>'),
(11, 1, 'compare', 1, 'Beatiful song lyrics', 'Taylor swift song lyrics', 'i hope you find EVERYTHING you look for!!<br><br><div style="text-align: center;"><img src="http://img217.imageshack.us/img217/6910/11983310cp5.jpg" width="120"><br></div>'),
(12, 19, 'water', 1, 'Water and all its miracles', 'Research paper on water ', 'yummy water is soooo yyummmy!!<br><br>asdf<br>asd<br>f<br>sadf<br>sdf<br><br><br><br>sdfasdf<br><br><br><br><br><br>sdf<br>asfd<br><br><br>sadf<br>saf<br><br><br><br><br><br>sdf<br><br><br><br><br>sadf<br><br><br><br><br><br>sadf<br>');

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
(7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE IF NOT EXISTS `sites` (
  `site_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(25) NOT NULL,
  `domain` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `tagline` text NOT NULL,
  `address` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `banner` varchar(50) NOT NULL,
  `theme` varchar(50) NOT NULL,
  `simple_page` varchar(50) NOT NULL,
  PRIMARY KEY (`site_id`),
  KEY `url` (`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`site_id`, `url`, `domain`, `name`, `tagline`, `address`, `phone`, `email`, `banner`, `theme`, `simple_page`) VALUES
(1, 'jade', '', '+Jade yessir', '				<a target="" title="" href="/auth"></a>			', '1115 MountBatten ave<br>Glendale, Ca 91207', '626-433-3534', 'jade@deployJuicy.com', 'plus_jade.jpg', 'redcross', 'about'),
(10, 'pinky', '', 'Motocycles Plus', '<font size="3">San Gabriel Valley motorcycle maintenance, repair, and parts!</font><br>', '729 e. Garvey Ave.<br> Montery Park, Ca 91755', '626.288.6607', 'service@motorycles-plus.com', 'moto-plus.jpg', 'beta', ''),
(2, 'webemb', '', 'Dexterious Industries', '								Professional custom embroidery solutions...<br> done right.			', '709 w Ramona Rd<br>Alhambra, Ca 91803', '626-433-3534', 'sales@webemb.com', 'logo_black.png', 'redcross', ''),
(3, 'panda', '', 'PandaLand', '		Come to pandaland!	\r\n<p style="font-size:0.7em; color:#000;">\r\nWe create the best baked goods around! Try them out for yourself! =)\r\n</p>	', '1115 MountBatten ave<br>Glendale, Ca 91207', '818-219-1494', 'Panda@pandaland.com', 'cute_panda.jpg', 'redcross', ''),
(12, 'bob', '', 'What it do', 'Dj Shrek<br>', 'Hip hop Alley<br> Westide, Ca', '123-555-3434', 'djboby@sucka.com', 'logo_deploy.png', 'nonzero', 'home'),
(14, 'boy', '', '', '', '', '', '', 'linux.gif', 'redcross', ''),
(15, 'human', '', 'Are we human?', '', 'Are we dancers?', '800 - 500 - ', 'blah@ronald.edu', 'blue1.gif', 'redcross', ''),
(16, 'moto', '', '', '', '729 e. Garvey Ave.  Montery Park, Ca 91755', '626.288.6607', 'service@motorycles-plus.com', 'motologo.png', 'custom', 'home_product_reviews'),
(18, 'dad', '', 'dad telecommunications', '', '709 W Ramona Rd', '626.288.6607', 'dad@dad.com', 'logo_deploy.png', 'redcross', ''),
(19, 'water', '', 'water', '', 'waterPolo', '626-433-3534', '', '', 'redcross', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site` int(3) NOT NULL,
  `email` varchar(127) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` char(50) NOT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `site`, `email`, `username`, `password`, `logins`, `last_login`) VALUES
(1, 1, 'jade@jade.com', 'jade', '62a69de60a2d1bb13bf2edf2bd976870b72597985683325617', 56, 1232712417),
(6, 0, 'dad@dad.com', 'dad', '655f0e5cda4630082a2944aa18853ec6328191946b6644dcdd', 2, 1232598890),
(7, 0, 'water@water.com', 'water', 'edc0cb9900b27be55eb30d3ed1ea30e6cc62c53f450719ca88', 2, 1232603082);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user_tokens`
--


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
