<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'history.php';

session_start();

if (is_logined() === false) {
   redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

if (is_admin($user) === true) {
   $histories = acquisition_history($db); //管理者用
} else {
   $histories = get_history($db, $user['user_id']); //一般用
}

$token = get_csrf_token();

include_once VIEW_PATH . 'history_view.php';
