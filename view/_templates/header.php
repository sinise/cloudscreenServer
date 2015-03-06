<!doctype html>
<html>
<head>
    <!-- META -->
    <meta charset="utf-8">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/style.css" />
</head>
<body>
    <!-- wrapper, to center website -->
    <div class="wrapper">

        <!-- navigation -->
        <ul class="navigation">

            <?php if (Session::userIsLoggedIn()) { ?>
                <li <?php if (View::checkForActiveController($filename, "rpi")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo Config::get('URL'); ?>rpi/index">Manage</a>
                </li>

                <?php if (Session::get('user_account_type') == 3) { ?>
                    <li <?php if (View::checkForActiveController($filename, "overview")) { echo ' class="active" '; } ?> >
                        <a href="<?php echo Config::get('URL'); ?>profile/index">Users</a>
                    </li>
                    <li <?php if (View::checkForActiveControllerAndAction($filename, "login/register")) { echo ' class="active" '; } ?> >
                        <a href="<?php echo Config::get('URL'); ?>login/register">Register</a>
                    </li>
                <?php } ?>
            <?php } else { ?>
                <!-- for not logged in users -->
                <li <?php if (View::checkForActiveControllerAndAction($filename, "login/index")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo Config::get('URL'); ?>login/index">Login</a>
                </li>
            <?php } ?>
        </ul>

        <!--
        <?php if (Session::userIsLoggedIn()) : ?>
            <div class="header_right_box">
                <div class="namebox">
                    Hello <?php echo Session::get('user_name'); ?> !
                </div>
                <div class="avatar">
                    <?php if (Config::get('USE_GRAVATAR')) { ?>
                        <img src='<?php echo Session::get('user_gravatar_image_url'); ?>'
                             style='width:<?php echo Config::get('AVATAR_SIZE'); ?>px; height:<?php echo Config::get('AVATAR_SIZE'); ?>px;' />
                    <?php } else { ?>
                        <img src='<?php echo Session::get('user_avatar_file'); ?>'
                             style='width:<?php echo Config::get('AVATAR_SIZE'); ?>px; height:<?php echo Config::get('AVATAR_SIZE'); ?>px;' />
                    <?php } ?>
                </div>
            </div>
        <?php endif; ?>
        -->

        <!-- my account -->
        <ul class="navigation right">
        <?php if (Session::userIsLoggedIn()) : ?>
            <li <?php if (View::checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                <a href="<?php echo Config::get('URL'); ?>login/showprofile">My Account</a>
                <ul class="navigation-submenu">
                    <li <?php if (View::checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                        <a href="<?php echo Config::get('URL'); ?>login/edituseremail">Edit email</a>
                    </li>
                    <li <?php if (View::checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                        <a href="<?php echo Config::get('URL'); ?>login/logout">Logout</a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>
        </ul>
