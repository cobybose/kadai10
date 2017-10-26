<?php
session_start();

//0.外部ファイル読み込み
include("functions.php");
ssidChk();

$id = $_POST["id"];
//$up_file = $_POST["up_file"];

//Fileアップロードチェック
if (isset($_FILES["upfile"] ) && $_FILES["upfile"]["error"] ==0 ) {
    //情報取得
    $file_name = $_FILES["upfile"]["name"];         //"1.jpg"ファイル名取得
    $tmp_path  = $_FILES["upfile"]["tmp_name"]; //"/usr/www/tmp/1.jpg"アップロード先のTempフォルダ
    $file_dir_path = "upload/";  //画像ファイル保管先

    
    //***File名の変更***
    $extension = pathinfo($file_name, PATHINFO_EXTENSION); //拡張子取得(jpg, png, gif)
    $file_name = date("YmdHis").md5(session_id()) . "." . $extension;  //ユニークファイル名作成
   

    $img="";  //画像表示orError文字を保持する変数
    // FileUpload [--Start--]
    if ( is_uploaded_file( $tmp_path ) ) {
        if ( move_uploaded_file( $tmp_path, $file_dir_path . $file_name ) ) {
            chmod( $file_dir_path . $file_name, 0644 );
            //echo $file_name . "をアップロードしました。";
            //$img = '<img width="300" src="'. $file_dir_path . $file_name . '" >'; //画像表示用HTML
        } else {
            //$img = "Error:アップロードできませんでした。"; //Error文字
        }
    }
    // FileUpload [--End--]
}else{
    $img = "画像が送信されていません"; //Error文字
}


//1.  DB接続します
$pdo = db_con();

//2. データ登録SQL作成
$stmt = $pdo->prepare("UPDATE gs_user_table SET image=:image WHERE id=:id");
$stmt->bindValue(':image',$file_name, PDO::PARAM_STR);
$stmt->bindValue(':id',$id, PDO::PARAM_INT);
$res = $stmt->execute();

//3. SQL実行時にエラーがある場合
if($res==false){
  queryError($stmt);
}

//4. 抽出データ数を取得
$count = $stmt->fetchColumn(); //SELECT COUNT(*)で使用可能()
$val = $stmt->fetch(); //1レコードだけ取得する方法

//5. 該当レコードがあればSESSIONに値を代入
if( $val["id"] != "" ){
  $_SESSION["chk_ssid"]  = session_id();
//  $_SESSION["image"] = $val['image'];
}




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
    
    <div>こんにちは、<?=$_SESSION["name"];?>　さん</div>
    <img width="200" src="upload/<?=$_SESSION["image"];?>">


    <footer><small>Produced by tomohiro tengan</small></footer>

</body>

</html>
