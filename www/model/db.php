<?php

function get_db_connect()
{
  // MySQL用のDSN文字列
  $dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';charset=' . DB_CHARSET;

  try {
    // データベースに接続
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//エラーモードの設定:各種メソッドの戻り値を確認しなくても問題や原因が検知する
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //プリペアドステートメント:SQLを実行前にいろいろと準備して実行する
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    exit('接続できませんでした。理由：' . $e->getMessage());
  }
  return $dbh;
}

function fetch_query($db, $sql, $params = array()) //一部のデータを配列として返す
{
  try {
    $statement = $db->prepare($sql);
    $statement->execute($params);
    return $statement->fetch();
  } catch (PDOException $e) {
    set_error('データ取得に失敗しました。');
  }
  return false;
}

function fetch_all_query($db, $sql, $params = array()) //全てのデータを配列として返す
{
  try {
    $statement = $db->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
  } catch (PDOException $e) {
    set_error('データ取得に失敗しました。');
  }
  return false;
}

function execute_query($db, $sql, $params = array()) //実行処理を行う
{
  try {
    $statement = $db->prepare($sql);
    return $statement->execute($params);
  } catch (PDOException $e) {
    set_error('更新に失敗しました。');
  }
  return false;
}


