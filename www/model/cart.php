<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_user_carts($db, $user_id)
{
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
  ";
  return fetch_all_query($db, $sql,[$user_id]);
}

function get_user_cart($db, $user_id, $item_id)
{
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
    AND
      items.item_id = ?
  ";

  return fetch_query($db, $sql,[$user_id,$item_id]);
}

function add_cart($db, $user_id, $item_id)
{
  $cart = get_user_cart($db, $user_id, $item_id);
  if ($cart === false) {
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

function insert_cart($db, $user_id, $item_id, $amount = 1)
{
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(?,?,?)
  ";

  return execute_query($db, $sql,[$item_id,$user_id,$amount]); 
}

function update_cart_amount($db, $cart_id, $amount)
{
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  return execute_query($db, $sql,[$amount,$cart_id]);
}

function delete_cart($db, $cart_id)
{
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";

  return execute_query($db, $sql,[$cart_id]);
}

function purchase_carts($db, $carts)
{
  if (validate_cart_purchase($carts) === false) {
    return false;
  }


$db ->beginTransaction(); //関連する複数の処理を一つの処理としてまとめる

create_purchasehistory($db,$carts);
  foreach ($carts as $cart) {
    if (update_item_stock(
      $db,
      $cart['item_id'],
      $cart['stock'] - $cart['amount']
    ) === false) {
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }

  delete_user_carts($db, $carts[0]['user_id']);
  if(has_error()) {
    $db->rollback(); //ひとつでも違ってたらやり直し
  } else {
    $db->commit(); 
  }
}

function delete_user_carts($db, $user_id)
{
  $sql = "
    DELETE FROM
      carts
    WHERE           
      user_id = ?    
  ";

  execute_query($db, $sql,[$user_id]); 
}
//SQLインジェクション(SQLの実行に利用されるフォームの入力値等を通じて攻撃)
//対策として143行目の[$user_id]を?に変換、146行目に[$user_id]を追加

function sum_carts($carts)
{
  $total_price = 0;
  foreach ($carts as $cart) {
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts)
{
  if (count($carts) === 0) {
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach ($carts as $cart) {
    if (is_open($cart) === false) {
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if ($cart['stock'] - $cart['amount'] < 0) {
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if (has_error() === true) {
    return false;
  }
  return true;
}


function insert_history($db,$user_id)
{
  $sql = "
  INSERT INTO
     history(
        user_id,
        create_datetime
      )
      VALUE(?,NOW())
    ";

    return execute_query($db,$sql,[$user_id]);
  
}

function insert_details($db,$order_id,$item_id,$amount,$price) 
//関数：insert_detalis  引数:($db,$order_id,$item_id,$amount,$price)
//引数とはある情報(値)を引数が持っており、それを関数に渡すことで、関数はその情報に応じた処理をする
{
  $sql = "
   INSERT INTO
     details(
        order_id,
        item_id,
        amount,
        price
      )
      VALUE(?,?,?,?)
    ";

    return execute_query($db,$sql,[$order_id,$item_id,$amount,$price]);
}

function create_purchasehistory($db,$carts) {
  if(insert_history($db,$carts[0]['user_id']) === false) {  //$carts[0]['user_id']は1件目のユーザーIDを取得(1を使いたいが0からカウント開始)
    
    set_error('購入履歴データの作成に失敗しました');
    return false; //処理を中止する
  }
  $order_id = $db->lastInsertId();//lastInsertId(自動的に割り当てられたIDを取得する)
                                  //使用条件はテーブルの主キーがAUTO INCREMENT(オートインクリメント)の自動連番であること
  foreach($carts as $cart) {
    insert_details($db,$order_id,$cart['item_id'],$cart['amount'],$cart['price']); 
    //ここでinsert_detailsを呼び出して、$cartの中のカラムをループ処理する。
  }
}