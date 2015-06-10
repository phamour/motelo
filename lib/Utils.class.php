<?php

class Utils {

    public static function yflash($key, $value) {
        if (isset($_SESSION['y.flash'])) {
            $_SESSION['y.flash'][$key] = $value;
        }
    }

    public static function renderLayout($app, $body, $bodyParams = array(), $jsParams = array()) {
        $app->render('header.php');
        $app->render('flash.php');
        $app->render($body, $bodyParams);
        $app->render('jsincludes.php', $jsParams);
        $app->render('footer.php');
    }

}

// END /lib/Utils.class.php
