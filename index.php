<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php 
// include_once "../assets/php/db_connect.php";
include_once "assets/php/db_connect.php";
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	if(isset($_SESSION["HeliUser"])){
		echo "Redirecting to home.php...";
		header("Location: ../home.php");
	}
?>
<!DOCTYPE html>
<!-- Drew - A Multipurpose Landing Page Template, designed by David Rozando (http://design.davidrozando.com) -->
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en"> <!--<![endif]-->

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		
		<!-- BASIC INFO -->
		<title>Helicopters Offshore1234 - Your Flight Community</title>
		<meta name="keywords" content="helicopters,helicopter,offshore, helicopters offshore, helicopter offshore, offshore helicopters,scheduling,flying, flight,schedule,pilots,statistics,log book,log,Angola,management,pilot,planner,easy,simple,software,cloud,web application, application">
		<!-- <meta name="description" content="Helicopters Offshore is a site designed to make offshore helicopter scheduling easy. We offer a way to easily schedule pilots on aircrafts, plan out training schedules, manage tests, keep track with our logbook, and keep in touch within your community. Our software is perfect for you whether you're a single pilot or a company looking to manage your fleet."> -->
		<meta name="description" content="Helicopters Offshore offers a way to easily manage schedules, training, tests expiration, keeping a logbook, and keeping in touch within your community.">
		<!-- FAVICONS -->
		<link rel="icon" href="images/favicons/favicon.ico">
		<!-- <link rel="apple-touch-icon" href="images/favicons/apple-touch-icon.png"> -->

		<!-- CSS
		================================= -->

		<!-- GOOGLE FONTS -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800">

		<!-- LIBRARIES CSS -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/animate.min.css">

		<!-- SPECIFIC CSS -->
		<!-- <link rel="stylesheet" href="css/style.css"> -->

		<!-- COLORS -->
		<!-- <link id="color-css" rel="stylesheet" href="css/colors/pink.css"> -->
		<!-- <link id="color-css" rel="stylesheet" href="css/colors/red.css"> -->
		<link id="color-css" rel="stylesheet" href="css/colors/orange.css">
		<!-- <link id="color-css" rel="stylesheet" href="css/colors/yellow.css"> -->
		<!-- <link id="color-css" rel="stylesheet" href="css/colors/green.css"> -->
		<!-- <link id="color-css" rel="stylesheet" href="css/colors/turquoise.css"> -->
		<!-- <link id="color-css" rel="stylesheet" href="css/colors/blue.css"> -->
		
	</head>

	<body class="enable-animations enable-preloader">

		<div id="document" class="document">

			<!-- HEADER
			================================= -->
			<header id="header" class="header-section section section-dark navbar navbar-fixed-top">

				<div class="container-fluid">

					<div class="navbar-header navbar-left">

						<!-- RESPONSIVE MENU BUTTON -->
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>

						<!-- HEADER LOGO -->
						<a class="navbar-logo navbar-brand anchor-link" href="#hero">
							HELICOPTERS OFFSHORE
						</a>

					</div>

					<nav id="navigation" class="navigation navbar-collapse collapse navbar-right">
						
						<!-- NAVIGATION LINKS -->
						<ul id="header-nav" class="nav navbar-nav">
							
							<li><a href="#hero" class="hidden">Top</a></li>

							<li><a href="#about">About</a></li>
							<li><a href="#benefits">Features</a></li>
							<li><a href="#how-it-works">How it Works</a></li>
							<!-- <li><a href="#video">Watch Video</a></li> -->
							<li><a href="#future">Our Goals</a></li>
							<li><a href="#pricing">Pricing</a></li>
							<li><a href="news.php">News</a></li>
							<!-- <li><a href="#contact">Contact</a></li> -->
							
							<!-- HEADER ACTION BUTTON -->
							<li class="header-action-button"><a href="#" class="btn btn-primary" data-toggle="modal" data-target="#signupModal">Sign Up</a></li>
							<li class="header-action-button"><a href="#" data-toggle="modal" data-target="#loginModal" class="btn btn-primary">Log in</a></li>

						</ul>

					</nav>

				</div>

			</header>

			<!-- HERO
			================================= -->
			<section id="hero" class="hero-section hero-layout-simple hero-fullscreen section section-dark">

				<div class="section-background">

					<!-- IMAGE BACKGROUND -->
					<div class="section-background-image parallax" data-stellar-ratio="0.4">
						<img src="images/backgrounds/head_banner.jpg" alt="helictoper dashboard" style="opacity: 0.3;">
					</div>

					<!-- VIDEO BACKGROUND -->
					<!-- <div class="section-background-video section-background-dot-overlay parallax" data-stellar-ratio="0.4">
						<video preload="auto" autoplay loop muted poster="images/backgrounds/video-fallback-bg.jpg" style="opacity: 0.3;">
							<source type="video/mp4" src="videos/video-bg.mp4">
							<source type="video/ogg" src="videos/video-bg.ogv">
							<source type="video/webm" src="videos/video-bg.webm">
						</video>
					</div> -->

					<!-- SLIDESHOW BACKGROUND -->
					<!-- <ul class="section-background-slideshow parallax" data-stellar-ratio="0.4" data-speed="800" data-timeout="4000">
						<li><img src="images/backgrounds/hero-bg-slideshow-1.jpg" alt="" style="opacity: 0.25;"></li>
						<li><img src="images/backgrounds/hero-bg-slideshow-2.jpg" alt="" style="opacity: 0.25;"></li>
						<li><img src="images/backgrounds/hero-bg-slideshow-3.jpg" alt="" style="opacity: 0.2;"></li>
					</ul> -->

				</div>

				<div class="container">

					<div class="hero-content">
						<div class="hero-content-inner">

							<div class="row">
								<div class="col-md-10 col-md-offset-1">

									<div class="hero-heading" data-animation="fadeIn">

										<h1 class="hero-title">Helicopter management made easy</h1>

										<p class="hero-tagline">We help you organize all aspects of helicopter piloting and management.</p>

									</div>

									<p class="hero-buttons">
										<a href="#about" class="btn btn-lg btn-default anchor-link">Learn More</a>
										<a href="#" data-toggle="modal" data-target="#signupModal" class="btn btn-lg btn-primary">Sign Up Now</a>
									</p>

								</div>
							</div>
							
						</div>
					</div>

				</div>

				<!-- HERO START LINK -->
				<a href="#headline" class="hero-start-link anchor-link"><span class="fa fa-angle-double-down"></span></a>

			</section>

			<!-- HEADLINE
			================================= -->
			<section id="headline" class="headline-section section-gray section">

				<div class="container">

					<div class="row">
						<div class="col-md-10 col-md-offset-1">

							<p class="headline-text">
								Designed by people in the business, <strong>HELICOPTERS OFFSHORE</strong> makes managing your workflow, keeping track of records and schedules, and connecting with others simple and time effective.
							</p>

						</div>
					</div>

				</div>

			</section>

			<!-- DESCRIPTION
			================================= -->
			<section id="about" class="about-section section">

				<div class="container">

					<h2 class="section-heading text-center">About Helicopters Offshore</h2>

					<div class="about-row row">

						<!-- DESCRIPTION IMAGE -->
						<div class="about-image col-md-6" data-animation="fadeIn">
							<img src="images/backgrounds/about_us_2.jpg" alt="Angolan helicopter fleet" style="border-radius: 15px;">
						</div>

						<!-- DESCRIPTION TEXT -->
						<div class="about-text col-md-6">
							<p class="lead">Our duty is to make your duty simple.</p>
							<p>At <strong>HELICOPTERS OFFSHORE</strong>, one of our many visions is <u>planning made easy</u>. <i>How can we help make you job easier?</i> We've come up with a way for you to easily keep track and manage all of the things that are important to you. How do we know? Because we're in the business too. Although we're designed for offshore helicopters in Angola, our features can be applied worldwide! Here's some of our features:</p>
							<ul class="icon-list">
								<li>
									<span class="icon-list-icon fa fa-calendar" data-animation="bounceIn"></span>
									<h4 class="icon-list-title">Scheduling</h4>
									<p>From managing your weekly flights, to your training schedules, we've got it.</p>
								</li>
								<li>
									<span class="icon-list-icon fa fa-users" data-animation="bounceIn"></span>
									<h4 class="icon-list-title">Community</h4>
									<p>We've created a community for helicopter pilots. Communicate, share, explore. We keep you and your colleagues in touch</p>
								</li>
								<li>
									<span class="icon-list-icon fa fa-clock-o" data-animation="bounceIn"></span>
									<h4 class="icon-list-title">Keep Track</h4>
									<p>With HELICOPTER OFFSHORE, we keep track for you. From log books, to tracking when your tests expire, to showing you your flight statistics. All of your data is in one place.</p>
								</li>
							</ul>
						</div>

					</div>

				</div>

			</section>

			<!-- BENEFITS
			================================= -->
			<section id="benefits" class="benefits-section section-gray section">

				<div class="container">

					<h2 class="section-heading text-center">More Features</h2>

					<div class="benefits-row row">

						<!-- BENEFIT 1 -->
						<div class="col-md-4 col-sm-6">
							<div class="benefit">
								<span class="benefit-icon fa fa-user-plus" data-animation="bounceIn"></span>
								<h4 class="benefit-title">Adding Pilots</h4>
								<p class="benefit-description">Adding a pilot to your fleet is just a few keys and a click away. You can keep track of test expiration, contact information, and enable/disable aircrafts, contracts and positions. Rather than searching through piles of paper, just type the name and your results are there. Creating a pilot creates a user. That pilot can now sign in and keep track of their own statistics, view their scheduling, and connect to your community.</p>
							</div>
						</div>

						<!-- BENEFIT 2 -->
						<div class="col-md-4 col-sm-6">
							<div class="benefit">
								<span class="benefit-icon fa fa-bell" data-animation="bounceIn"></span>
								<h4 class="benefit-title">Notifications</h4>
								<p class="benefit-description">With just a click of the button, you can easily notify all of the scheduled pilots when and what they're flying. We send them a message straight to their phone telling them all the details. At the same time, we get you the log sheets you'll need to keep track of their hours at the end of the day. We even give you a record of all the messages you've sent and whether or not they were delivered successfully.</p>
							</div>
						</div>

						<div class="hidden-md hidden-lg clear"></div>

						<!-- BENEFIT 3 -->
						<div class="col-md-4 col-sm-6">
							<div class="benefit">
								<span class="benefit-icon fa fa-dashboard" data-animation="bounceIn"></span>
								<h4 class="benefit-title">Flight Schedule</h4>
								<p class="benefit-description">The first thing you see when you log in is your schedule. We know you've got a bunch of things to keep track of, so for your schedule, we've got you covered. You can easily see when and what you're flying throughout the week. For management, we cut your planning time by 50% giving you more time to enjoy yourself. Keeping track of who's available, who flew a night shift, who's flown too much, who's test are expired; We do it so you don't have to. Simply click the position and select the pilot. That's it!</p>
							</div>
						</div>

						<div class="hidden-sm clear"></div>

						<!-- BENEFIT 4 -->
						<div class="col-md-4 col-sm-6">
							<div class="benefit">
								<span class="benefit-icon fa fa-calendar" data-animation="bounceIn"></span>
								<h4 class="benefit-title">Training Schedule</h4>
								<p class="benefit-description">In addition to our key flight schedule, we have the training schedule. Plan your trainers, examiners, and trainees all in one place. We know who's available so just click a time period and we'll show you a list.</p>
							</div>
						</div>

						<div class="hidden-md hidden-lg clear"></div>

						<!-- BENEFIT 5 -->
						<div class="col-md-4 col-sm-6">
							<div class="benefit">
								<span class="benefit-icon fa fa-file-text-o" data-animation="bounceIn"></span>
								<h4 class="benefit-title">Tests and Paperwork</h4>
								<p class="benefit-description">Easily keep track of when your documents and tests are going to expire. For management, easily print out lists of pilots who's validity is going to expire or already has. Click <a href="#" data-toggle="modal" data-target="#testListModal">here</a> for a list of all the tests we keep track of.</p>
							</div>
						</div>

						<!-- BENEFIT 6 -->
						<div class="col-md-4 col-sm-6">
							<div class="benefit">
								<span class="benefit-icon fa fa-book" data-animation="bounceIn"></span>
								<h4 class="benefit-title">Crafts And Contracts</h4>
								<p class="benefit-description">Keep track of all your aircrafts and contracts. Simply add the model and registration, whether it flies at night or during the day <i>(used by the schedule)</i> and even keep track of whether or not it's out for maintenance. Then you can add any of your new aircrafts to a contract, pick a fancy color, and get started scheduling.</p>
							</div>
						</div>

						<!-- BENEFIT 6 -->
						<div class="col-md-4 col-sm-6">
							<div class="benefit">
								<span class="benefit-icon fa fa-file-o" data-animation="bounceIn"></span>
								<h4 class="benefit-title">Documents</h4>
								<p class="benefit-description">Upload important documents that you need your fleet to see. We also keep track of who has viewed the document so you can know who to contact.</p>
							</div>
						</div>

						<!-- BENEFIT 6 -->
						<div class="col-md-4 col-sm-6">
							<div class="benefit">
								<span class="benefit-icon fa fa-key" data-animation="bounceIn"></span>
								<h4 class="benefit-title">Permission Levels</h4>
								<p class="benefit-description">We know that you might have different levels of users in your account. We let you set what your users have access to. For instance, pilots can't edit the flight schedule but managers can. You can give control to users that need it.</p>
							</div>
						</div>
					</div>

				</div>

			</section>

			<!-- HOW IT WORKS
			================================= -->
			<section id="how-it-works" class="how-it-works-section section">

				<div class="container-fluid">
					
					<h2 class="section-heading text-center">How it Works</h2>

					<div class="hiw-row row">

						<!-- HOW IT WORKS - ITEM 1 -->
						<div class="col-md-3 col-sm-6" data-animation="fadeIn">
							<div class="hiw-item">
								<img class="hiw-item-picture" src="images/backgrounds/addpilot.png" alt="pilot management">
								<div class="hiw-item-text">
									<span class="hiw-item-icon">1</span>
									<h4 class="hiw-item-title">Add Some Pilots</h4>
									<p class="hiw-item-description">Add some pilots to your fleet. Set their contact information, permissions, contracts and crafts, positions and tests and that's it!</p>
								</div>
							</div>
						</div>

						<!-- HOW IT WORKS - ITEM 2 -->
						<div class="col-md-3 col-sm-6" data-animation="fadeIn">
							<div class="hiw-item even">
								<img class="hiw-item-picture" src="images/backgrounds/contract.png" alt="aircraft management">
								<div class="hiw-item-text">
									<span class="hiw-item-icon">2</span>
									<h4 class="hiw-item-title">Crafts And Contracts</h4>
									<p class="hiw-item-description">Add aircrafts to manage and schedule, and create some contracts. Simply select which pilots can fly on that contract, pick the aircrafts, choose a color and you're done. You can rearrange the order just by dragging.</p>
								</div>
							</div>
						</div>

						<div class="hidden-md hidden-lg clear"></div>

						<!-- HOW IT WORKS - ITEM 3 -->
						<div class="col-md-3 col-sm-6" data-animation="fadeIn">
							<div class="hiw-item">
								<img class="hiw-item-picture" src="images/backgrounds/schedule.png" alt="helicopter scheduling">
								<div class="hiw-item-text">
									<span class="hiw-item-icon">3</span>
									<h4 class="hiw-item-title">Schedule Away</h4>
									<p class="hiw-item-description">With just a few clicks you can be done your week's schedule. What used to take hours can now take minutes. No more stressing over papers. If there's a change, just click and select someone else.</p>
								</div>
							</div>
						</div>

						<!-- HOW IT WORKS - ITEM 4 -->
						<div class="col-md-3 col-sm-6" data-animation="fadeIn">
							<div class="hiw-item even">
								<img class="hiw-item-picture" src="images/backgrounds/how_it_works_banner_2.jpg" alt="helicopter flying">
								<div class="hiw-item-text">
									<span class="hiw-item-icon">4</span>
									<h4 class="hiw-item-title">Relax &amp; Fly!</h4>
									<p class="hiw-item-description">We take care of all the heavy lifting so you can do you job without the stress. It really can be that easy.</p>
								</div>
							</div>
						</div>

					</div>

				</div>

			</section>

			<!-- NUMBERS
			================================= -->
			<section id="numbers" class="numbers-section section-dark section">

				<div class="section-background">

					<!-- IMAGE BACKGROUND -->
					<div class="section-background-image parallax" data-stellar-ratio="0.4">
						<img src="images/backgrounds/numbers_banner_2.jpg" alt="helicopter statistics" style="opacity: 0.2;">
					</div>

				</div>

				<div class="container">

					<h2 class="section-heading text-center">Our Numbers</h2>

					<div class="numbers-row row">

						<!-- NUMBERS - ITEM 1 -->
						<div class="col-md-3 col-sm-6">
							<div class="numbers-item">
								<div class="numbers-item-counter"><span class="counter-up">2</span></div>
								<div class="numbers-item-caption">Stage</div>
							</div>
						</div>

						<!-- NUMBERS - ITEM 2 -->
						<div class="col-md-3 col-sm-6">
							<div class="numbers-item">
								<div class="numbers-item-counter"><span class="counter-up">95</span>%</div>
								<div class="numbers-item-caption">Complete</div>
							</div>
						</div>

						<!-- NUMBERS - ITEM 3 -->
						<div class="col-md-3 col-sm-6">
							<div class="numbers-item">
								<div class="numbers-item-counter"><span class="counter-up"><?php $count = $mysqli->query("SELECT COUNT(id) AS count FROM pilot_info")->fetch_assoc()["count"]; $html = ($count < 10000) ? "$count</span>" : ($count/1000)."</span>k"; echo $html?></div>
								<div class="numbers-item-caption">Total Users</div>
							</div>
						</div>

						<!-- NUMBERS - ITEM 4 -->
						<div class="col-md-3 col-sm-6">
							<div class="numbers-item">
								<!-- <div class="numbers-item-counter"><span class="counter-up"><?php $count = $mysqli->query("SELECT COUNT(id) AS count FROM crafts")->fetch_assoc()["count"]; $html = ($count < 10000) ? "$count</span>" : ($count/1000)."</span>k"; echo $html?></div> -->
								<div class="numbers-item-caption">Crafts</div>
							</div>
						</div>

					</div>

				</div>

			</section>

			<!-- VIDEO SECTION
			================================= -->
			<!-- <section id="video" class="video-section section-gray section">

				<div class="container">

					<h2 class="section-heading text-center">Watch the Video</h2>

					<div class="row">
						<div class="col-md-10 col-md-offset-1">

							<div class="video-embed"> -->

								<!-- VIDEO EMBED FROM VIMEO -->
								<!-- <iframe class="video-async" data-source="vimeo" data-video="115134273" data-color="f3ae73" allowfullscreen></iframe> -->
								
								<!-- VIDEO EMBED FROM YOUTUBE -->
								<!-- <iframe class="video-async" data-source="youtube" data-video="7UAy8E3e9f8" allowfullscreen></iframe> -->
<!-- 
							</div>

						</div>
					</div>

				</div>
			</section> -->

			<!-- TEAM
			================================= -->
			<!-- <section id="team" class="team-section section">

				<div class="container-fluid">
					
					<h2 class="section-heading text-center">Meet The Experts</h2>

					<div class="team-row row"> -->

						<!-- TEAM MEMBER 1 -->
						<!-- <div class="col-lg-2 col-md-4 col-sm-6" data-animation="fadeIn">
							<div class="team-member">
								<img class="team-member-picture" src="images/contents/team-member-1.jpg" alt="">
								<div class="team-member-text">
									<h4 class="team-member-name">Jason Castillo</h4>
									<div class="team-member-position">CEO &amp; Co-Founder</div>
									<p class="team-member-description">Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.</p>
								</div>
							</div>
						</div> -->

						<!-- TEAM MEMBER 2 -->
						<!-- <div class="col-lg-2 col-md-4 col-sm-6" data-animation="fadeIn">
							<div class="team-member even">
								<img class="team-member-picture" src="images/contents/team-member-2.jpg" alt="">
								<div class="team-member-text">
									<h4 class="team-member-name">Harold Kelly</h4>
									<div class="team-member-position">CTO &amp; Co-Founder</div>
									<p class="team-member-description">Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.</p>
								</div>
							</div>
						</div>

						<div class="hidden-md hidden-lg clear"></div> -->

						<!-- TEAM MEMBER 3 -->
						<!-- <div class="col-lg-2 col-md-4 col-sm-6" data-animation="fadeIn">
							<div class="team-member">
								<img class="team-member-picture" src="images/contents/team-member-3.jpg" alt="">
								<div class="team-member-text">
									<h4 class="team-member-name">Kathy Nelson</h4>
									<div class="team-member-position">Graphic Designer</div>
									<p class="team-member-description">Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.</p>
								</div>
							</div>
						</div>

						<div class="hidden-sm hidden-lg clear"></div> -->

						<!-- TEAM MEMBER 4 -->
						<!-- <div class="col-lg-2 col-md-4 col-sm-6" data-animation="fadeIn">
							<div class="team-member even">
								<img class="team-member-picture" src="images/contents/team-member-4.jpg" alt="">
								<div class="team-member-text">
									<h4 class="team-member-name">Dylan Fowler</h4>
									<div class="team-member-position">UI/UX Designer</div>
									<p class="team-member-description">Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.</p>
								</div>
							</div>
						</div>

						<div class="hidden-md hidden-lg clear"></div> -->

						<!-- TEAM MEMBER 5 -->
						<!-- <div class="col-lg-2 col-md-4 col-sm-6" data-animation="fadeIn">
							<div class="team-member">
								<img class="team-member-picture" src="images/contents/team-member-5.jpg" alt="">
								<div class="team-member-text">
									<h4 class="team-member-name">Carolyn Harvey</h4>
									<div class="team-member-position">Development</div>
									<p class="team-member-description">Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.</p>
								</div>
							</div>
						</div>
 -->
						<!-- TEAM MEMBER 6 -->
						<!-- <div class="col-lg-2 col-md-4 col-sm-6" data-animation="fadeIn">
							<div class="team-member even">
								<img class="team-member-picture" src="images/contents/team-member-6.jpg" alt="">
								<div class="team-member-text">
									<h4 class="team-member-name">Diane Grant</h4>
									<div class="team-member-position">Marketing &amp; Strategy</div>
									<p class="team-member-description">Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.</p>
								</div>
							</div>
						</div>

					</div>

				</div>

			</section> -->
			<section id="future" class="headline-section section-gray section">

				<div class="container">

					<div class="row">
						<div class="col-md-10 col-md-offset-1">

							<p class="headline-text">
								We're continuing to expand to give you everything you want, all in one place.
							</p>

						</div>
					</div>

				</div>

			</section>
			<!-- TWO COLS DESCRIPTION SECTION
			================================= -->
			<section class="two-cols-description-section section-accent section">

				<div class="container-fluid two-cols-description-row">

					<!-- TWO COLS DESCRIPTION TEXT -->
					<div class="two-cols-description-text" data-animation="fadeInLeft">

						<div class="two-cols-description-text-inner">

							<h2 class="section-heading text-left">Your Community</h2>

							<p>You can easily stay in contact with all of your fellow pilots with our community. Share. Read. Belong. See what your fellow pilots are up to and contribute to your community.</p>

							<ul class="nice-list">
								<li><strong>News Feed</strong> <small>NEW</small>. Find out all the important updates around the world that apply to pilots.</li>
								<li><strong>Messaging</strong> <small>NEW</small>. If you want to send a private message, we've got that too. For quick messaging in the workplace, there's our messaging center.</li>
								<li><strong>Store</strong> <small>Coming Soon!</small>. List and purchase things from your fellow colleagues. From headsets to computers, it's a digital garage sale.</li>
								<li><strong>Posts</strong> <small>Coming Soon!</small>. Stay in the know and see what your friends are talking about. Have something on your mind? Just post about it.</li>
							</ul>
							
						</div>

					</div>

					<!-- TWO COLS DESCRIPTION IMAGE -->
					<div class="two-cols-description-image" data-animation="fadeInRight">
						<img src="images/backgrounds/community_image_2.jpg" alt="Angolan offshore helicopter">
					</div>

				</div>

			</section>

			<!-- PRICING SECTION
			================================= -->
			<section id="pricing" class="pricing-section section">

				<div class="container">

					<h2 class="section-heading text-center">Pricing Table</h2>

					<div class="row text-center">
						<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
							<p>Right now, It's free to try it out, see our designs, and we can customize it to fit your needs</p>
						</div>
					</div>

					<!-- PRICING TABLE -->
					<!-- <div class="pricing-table row"> -->

						<!-- PRICING PACKAGE 1 -->
						<!-- <div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-3" data-animation="fadeInUp">
							<div class="pricing-package">
								<div class="pricing-package-header">
									<h4 class="price-title">Personal</h4>
									<div class="price">
										<span class="price-currency">$</span>
										<span class="price-number">0</span>
										<span class="price-decimal"></span>
									</div>
									<div class="price-description">FREE Forever</div>
								</div>
								<ul class="pricing-package-items">
									<li>Up to 20 Projects</li>
									<li>500MB Harddisk Space</li>
									<li>Up to 1000 Monthly Visitors</li>
									<li><del>Custom Domain</del></li>
									<li><del>Premium Themes Access</del></li>
									<li>24/7 Customer Support</li>
								</ul>
							</div>
						</div> -->

						<!-- PRICING PACKAGE 2 -->
						<!-- <div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-3" data-animation="fadeInUp">
							<div class="pricing-package pricing-package-featured">
								<div class="pricing-package-header">
									<h4 class="price-title">Professional</h4>
									<div class="price">
										<span class="price-currency">$</span>
										<span class="price-number">13</span>
										<span class="price-decimal">99</span>
									</div>
									<div class="price-description">Billed monthly</div>
									<div class="price-featured">Most Popular</div>
								</div>
								<ul class="pricing-package-items">
									<li>Up to 200 Projects</li>
									<li>5GB Harddisk Space</li>
									<li>Unlimited Visitors</li>
									<li>Custom Domain</li>
									<li><del>Premium Themes Access</del></li>
									<li>24/7 Customer Support</li>
								</ul>
							</div>
						</div> -->

						<!-- PRICING PACKAGE 3 -->
						<!-- <div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-3" data-animation="fadeInUp">
							<div class="pricing-package">
								<div class="pricing-package-header">
									<h4 class="price-title">Enterprise</h4>
									<div class="price">
										<span class="price-currency">$</span>
										<span class="price-number">69</span>
										<span class="price-decimal">99</span>
									</div>
									<div class="price-description">Billed monthly</div>
								</div>
								<ul class="pricing-package-items">
									<li>Unlimited Projects</li>
									<li>Unlimited Harddisk Space</li>
									<li>Unlimited Visitors</li>
									<li>Custom Domain</li>
									<li>Premium Themes Access</li>
									<li>24/7 Customer Support</li>
								</ul>
							</div>
						</div>

					</div> -->

				</div>

			</section>

			<!-- TESTIMONIALS
			================================= -->
			<!-- <section id="testimonial" class="testimonial-section section-gray section">

				<div class="container">

					<h2 class="section-heading text-center">Trusted by Thousands</h2>

					<div class="sponsors-row" data-animation="bounceIn">
						<img src="images/contents/sponsor-1.png" alt="">
						<img src="images/contents/sponsor-2.png" alt="">
						<img src="images/contents/sponsor-3.png" alt="">
						<img src="images/contents/sponsor-4.png" alt="">
					</div>

					<div class="testimonial-row row"> -->

						<!-- TESTIMONIAL ITEM 1 -->
						<!-- <div class="col-sm-6">
							<div class="testimonial">
								<blockquote class="testimonial-quote">
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla lectus dui, volutpat sed velit congue, pulvinar tristique nisi. Maecenas mollis rutrum bibendum. Etiam mollis diam risus, at varius ex dapibus sed. In in leo at sapien placerat sagittis.</p>
								</blockquote>
								<span class="testimonial-ratings">
									<span class="fa fa-star"></span>
									<span class="fa fa-star"></span>
									<span class="fa fa-star"></span>
									<span class="fa fa-star"></span>
									<span class="fa fa-star"></span>
								</span>
								<cite class="testimonial-cite">John Doe, Company Inc.</cite>
							</div>
						</div> -->

						<!-- TESTIMONIAL ITEM 2 -->
<!-- 						<div class="col-sm-6">
							<div class="testimonial">
								<blockquote class="testimonial-quote">
									<p>Nulla lectus dui, volutpat sed velit congue, pulvinar tristique nisi. Maecenas mollis rutrum bibendum. Etiam mollis diam risus, at varius ex dapibus sed. In in leo at sapien placerat sagittis. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
								</blockquote>
								<span class="testimonial-ratings">
									<span class="fa fa-star"></span>
									<span class="fa fa-star"></span>
									<span class="fa fa-star"></span>
									<span class="fa fa-star"></span>
									<span class="fa fa-star-half-o"></span>
								</span>
								<cite class="testimonial-cite">Dohn Joe, Internet Marketer</cite>
							</div>
						</div>

					</div>

				</div>

			</section>
 -->
			<!-- GOOGLE MAPS
			================================= -->
			<section id="contact" class="maps-section section">

				<div class="container-fluid maps-row">

					<!-- MAPS IMAGE -->
					<div class="maps-image" data-animation="fadeIn">
						<div id="gmap"></div>
					</div>

					<!-- MAPS TEXT -->
					<div class="maps-text" data-animation="fadeIn">

						<div class="maps-text-inner">

							<h3 class="section-heading text-left">Want to find out more?</h3>

							<p>To see if we can fit your pilots' schedule and and fleet management requirements, just send us a message. We'd love to hear from you and get you on board.</p>

							<div class="row">
								<div class="form-group">
									<label for="msg-name">Name (Optional)</label>
									<input type="text" class="form-control" name="msg-name" id="msg-name"> 
								</div>
								<div class="form-group">
									<label for="msg-email">Email</label>
									<input type="text" class="form-control" name="msg-email" id="msg-email"> 
								</div>
								<div class="form-group">
									<textarea cols="54" rows="8" class="form-control" placeholder="Message" id="msg-content"></textarea>
								</div>
								<div class="form-group">
									<button class="btn btn-primary form-control" id="sendMessage">Send Message</button>
								</div>
							</div>
							
						</div>

					</div>

				</div>

			</section>

			<!-- CLOSING
			================================= -->
			<section id="closing" class="closing-section section-dark section">

				<div class="section-background">

					<!-- IMAGE BACKGROUND -->
					<div class="section-background-image parallax" data-stellar-ratio="0.4">
						<img src="images/backgrounds/11.jpg" alt="offshore helicopter view" style="opacity: 0.15;">
					</div>

				</div>

				<div class="container">

					<h3 class="closing-shout">Ready to start? Take the first step by clicking the button below</h3>

					<div class="closing-buttons" data-animation="tada"><a href="#hero" class="anchor-link btn btn-lg btn-primary" data-toggle="modal" data-target="#signupModal">Sign Up Now</a></div>

				</div>

			</section>

			<!-- FOOTER
			================================= -->
			<section id="footer" class="footer-section section">

				<div class="container">

					<p class="footer-logo">
						<!-- <img src="images/logos/footer-logo.png" srcset="images/logos/footer-logo@2x.png 2x" alt="Drew"> -->
						HELICOPTERS OFFSHORE
					</p>

					<div class="footer-socmed">
						<a href="https://www.facebook.com/HelicoptersOffshore" target="_blank"><span class="fa fa-facebook"></span></a>
						<a href="https://twitter.com/Heli_Offshore" target="_blank"><span class="fa fa-twitter"></span></a>
						<a href="https://plus.google.com/+HelicoptersoffshoreManagement/about" target="_blank"><span class="fa fa-google-plus"></span></a>
					</div>

					<div class="footer-copyright">
						&copy; 2015 Flux Solutions
					</div>

				</div>

			</section>
		
		</div>
		<div class="modal" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="viewLog" aria-hidden="true">
	      <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h3 class="modal-title text-center">Log In</h3>
	        </div>
	        <div class="modal-body">
	            <form action="../assets/php/login.php" method="post" class="text-center" id="loginForm">
					<div class="form-group">
						<label for="user">Username</label>
						<input type="text" id="user" class="form-control" name="user" required="required" placeholder="Username" />
					</div>
					<div class="form-group">
					<label for="password">Password</label>
					<input type="password" id="password" class="form-control" name="password" required="required" placeholder="Password" />
					</div>
					<div class="checkbox" style="text-align: left;">
						<label>
							<input type="checkbox" name="keepSignedIn" value="Yes"/>
							Keep me signed in
						</label>
					</div>
					<div class="form-group">
					<input type="button" id="loginBtn" class="form-control btn btn-primary" value="Log In" onclick="logIn(this.form, this.form.password);">
					</div>
				</form>
	       </div>
	       <div class="modal-footer">
	        Not yet a member? <button class="btn btn-primary btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#signupModal">Sign up here</button>
	       </div>
	      </div>
	      </div>
	    </div>

	    <div class="modal" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="viewLog" aria-hidden="true">
	      <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h3 class="modal-title text-center">Sign Up</h3>
	        </div>
	        <div class="modal-body">
	            <form action="../assets/php/signup.php" method="post" class="text-center" id="signUpForm">
	            	<div class='step' data-step="1">
		            	<h3 class="page-header">Account Information</h3>
						<div class="form-group">
							<label for="account">Account Name or Company*</label>
							<input type="text" id="account" class="form-control" name="account" required="required" />
						</div>
						<div class="form-group">
							<label for="nationality">Operation Nationality* <div class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="Used in forms. ex: 'Angolan' Visa"></div></label>
							<input type="text" id="nationality" class="form-control" name="nationality"/>
						</div>
					<!-- <div class="form-group">
						<label for="maxSeven">Maximum Hours in 7 Days*</label>
						<input type="text" id="maxSeven" class="form-control" name="maxSeven"/>
					</div>
					<div class="form-group">
						<label for="max28">Maximum Hours in 28 Days*</label>
						<input type="text" id="max28" class="form-control" name="max28"/>
					</div>
					<div class="form-group">
						<label for="maxShifts">Maximum Shifts In a Row*</label>
						<input type="text" id="maxShifts" class="form-control" name="maxShifts"/>
					</div> -->
						<div class="form-group">
							<input type="button" class="form-control btn btn-primary col-md-6 step-btn" value="Next" data-increment="1">
						</div>
					</div>
					<div class="step" data-step="2">
						<h3 class="page-header">User Information</h3>
						<div class="form-group">
							<label for="firstname">First Name*</label>
							<input type="text" id="firstname" class="form-control" name="firstname"/>
						</div>
						<div class="form-group">
							<label for="lastname">Last Name*</label>
							<input type="text" id="lastname" class="form-control" name="lastname"/>
						</div>
						<div class="form-group">
							<label for="user-nationality">Nationality</label>
							<input type="text" id="user-nationality" class="form-control" name="user-nationality"/>
						</div>
						<div class="form-group">
							<label for="email">Email*</label>
							<input type="text" id="email" class="form-control" name="email"/>
						</div>
						<div class="form-group">
							<label for="phone">Cellular Phone</label>
							<input type="text" id="phone" class="form-control" name="phone"/>
						</div>
						<div class="form-group col-md-6">
							<input type="button" class="form-control btn btn-primary col-md-6 step-btn" value="Back" data-increment="-1">
						</div>
						<div class="form-group col-md-6">
							<input type="button" class="form-control btn btn-primary col-md-6 step-btn" value="Next" data-increment="1">
						</div>
					</div>
					<div class="step" data-step="3">
						<h3 class="page-header">Log in Information</h3>
						<div class="form-group">
							<label for="username">Username*<span id="usernameTaken"></span></label>
							<input type="text" id="username" class="form-control" name="username" placeholder="Choose a username"/>
						</div>
						<div class="form-group">
							<label for="password">Password*</label>
							<input type="password" id="password" class="form-control" name="password" placeholder="Enter a password"/>
						</div>
						<div class="form-group">
							<label for="confpassword">Confirm Password*</label>
							<input type="password" id="confpassword" class="form-control" name="confpassword" placeholder="Re-enter password"/>
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" id="user-agreement-check"> I hereby agree to the <a data-toggle="modal" data-target="#user-agreement">Terms of Use.</a>
							</label>
						</div>
						<div class="form-group col-md-6">
							<input type="button" class="form-control btn btn-primary col-md-6 step-btn" value="Back" data-increment="-1">
						</div>
						<div class="form-group col-md-6">
							<input type="button" id="loginBtn" class="form-control btn btn-primary" value="Sign Up Now!" onclick="signUp(this.form);">
						</div>
					</div>
					<p class="outer-top-xxs outer-bottom-xxs">* Required Information</p>
				</form>
	       </div>
	       <div class="modal-footer">
	        Already have an account? <button class="btn btn-primary btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#loginModal">Log In</button>
	       </div>
	      </div>
	      </div>
	    </div>

	    <div class="modal" id="testListModal" tabindex="-1" role="dialog" aria-labelledby="viewLog" aria-hidden="true">
	      <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h3 class="modal-title text-center">Our Tests</h3>
	        </div>
	        <div class="modal-body">
	            <ul>
	            	<li>Angolan License</li>
	            	<li>Foreigner License</li>
	            	<li>Instrument Val</li>
	            	<li>Medical</li>
	            	<li>English Level</li>
	            	<li>Simulator</li>
	            	<li>C.R.M</li>
	            	<li>Base Check</li>
	            	<li>Line Check</li>
	            	<li>I.F.R Check</li>
	            	<li>I.F.R Currency</li>
	            	<li>Night Check</li>
	            	<li>Night Rig Currency</li>
	            	<li>Dangerous Goods</li>
	            	<li>HUET</li>
	            	<li>First Aid Training</li>
	            	<li>Basic Fire Fighting</li>
	            	<li>HERDS</li>
	            	<li>HOIST</li>
	            	<li>HOOK</li>
	            	<li>Passport</li>
	            	<li>Angolan Visa</li>
	            	<li>US Visa</li>
	            	<li>Caderneta De Voo</li>
	            </ul>
	       </div>
	      </div>
	      </div>
	    </div>

	    <!-- === USER AGREEMENT =============== -->
	    <div class="modal" id="user-agreement" tabindex="-1" role="dialog" aria-labelledby="viewLog" aria-hidden="true">
		    <div class="modal-dialog">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		          <h3 class="modal-title text-center">User Agreement</h3>
		        </div>
		        <div class="modal-body">
		          <p class="text-center">By using Helicopters-Offshore.com you are agreeing to the following:</p>
		          <h2 class="text-center">Web Site Terms and Conditions of Use</h2>

		          <h3>1. Terms</h3>
		          <p>
		            By accessing this web site, you are agreeing to be bound by these 
		            web site Terms and Conditions of Use, all applicable laws and regulations, 
		            and agree that you are responsible for compliance with any applicable local 
		            laws. If you do not agree with any of these terms, you are prohibited from 
		            using or accessing this site. The materials contained in this web site are 
		            protected by applicable copyright and trade mark law.
		          </p>
		          
				  <h3>2. Use License</h3>
		          <ol type="a">
		            <li>
		              Permission is granted to temporarily download one copy of the materials 
		              (information or software) on Helicopters Offshore's web site for personal, 
		              non-commercial transitory viewing only. This is the grant of a license, 
		              not a transfer of title, and under this license you may not:
		              <ol type="i">
		                <li>modify or copy the materials;</li>
		                <li>use the materials for any commercial purpose, or for any public display (commercial or non-commercial);</li>
		                <li>attempt to decompile or reverse engineer any software contained on Helicopters Offshore's web site;</li>
		                <li>remove any copyright or other proprietary notations from the materials; or</li>
		                <li>transfer the materials to another person or "mirror" the materials on any other server.</li>
		              </ol>
		            </li>
		            <li>
		              This license shall automatically terminate if you violate any of these restrictions and may be terminated by Helicopters Offshore at any time. Upon terminating your viewing of these materials or upon the termination of this license, you must destroy any downloaded materials in your possession whether in electronic or printed format.
		            </li>
		          </ol>

		          <h3>3. Disclaimer</h3>
                <ol type="a">
                    <li>
                        The materials on Helicopters Offshore's web site are provided "as is". Helicopters Offshore makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties, including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights. Further, Helicopters Offshore does not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the materials on its Internet web site or otherwise relating to such materials or on any sites linked to this site.
                    </li>
		          </ol>

		          <h3>4. Limitations</h3>
                <p>
                    In no event shall Helicopters Offshore or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption,) arising out of the use or inability to use the materials on Helicopters Offshore's Internet site, even if Helicopters Offshore or a Helicopters Offshore authorized representative has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.
                </p>

                <h3>5. Revisions and Errata</h3>
                <p>
                    The materials appearing on Helicopters Offshore's web site could include technical, typographical, or photographic errors. Helicopters Offshore does not warrant that any of the materials on its web site are accurate, complete, or current. Helicopters Offshore may make changes to the materials contained on its web site at any time without notice. Helicopters Offshore does not, however, make any commitment to update the materials.
                </p>

                <h3>6. Links</h3>
                <p>
                    Helicopters Offshore has not reviewed all of the sites linked to its Internet web site and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by Helicopters Offshore of the site. Use of any such linked web site is at the user's own risk.
                </p>

				<h3>7. Site Terms of Use Modifications</h3>
                <p>
                    Helicopters Offshore may revise these terms of use for its web site at any time without notice. By using this web site you are agreeing to be bound by the then current version of these Terms and Conditions of Use.
                </p>

                <h3>8. Governing Law</h3>
                <p>
                    Any claim relating to Helicopters Offshore's web site shall be governed by the laws of the State of Quebec without regard to its conflict of law provisions.
                </p>
                <p>
                    General Terms and Conditions applicable to Use of a Web Site.
                </p>

                <h2>Privacy Policy</h2>
                <p>
                    Your privacy is very important to us. Accordingly, we have developed this Policy in order for you to understand how we collect, use, communicate and disclose and make use of personal information. The following outlines our privacy policy.
                </p>
		          <ul>
		            <li>
		              Before or at the time of collecting personal information, we will identify the purposes for which information is being collected.
		            </li>
		            <li>
		              We will collect and use of personal information solely with the objective of fulfilling those purposes specified by us and for other compatible purposes, unless we obtain the consent of the individual concerned or as required by law.   
		            </li>
		            <li>
		              We will only retain personal information as long as necessary for the fulfillment of those purposes. 
		            </li>
		            <li>
		              We will collect personal information by lawful and fair means and, where appropriate, with the knowledge or consent of the individual concerned. 
		            </li>
		            <li>
		              Personal data should be relevant to the purposes for which it is to be used, and, to the extent necessary for those purposes, should be accurate, complete, and up-to-date. 
		            </li>
		            <li>
		              We will protect personal information by reasonable security safeguards against loss or theft, as well as unauthorized access, disclosure, copying, use or modification.
		            </li>
		            <li>
		              We will make readily available to customers information about our policies and practices relating to the management of personal information. 
		            </li>
		          </ul>
		          <p>
		            We are committed to conducting our business in accordance with these principles in order to ensure that the confidentiality of personal information is protected and maintained. 
		          </p>    
		        </div>
		      </div>
		    </div>
		</div>
		<!-- JAVASCRIPT
		================================= -->
		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/respimage.min.js"></script>
		<script src="js/jpreloader.min.js"></script>
		<script src="js/smoothscroll.min.js"></script>
		<script src="js/jquery.nav.min.js"></script>
		<script src="js/jquery.inview.min.js"></script>
		<script src="js/jquery.counterup.min.js"></script>
		<script src="js/jquery.stellar.min.js"></script>
		<!-- // <script src="js/maplace-0.1.3.min.js"></script> -->
		<script src="js/script.js"></script>
		<script src="../assets/js/sha512.js"></script>
		<script src="../assets/lib/noty/packaged/jquery.noty.packaged.js"></script>
		<script src="../assets/js/loginfunctions.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=weather"></script>
		<script type="text/javascript">
			var step = 1;
			$(document).ready(function(){
				var mapOptions = {
					    zoom: 10,
					    center: new google.maps.LatLng(45.461212, -73.862920)
					};
				map = new google.maps.Map($("#gmap")[0], mapOptions);

				$("#sendMessage").click(function(){
					var email = $("#msg-email").val(),
						name = $("#msg-name").val(),
						msg = $("#msg-content").val();
					if(msg != "" && msg != null && email != "" && email != null){
						//submit & email us
						var that = this;
						$.ajax({
							type: "POST",
							url: "../assets/php/submit_thoughts.php",
							data: {msg: msg, name: name, email: email, landing: true},
							success: function(result){
								console.log(result);
								if(result == "success"){
									var n = noty({
				                        layout: "top",//layout, // top, topLeft, topCenter, topRight, centerLeft, center, centerRight, bottomLeft, bottomCenter, bottomRight, bottom
				                        type: "success",     // alert, success, error, warning, information, confirm (needs button options)
				                        text: "Your message was sent successfully. We'll get back to you shortly.",     // text or HTML
				                        timeout: 10000,  // time in ms before notification disappears
				                        killer: true    // "kills" all other notifications
				                    });
								}else{
									var n = noty({
				                        layout: "top",//layout, // top, topLeft, topCenter, topRight, centerLeft, center, centerRight, bottomLeft, bottomCenter, bottomRight, bottom
				                        type: "error",     // alert, success, error, warning, information, confirm (needs button options)
				                        text: "Something went wrong with sending the message. We're looking in to it.",     // text or HTML
				                        timeout: 10000,  // time in ms before notification disappears
				                        killer: true    // "kills" all other notifications
				                    });
								}
							}
						})
					}else{
						var n = noty({
	                        layout: "top",//layout, // top, topLeft, topCenter, topRight, centerLeft, center, centerRight, bottomLeft, bottomCenter, bottomRight, bottom
	                        type: "error",     // alert, success, error, warning, information, confirm (needs button options)
	                        text: "Please enter your email and a message.",     // text or HTML
	                        timeout: 10000,  // time in ms before notification disappears
	                        killer: true    // "kills" all other notifications
	                    });
					}
				});

				$(".step").hide();
				$(".step[data-step='1']").show();
				$(".step-btn").click(function(){
					step += parseInt($(this).data("increment"));
					$(".step").hide();
					$(".step[data-step='"+step+"']").show();
				})
			})
		</script>
	</body>

</html>