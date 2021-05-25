<!DOCTYPE HTML>
<html lang="ja">

<head>
    <?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>明細履歴</title>
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'detail.css'); ?>">
</head>
    <body>
        <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        <h1>明細履歴</h1>
        <div class="container">
            <?php include VIEW_PATH . 'templates/messages.php'; ?>
               
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>注文番号</th>
                        <th>購入日時</th>
                        <th>合計金額</th>                     
                    </tr>
                </thead>
                <tbody>         
                        <tr>
                            <td><?php print(h($history['order_id'])); ?></td>
                            <td><?php print(h($history['create_datetime'])); ?></td>
                            <td><?php print(h($history['total'])); ?></td>                              
                        </tr>                   
                </tbody>
            </table>
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>商品名</th>
                        <th>価格</th>
                        <th>購入数</th>
                        <th>合計</th>
                    </tr>
                </thead>
            <?php foreach ($details as $detail) { ?>
                <tr>
                    <td><?php print(h($detail['name'])); ?></td>
                    <td><?php print(h($detail['price'])); ?></td>
                    <td><?php print(h($detail['amount'])); ?></td>
                    <td><?php print(h($detail['total'])); ?></td>
                </tr>
            <?php } ?>
            </table>
    </body>
</html>