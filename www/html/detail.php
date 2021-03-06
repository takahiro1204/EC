<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'history.php';
require_once MODEL_PATH . 'detail.php';

session_start();

if (is_logined() === false) {
    redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);
$order_id = get_post('order_id');

if (is_admin($user) === true) {
    $history = obtain_history($db,$order_id); 
    $details = acquisition_details($db,$order_id);

} else {
    $history = open_history($db, $user['user_id'],$order_id);
    $details = get_details($db,$user['user_id'],$order_id);
}     


include_once VIEW_PATH . 'detail_view.php';
