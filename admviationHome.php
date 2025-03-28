<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

include_once "db_connect.php";

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
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#loginModal">Log In</a>
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
            <!-- <a href="#about" class="hero-start-link anchor-link"><span class="fa fa-angle-double-down"></span></a> -->
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
            <!-- Content for "about" section -->
        </section>

        <section id="benefits" class="benefits-section section-gray section">
            <!-- Content for "benefits" section -->
        </section>

        <!-- THIS SECTION BELOW IS USED FOR TESTING -->

        <section id="how-it-works" class="how-it-works-section section">
        <!-- Content for "How it Works" section -->
        </section>

        <section id="future" class="future-section section">
            <!-- Content for "Our Goals" section -->
        </section>

        <section id="pricing" class="pricing-section section">
            <!-- Content for "Pricing" section -->
        </section>
        <!-- <section id="headline" class="headline-section section"> -->
            <!-- Content for "Headline" section -->
        <!-- </section> -->

        <!-- THIS SECTION ABOVE IS USED FOR TESTING -->

        <!-- Login Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="modal-title text-center" id="loginModalLabel">Log In</h3>
                    </div>
                    <div class="modal-body">
                        <form action ="login.php" class="text-center" id="loginForm" onsubmit="logIn(this);return false;">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="user" class="form-control" name="username" required placeholder="Username" />
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" class="form-control" name="password" required placeholder="Password" />
                            </div>
                            <div class="checkbox" style="text-align: left;">
                                <label>
                                    <input type="checkbox" name="keepSignedIn" value="Yes" />
                                    Keep me signed in
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <input type="submit" id="loginBtn" class="form-control btn btn-primary" value="Log In">
                            </div>
                            <div id="loginError" class="text-danger"></div>
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


</body>

</html>