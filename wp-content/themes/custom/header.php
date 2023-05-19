<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Male-Fashion | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?> /css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?> /css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?> /css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?> /css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?> /css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?> /css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?> /css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?> /css/style.css" type="text/css">
</head>

<body>
    <!-- Page Preloder -->
   <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__option">
            <div class="offcanvas__links">
                <a href="">Sign in</a>
                <a href=""> Login</a>
                <a href="#">FAQs</a>
            </div>
            <div class="offcanvas__top__hover">
                <span>Usd <i class="arrow_carrot-down"></i></span>
                <ul>
                    <li>USD</li>
                    <li>EUR</li>
                    <li>USD</li>
                </ul>
            </div>
        </div>
        <div class="offcanvas__nav__option">
            <a href="#" class="search-switch"><img src="<?php bloginfo('template_directory') ?> /img/icon/search.png" alt=""></a>
            <a href="#"><img src="<?php bloginfo('template_directory') ?> /img/icon/heart.png" alt=""></a>
            <a href="#"><img src="<?php bloginfo('template_directory') ?> /img/icon/cart.png" alt=""> <span>0</span></a>
            <div class="price">$0.00</div>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__text">
            <p>Free shipping, 30-day return or refund guarantee.</p>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-7">
                        <div class="header__top__left">
                            <p>Free shipping, 30-day return or refund guarantee.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-5">
                        <div class="header__top__right">
                            <div class="header__top__links">
                                
                                <a href="#">Login</a>
                                <a href="#">Sign in</a>
                                <a href="#">FAQs</a>
                            </div>
                            <div class="header__top__hover">
                                <span>Usd <i class="arrow_carrot-down"></i></span>
                                <ul>
                                    <li>USD</li>
                                    <li>EUR</li>
                                    <li>USD</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="header__logo">
                        <a href="<?php bloginfo('template_directory') ?>/index.php"><img src="<?php bloginfo('template_directory') ?> /img/logo.png" alt=""></a>
                    </div>
                </div>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location'=>'primary_menu',
                        'menu_class'=>'custom_nav'
                    )
                )
                ?>
                <div class="col-lg-3 col-md-3">
                    <div class="header__nav__option">
                        <a href="#" class="search-switch"><img src="<?php bloginfo('template_directory') ?> /img/icon/search.png" alt=""></a>
                        <a href="#"><img src="<?php bloginfo('template_directory') ?> /img/icon/heart.png" alt=""></a>
                        <a href="#"><img src="<?php bloginfo('template_directory') ?> /img/icon/cart.png" alt=""> <span>0</span></a>
                        <div class="price">$0.00</div>
                    </div>
                </div>
            </div>
            <div class="canvas__open"><i class="fa fa-bars"></i></div>
        </div>
    </header>
    <!-- Header Section End -->