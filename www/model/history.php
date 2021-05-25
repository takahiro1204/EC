<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function 
acquisition_history($db)
{
    $sql = "SELECT history.order_id,create_datetime,sum(amount*price) as total
from history
inner join details on history.order_id = details.order_id
group by history.order_id 
order by create_datetime desc"; //降順(最新版が表示)

    return fetch_all_query($db, $sql);
}

function get_history($db, $user_id)
{
    $sql = "SELECT history.order_id,create_datetime,sum(amount*price) as total
from history
inner join details on history.order_id = details.order_id
where user_id = ?
group by history.order_id 
order by create_datetime desc"; //降順(最新版が表示)

    return fetch_all_query($db, $sql, array($user_id));
}
