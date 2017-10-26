<?php
session_start();

//0.外部ファイル読み込み
include("functions.php");
ssidChk();

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM gs_user_table");
$status = $stmt->execute();

//３．データ表示

?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <title>Share Tabi</title>
        <!--    <link rel="stylesheet" href="css/reset.css">-->
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <h1>Share Tabi</h1>
        <h2>旅をマップにシェアしよう！</h2>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li>旅日記</li>
            <li><a href="map2.php">周辺詮索</a></li>
            <li><a href="chat.php">Chat</a></li>
        </ul>

        <div>こんにちは、
            <?=$_SESSION["name"];?>　さん</div>
        <form method="post" action="res.php" enctype="multipart/form-data">
            ファイル:<input type="file" name="upfile"><br>
            <input type="hidden" name="id" value="<?=$id?>">
            <input type="submit" value="upload">
        </form>

        <!--
        <div id="wrap1">
            <h3 id="user_id"></h3>
            <ul id="output"></ul>
        </div>
-->

        <footer><small>Produced by tomohiro tengan</small></footer>



        <script>


        </script>

    </body>

    </html>
