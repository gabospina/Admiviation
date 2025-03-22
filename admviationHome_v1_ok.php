<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// include_once "assets/php/db_connect.php";
include_once "db_connect.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//TEMPORARILY COMMENT OUT THIS SECTION FOR TESTING
// if(isset($_SESSION["HeliUser"])){
//    header("Location: hangarLdng.php");
//    exit();
// }

// DEBUG: Print the session
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- BASIC INFO -->
    <title>Admviation</title>
    <meta name="keywords" content="helicopters,helicopter,offshore, helicopters offshore, helicopter offshore, offshore helicopters,scheduling,flying, flight,schedule,pilots,statistics,log book,log,Angola,management,pilot,planner,easy,simple,software,cloud,web application, application">
    <meta name="description" content="Helicopters Offshore offers a way to easily manage schedules, training, tests expiration, keeping a logbook, and keeping in touch within your community.">

    <!-- FAVICONS -->
    <link rel="icon" href="images/favicons/favicon.ico">

    <!-- CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800">
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/animate.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link id="color-css" rel="stylesheet" href="css/colors/orange.css" type="text/css">
   
</head>

<!-- <body class="enable-animations enable-preloader"> -->
<body>    

    <div id="document" class="document">

        <!-- HEADER -->
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
                        Admviation - HELICOPTERS OFFSHORE
                    </a>
                </div>
                <nav id="navigation" class="navigation navbar-collapse collapse navbar-right">

                    <!-- NAVIGATION LINKS -->
                    <ul id="header-nav" class="nav navbar-nav">
                        <li><a href="#hero" class="hidden">Top</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#benefits">Features</a></li>
                        <li><a href="#how-it-works">How it Works</a></li>
                        <li><a href="#future">Our Goals</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                        <li><a href="news.php">News</a></li>

                        <!-- HEADER ACTION BUTTON -->
                        <li class="header-action-button">
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#signupModal">Sign
                                Up</a>
                        </li>
                        <li class="header-action-button">
                            <a href="#" data-toggle="modal" data-target="#loginModal" class="btn btn-primary">Log In</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>

        <!-- HERO -->
        <section id="hero" class="hero-section hero-layout-simple hero-fullscreen section section-dark">
            <div class="section-background">

                <!-- IMAGE BACKGROUND -->
                <div class="section-background-image parallax" data-stellar-ratio="0.4">
                    <img src="images/backgrounds/head_banner.jpg" alt="helictoper dashboard" style="opacity: 0.3;">
                </div>
            </div>

            <div class="container">
                <div class="hero-content">
                    <div class="hero-content-inner">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="hero-heading" data-animation="fadeIn">
                                    <h1 class="hero-title">Helicopter management made easy</h1>
                                    <p class="hero-tagline">We help you organize all aspects of helicopter piloting and
                                        management.</p>
                                </div>
                                <p class="hero-buttons">
                                    <a href="#about" class="btn btn-lg btn-default anchor-link">Learn More</a>
                                    <a href="#" data-toggle="modal" data-target="#signupModal"
                                        class="btn btn-lg btn-primary">Sign Up Now</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#headline" class="hero-start-link anchor-link"><span class="fa fa-angle-double-down"></span></a>
        </section>

        <!-- Rest of your HTML content here -->
        <section id="headline" class="headline-section section-gray section">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <p class="headline-text">
                            Designed by people in the business, <strong>HELICOPTERS OFFSHORE</strong> makes managing
                            your workflow, keeping track of records and schedules, and connecting with others simple and
                            time effective.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="about-section section">
            <div class="container">
                <h2 class="section-heading text-center">About Helicopters Offshore</h2>
                <div class="about-row row">
                    <div class="about-image col-md-6" data-animation="fadeIn">
                        <img src="images/backgrounds/about_us_2.jpg" alt="Angolan helicopter fleet"
                            style="border-radius: 15px;">
                    </div>
                    <div class="about-text col-md-6">
                        <p class="lead">Our duty is to make your duty simple.</p>
                        <p>At <strong>HELICOPTERS OFFSHORE</strong>, one of our many visions is <u>planning made
                                easy</u>. <i>How can we help make you job easier?</i> We've come up with a way for you
                            to easily keep track and manage all of the things that are important to you. How do we know?
                            Because we're in the business too. Although we're designed for offshore helicopters in
                            Angola, our features can be applied worldwide! Here's some of our features:</p>
                        <ul class="icon-list">
                            <li>
                                <span class="icon-list-icon fa fa-calendar" data-animation="bounceIn"></span>
                                <h4 class="icon-list-title">Scheduling</h4>
                                <p>From managing your weekly flights, to your training schedules, we've got it.</p>
                            </li>
                            <li>
                                <span class="icon-list-icon fa fa-users" data-animation="bounceIn"></span>
                                <h4 class="icon-list-title">Community</h4>
                                <p>We've created a community for helicopter pilots. Communicate, share, explore. We keep
                                    you and your colleagues in touch</p>
                            </li>
                            <li>
                                <span class="icon-list-icon fa fa-clock-o" data-animation="bounceIn"></span>
                                <h4 class="icon-list-title">Keep Track</h4>
                                <p>With HELICOPTER OFFSHORE, we keep track for you. From log books, to tracking when
                                    your tests expire, to showing you your flight statistics. All of your data is in one
                                    place.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section id="benefits" class="benefits-section section-gray section">
            <div class="container">
                <h2 class="section-heading text-center">More Features</h2>
                <div class="benefits-row row">
                    <div class="col-md-4 col-sm-6">
                        <div class="benefit">
                            <span class="benefit-icon fa fa-user-plus" data-animation="bounceIn"></span>
                            <h4 class="benefit-title">Adding Pilots</h4>
                            <p class="benefit-description">Adding a pilot to your fleet is just a few keys and a click
                                away. You can keep track of test expiration, contact information, and enable/disable
                                aircrafts, contracts and positions. Rather than searching through piles of paper, just
                                type the name and your results are there. Creating a pilot creates a user. That pilot
                                can now sign in and keep track of their own statistics, view their scheduling, and
                                connect to your community.</p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="benefit">
                            <span class="benefit-icon fa fa-bell" data-animation="bounceIn"></span>
                            <h4 class="benefit-title">Notifications</h4>
                            <p class="benefit-description">With just a click of the button, you can easily notify all of
                                the scheduled pilots when and what they're flying. We send them a message straight to
                                their phone telling them all the details. At the same time, we get you the log sheets
                                you'll need to keep track of their hours at the end of the day. We even give you a
                                record of all the messages you've sent and whether or not they were delivered
                                successfully.</p>
                        </div>
                    </div>

                    <div class="hidden-md hidden-lg clear"></div>

                    <div class="col-md-4 col-sm-6">
                        <div class="benefit">
                            <span class="benefit-icon fa fa-dashboard" data-animation="bounceIn"></span>
                            <h4 class="benefit-title">Flight Schedule</h4>
                            <p class="benefit-description">The first thing you see when you log in is your schedule. We
                                know you've got a bunch of things to keep track of, so for your schedule, we've got you
                                covered. You can easily see when and what you're flying throughout the week. For
                                management, we cut your planning time by 50% giving you more time to enjoy yourself.
                                Keeping track of who's available, who flew a night shift, who's flown too much, who's
                                test are expired; We do it so you don't have to. Simply click the position and select
                                the pilot. That's it!</p>
                        </div>
                    </div>

                    <div class="hidden-sm clear"></div>

                    <div class="col-md-4 col-sm-6">
                        <div class="benefit">
                            <span class="benefit-icon fa fa-calendar" data-animation="bounceIn"></span>
                            <h4 class="benefit-title">Training Schedule</h4>
                            <p class="benefit-description">In addition to our key flight schedule, we have the training
                                schedule. Plan your trainers, examiners, and trainees all in one place. We know who's
                                available so just click a time period and we'll show you a list.</p>
                        </div>
                    </div>

                    <div class="hidden-md hidden-lg clear"></div>

                    <div class="col-md-4 col-sm-6">
                        <div class="benefit">
                            <span class="benefit-icon fa fa-file-text-o" data-animation="bounceIn"></span>
                            <h4 class="benefit-title">Tests and Paperwork</h4>
                            <p class="benefit-description">Easily keep track of when your documents and tests are going
                                to expire. For management, easily print out lists of pilots who's validity is going to
                                expire or already has. Click <a href="#" data-toggle="modal"
                                    data-target="#testListModal">here</a> for a list of all the tests we keep track of.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="benefit">
                            <span class="benefit-icon fa fa-book" data-animation="bounceIn"></span>
                            <h4 class="benefit-title">Crafts And Contracts</h4>
                            <p class="benefit-description">Keep track of all your aircrafts and contracts. Simply add
                                the model and registration, whether it flies at night or during the day <i>(used by the
                                    schedule)</i> and even keep track of whether or not it's out for maintenance. Then
                                you can add any of your new aircrafts to a contract, pick a fancy color, and get started
                                scheduling.</p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="benefit">
                            <span class="benefit-icon fa fa-file-o" data-animation="bounceIn"></span>
                            <h4 class="benefit-title">Documents</h4>
                            <p class="benefit-description">Upload important documents that you need your fleet to see.
                                We also keep track of who has viewed the document so you can know who to contact.</p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="benefit">
                            <span class="benefit-icon fa fa-key" data-animation="bounceIn"></span>
                            <h4 class="benefit-title">Permission Levels</h4>
                            <p class="benefit-description">We know that you might have different levels of users in your
                                account. We let you set what your users have access to. For instance, pilots can't edit
                                the flight schedule but managers can. You can give control to users that need it.</p>
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
                                <p class="hiw-item-description">Add some pilots to your fleet. Set their contact
                                    information, permissions, contracts and crafts, positions and tests and that's it!
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- HOW IT WORKS - ITEM 2 -->
                    <div class="col-md-3 col-sm-6" data-animation="fadeIn">
                        <div class="hiw-item even">
                            <img class="hiw-item-picture" src="images/backgrounds/contract.png"
                                alt="aircraft management">
                            <div class="hiw-item-text">
                                <span class="hiw-item-icon">2</span>
                                <h4 class="hiw-item-title">Crafts And Contracts</h4>
                                <p class="hiw-item-description">Add aircrafts to manage and schedule, and create some
                                    contracts. Simply select which pilots can fly on that contract, pick the aircrafts,
                                    choose a color and you're done. You can rearrange the order just by dragging.</p>
                            </div>
                        </div>
                    </div>

                    <div class="hidden-md hidden-lg clear"></div>

                    <!-- HOW IT WORKS - ITEM 3 -->
                    <div class="col-md-3 col-sm-6" data-animation="fadeIn">
                        <div class="hiw-item">
                            <img class="hiw-item-picture" src="images/backgrounds/schedule.png"
                                alt="helicopter scheduling">
                            <div class="hiw-item-text">
                                <span class="hiw-item-icon">3</span>
                                <h4 class="hiw-item-title">Schedule Away</h4>
                                <p class="hiw-item-description">With just a few clicks you can be done your week's
                                    schedule. What used to take hours can now take minutes. No more stressing over
                                    papers. If there's a change, just click and select someone else.</p>
                            </div>
                        </div>
                    </div>

                    <!-- HOW IT WORKS - ITEM 4 -->
                    <div class="col-md-3 col-sm-6" data-animation="fadeIn">
                        <div class="hiw-item even">
                            <img class="hiw-item-picture" src="images/backgrounds/how_it_works_banner_2.jpg"
                                alt="helicopter flying">
                            <div class="hiw-item-text">
                                <span class="hiw-item-icon">4</span>
                                <h4 class="hiw-item-title">Relax & Fly!</h4>
                                <p class="hiw-item-description">We take care of all the heavy lifting so you can do you
                                    job without the stress. It really can be that easy.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- NUMBERS - REMOVED DUE TO CAUSE ERROR DISPLAYING THE WEBPAGE 
				================================= -->

        <section id="numbers" class="numbers-section section-dark section">
            <div class="section-background">
                <div class="section-background-image parallax" data-stellar-ratio="0.4">
                <img src="images/backgrounds/numbers_banner_2.jpg" alt="helicopter statistics" style="opacity: 0.2;">
                </div>
            </div>

            <div class="container">
                <h2 class="section-heading text-center">Our Numbers</h2>
                <div class="numbers-row row">
                <div class="col-md-3 col-sm-6">
                    <div class="numbers-item">
                    <div class="numbers-item-counter"><span class="counter-up">2</span></div>
                    <div class="numbers-item-caption">Stage</div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="numbers-item">
                    <div class="numbers-item-counter"><span class="counter-up">95</span>%</div>
                    <div class="numbers-item-caption">Complete</div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="numbers-item">
                    <div class="numbers-item-counter"><span class="counter-up">
                        <!--  -->
                    </div>
                    <div class="numbers-item-caption">Total Users</div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="numbers-item">
                    <div class="numbers-item-counter"><span class="counter-up">
                        
                    </div>
                    <div class="numbers-item-caption">Crafts</div>
                    </div>
                </div>
                </div>
            </div>
        </section>
        

        <!-- VIDEO SECTION
			================================= -->

        <!-- TEAM
			================================= -->

        <!-- TEAM MEMBER 6 -->

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
                        <p>You can easily stay in contact with all of your fellow pilots with our community. Share.
                            Read. Belong. See what your fellow pilots are up to and contribute to your community.</p>
                        <ul class="nice-list">
                            <li><strong>News Feed</strong> <small>NEW</small>. Find out all the important updates around
                                the world that apply to pilots.</li>
                            <li><strong>Messaging</strong> <small>NEW</small>. If you want to send a private message,
                                we've got that too. For quick messaging in the workplace, there's our messaging center.
                            </li>
                            <li><strong>Store</strong> <small>Coming Soon!</small>. List and purchase things from your
                                fellow colleagues. From headsets to computers, it's a digital garage sale.</li>
                            <li><strong>Posts</strong> <small>Coming Soon!</small>. Stay in the know and see what your
                                friends are talking about. Have something on your mind? Just post about it.</li>
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
                        <p>Right now, It's free to try it out, see our designs, and we can customize it to fit your
                            needs</p>
                    </div>
                </div>
                <!-- PRICING TABLE -->
            </div>
        </section>

        <!-- TESTIMONIALS
			================================= -->

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
                        <p>To see if we can fit your pilots' schedule and and fleet management requirements, just send
                            us a message. We'd love to hear from you and get you on board.</p>
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
                                <textarea cols="54" rows="8" class="form-control" placeholder="Message"
                                    id="msg-content"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary form-control" id="sendMessage">Send Message</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CLOSING ================================= -->
        <section id="closing" class="closing-section section-dark section">
            <div class="section-background2">
                <!-- IMAGE BACKGROUND -->
                <div class="section-background2-image parallax" data-stellar-ratio="0.4">
                    <img src="images/backgrounds/11.jpg" alt="offshore helicopter view" style="opacity: 1.0;">
                </div>
            </div>

            <div class="container">
                <h3 class="closing-shout">Ready to start? Take the first step by clicking the button below</h3>

                <div class="closing-buttons" data-animation="tada"><a href="#hero"
                        class="anchor-link btn btn-lg btn-primary" data-toggle="modal" data-target="#signupModal">Sign
                        Up Now</a></div>
            </div>
        </section>

        <!-- FOOTER
			================================= -->
        <section id="footer" class="footer-section section">
            <div class="container">
                <p class="footer-logo">
                    HELICOPTERS OFFSHORE
                </p>

                <div class="footer-socmed">
                    <a href="https://www.facebook.com/HelicoptersOffshore" target="_blank"><span
                            class="fa fa-facebook"></span></a>
                    <a href="https://twitter.com/Heli_Offshore" target="_blank"><span class="fa fa-twitter"></span></a>
                    <a href="https://plus.google.com/+HelicoptersoffshoreManagement/about" target="_blank"><span
                            class="fa fa-google-plus"></span></a>
                </div>

                <div class="footer-copyright">
                    © 2015 Flux Solutions
                </div>
            </div>
        </section>
    </div>

    <!-- Login Modal -->
    <div class="modal" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="modal-title text-center" id="loginModalLabel">Log In</h3>
                    </div>
                    <div class="modal-body">
                        <!-- <form action="../assets/php/login.php" method="post" class="text-center" id="loginForm"> -->
                        <form action="login.php" method="post" class="text-center" id="loginForm">
                            <div class="form-group">
                                <label for="user">Username</label>
                                <input type="text" id="user" class="form-control" name="user" required="required"
                                    placeholder="Username" />
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" class="form-control" name="password"
                                    required="required" placeholder="Password" />
                            </div>
                            <div class="checkbox" style="text-align: left;">
                                <label>
                                    <input type="checkbox" name="keepSignedIn" value="Yes" />
                                    Keep me signed in
                                </label>
                            </div>
                            <div class="form-group">
                                <!-- <input type="button" id="loginBtn" class="form-control btn btn-primary" value="Log In"> -->
                                <input type="button" id="loginBtn" class="form-control btn btn-primary" value="Log In"
                                    onclick="logIn(this.form, this.form.password);">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        Not yet a member? <button class="btn btn-primary btn-sm" data-dismiss="modal" data-toggle="modal"
                            data-target="#signupModal">Sign up here</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- SignupModal -->

        <div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="modal-title text-center" id="signupModalLabel">Sign Up</h3>
                    </div>
                    <div class="modal-body">
                        <form action="signup.php" method="post" class="text-center" id="signUpForm">
                            <div class='step' data-step="1">
                                <h3 class="page-header">Account Information</h3>
                                <div class="form-group">
                                    <label for="signup_companyName">Account Name or Company*</label>
                                    <input type="text" id="signup_companyName" class="form-control" name="companyName" required="required" />
                                </div>
                                <div class="form-group">
                                    <label for="signup_companyNationality">Operation Nationality* <div class="fa fa-question-circle"
                                        data-toggle="tooltip" data-placement="left"
                                        title="Used in forms. ex: 'Angolan' Visa"></div>
                                    </label>
                                    <input type="text" id="signup_companyNationality" class="form-control" name="companyNationality" />
                                </div>
                            </div>

                            <div class="step" data-step="2">
                                <h3 class="page-header">User Information</h3>
                                <div class="form-group">
                                    <label for="signup_firstname">First Name*</label>
                                    <input type="text" id="signup_firstname" class="form-control" name="firstname" />
                                </div>
                                <div class="form-group">
                                    <label for="signup_lastname">Last Name*</label>
                                    <input type="text" id="signup_lastname" class="form-control" name="lastname" />
                                </div>
                                <div class="form-group">
                                    <label for="signup_user-nationality">Nationality</label>
                                    <input type="text" id="signup_user-nationality" class="form-control" name="user-nationality" />
                                </div>
                                <div class="form-group">
                                    <label for="signup_email">Email*</label>
                                    <input type="text" id="signup_email" class="form-control" name="email" />
                                </div>
                                <div class="form-group">
                                    <label for="signup_phone">Cellular Phone</label>
                                    <input type="text" id="signup_phone" class="form-control" name="phone" />
                                </div>
                            </div>

                            <div class="step" data-step="3">
                                <h3 class="page-header">Log in Information</h3>
                                <div class="form-group">
                                    <label for="signup_username">Username*<span id="usernameTaken"></span></label>
                                    <input type="text" id="signup_username" class="form-control" name="username"
                                        placeholder="Choose a username" />
                                </div>
                                <div class="form-group">
                                    <label for="signup_password">Password*</label>
                                    <input type="password" id="signup_password" class="form-control" name="password"
                                        placeholder="Enter a password" />
                                </div>
                                <div class="form-group">
                                    <label for="signup_confpassword">Confirm Password*</label>
                                    <input type="password" id="signup_confpassword" class="form-control" name="confpassword"
                                        placeholder="Re-enter password" />
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="user-agreement-check"> I hereby agree to the <a
                                            data-toggle="modal" data-target="#user-agreement">Terms of Use.</a>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="button" class="form-control btn btn-primary col-md-6 step-btn" value="Back"
                                        data-increment="-1">
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <input type="button" id="signupBtn" class="form-control btn btn-primary"
                                        value="Sign Up Now!" onclick="signUp(this.form);">
                                </div>
                            </div>

                            <p class="outer-top-xxs outer-bottom-xxs">* Required Information</p>
                        </form>
                        
                    </div>
                    <div class="modal-footer">
                        Already have an account? <button class="btn btn-primary btn-sm" data-dismiss="modal"
                            data-toggle="modal" data-target="#loginModal">Log In</button>
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


    <!-- JAVASCRIPT
		================================= -->
    <!-- <script src="js/jquery-1.11.2.min.js"></script> -->
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/jquery-migrate-3.5.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/respimage.min.js"></script>
    <script src="js/jpreloader.min.js"></script>
    <!-- <script src="js/smoothscroll.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/smoothscroll/1.5.1/SmoothScroll.min.js" integrity="sha512-BkMKSo7sGBeDmtIyZoyInJbrI/XqQ1ez6SZotOL0e+iT6tzOjBmtMpWWPUVbcdqLbaukREItSDV2aohX+9gBlw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/smoothscroll/1.5.1/SmoothScroll.js" integrity="sha512-sFuDRuskXCbVzxMLpIVAwCal9FClhESJ8ojUAMRgh97FDmFs4RW50rSL7888M9Sjl3Uf7t4m3AsNvXmXGc/qXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- <script src="js/jquery.nav.min.js"></script> -->
    <script src="js/jquery.inview.min.js"></script>
    <script src="js/jquery.counterup.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/script.js"></script>
    <!-- <script src="assets/lib/noty/packaged/jquery.noty.packaged.js"></script> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css">
    <!-- Include Noty JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>
    <script src="loginfunctions.js"></script>
    
    <!-- <script type="text/javascript">
        var step = 1;
        $(document).ready(function () {
            var mapOptions = {
                zoom: 10,
                center: new google.maps.LatLng(45.461212, -73.862920)
            };
            map = new google.maps.Map($("#gmap")[0], mapOptions);

            $("#sendMessage").click(function () {
                var email = $("#msg-email").val(),
                    name = $("#msg-name").val(),
                    msg = $("#msg-content").val();
                if (msg != "" && msg != null && email != "" && email != null) {
                    var that = this;
                    $.ajax({
                        type: "POST",
                        url: "../assets/php/submit_thoughts.php",
                        data: { msg: msg, name: name, email: email, landing: true },
                        success: function (result) {
                            console.log(result);
                            if (result == "success") {
                                var n = noty({
                                    layout: "top",
                                    type: "success",
                                    text: "Your message was sent successfully. We'll get back to you shortly.",
                                    timeout: 10000,
                                    killer: true
                                });
                            } else {
                                var n = noty({
                                    layout: "top",
                                    type: "error",
                                    text: "Something went wrong with sending the message. We're looking in to it.",
                                    timeout: 10000,
                                    killer: true
                                });
                            }
                        }
                    })
                } else {
                    var n = noty({
                        layout: "top",
                        type: "error",
                        text: "Please enter your email and a message.",
                        timeout: 10000,
                        killer: true
                    });
                }
            });

            $(".step").hide();
            $(".step[data-step='1']").show();
            $(".step-btn").click(function () {
                step += parseInt($(this).data("increment"));
                $(".step").hide();
                $(".step[data-step='" + step + "']").show();
            })
        })
    </script> -->
</body>

</html>