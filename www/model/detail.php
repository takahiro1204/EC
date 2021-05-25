<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function //acquisition_detailsは管理者用の関数
acquisition_details($db, $order_id) //引数　関数が処理する情報
{
    $sql = "SELECT name,details.price,amount,amount*details.price as total
           from details
           inner join items on details.item_id = items.item_id
           WHERE order_id = ?";
    //*details.price は購入時の価格  amount*details.priceは（数量×価格＝金額）
    //inner join  detailsテーブルとitemsテーブルにあるitem_idを結合します。
    // WHERE 条件を指定します。ここではorder_idがあるかが条件です。

    return fetch_all_query($db, $sql, array($order_id)); //配列　fetch_all_query 全部の情報を取得する 
}

function //get_detailsは一般用の関数
get_details($db, $user_id, $order_id)
{
    $sql = "SELECT name,details.price,amount,amount*details.price as total
    from details
    inner join history on details.order_id = history.order_id
    inner join items on details.item_id = items.item_id
    WHERE details.order_id = ? AND user_id = ?";
    //inner join  detailsテーブルとhistoryテーブルにあるorder_idを結合
    //FROM句とJOIN句は、detailsとhistoryとitemsの3つのテーブルを内部結合
    //WHEREの条件としてorder_idとuser_idにプレースホルダをつけて、あとからパラメータをセット

    return fetch_all_query($db, $sql, array($order_id, $user_id)); 
    //arrayは配列であり複数のデータを扱うことが出来る
}

function //obtain_historyは管理者用の関数
obtain_history($db, $order_id)
{
    $sql = "SELECT history.order_id,create_datetime,sum(amount*price) as total
from history
inner join details on history.order_id = details.order_id
WHERE history.order_id = ?
group by history.order_id ";
    //group by  集計を注文番号単位で行うことを表す
    //sum(amount*price)-->金額の合計


    return fetch_query($db, $sql, array($order_id)); //fetch_query 一部の情報だけ取得する(order_idの情報を取得)
}

function //open_historyは一般用の関数
open_history($db, $user_id, $order_id)
{
    $sql = "SELECT history.order_id,create_datetime,sum(amount*price) as total
from history
inner join details on history.order_id = details.order_id
where user_id = ? AND history.order_id = ?
group by history.order_id ";


    return fetch_query($db, $sql, array($user_id, $order_id));
}
