<?php

define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/model/');
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/view/');


define('IMAGE_PATH', '/html/assets/images/');
define('STYLESHEET_PATH', '/assets/css/');
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/html/assets/images/' );

define('DB_HOST', 'mysql1.php.xdomain.ne.jp');
define('DB_NAME', 'hayabusa1868_83');
define('DB_USER', 'hayabusa1868_82');
define('DB_PASS', 'rain1994');
define('DB_CHARSET', 'utf8');

define('SIGNUP_URL', '/html/signup.php');
define('LOGIN_URL', '/html/login.php');
define('LOGOUT_URL', '/html/logout.php');
define('HOME_URL', '/html/index.php');
define('CART_URL', '/html/cart.php');
define('FINISH_URL', '/html/finish.php');
define('ADMIN_URL', '/html/admin.php');
define('HISTORY_URL','/html/history.php');
define('DETAIL_URL','/html/detail.php');

define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');


define('USER_NAME_LENGTH_MIN', 6);
define('USER_NAME_LENGTH_MAX', 100);
define('USER_PASSWORD_LENGTH_MIN', 6);
define('USER_PASSWORD_LENGTH_MAX', 100);

define('USER_TYPE_ADMIN', 1);
define('USER_TYPE_NORMAL', 2);

define('ITEM_NAME_LENGTH_MIN', 1);
define('ITEM_NAME_LENGTH_MAX', 100);

define('ITEM_STATUS_OPEN', 1);
define('ITEM_STATUS_CLOSE', 0);

define('PERMITTED_ITEM_STATUSES', array(
  'open' => 1,
  'close' => 0,
));

define('PERMITTED_IMAGE_TYPES', array(
  IMAGETYPE_JPEG => 'jpg',
  IMAGETYPE_PNG => 'png',
));