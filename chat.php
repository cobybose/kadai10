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
    <div id="wrap1">
        <h3 id="user_id"></h3>
        <ul id="output"></ul>

        <!--        <div>名前<input type="text" id="username"></div>-->
        <div id="wrap-send">
            <textarea rows="2" id="text2"></textarea>
            <button id="send2">送信</button>
        </div>
    </div>

    <footer><small>Produced by tomohiro tengan</small></footer>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.3.1/firebase.js"></script>
    <script>
        // Initialize Firebase
        var config = {
            apiKey: "AIzaSyCnyHJsa4x85L6hooASmX9L-TqMXrhEz-U",
            authDomain: "chatapp-7baa2.firebaseapp.com",
            databaseURL: "https://chatapp-7baa2.firebaseio.com",
            projectId: "chatapp-7baa2",
            storageBucket: "",
            messagingSenderId: "269046134975"
        };
        firebase.initializeApp(config);

        //MSG送受信準備
        let newPostRef = firebase.database().ref();

        function sendMsg() {

            //年・月・日・時・分・秒を取得する
            let time = new Date();
            let year = time.getFullYear();
            let month = time.getMonth() + 1;
            let date = time.getDate();
            let hour = time.getHours();
            let minute = time.getMinutes();
            let second = time.getSeconds();
            let str_time = `${year}/${month}/${date} ${hour}:${minute}:${second}`;

            if (localStorage.getItem("myname")) {
                let myname = localStorage.getItem("myname");

                if (myname == null) {
                    newPostRef.push({
                        username: "てんがん",
                        text: $("#text2").val(),
                        time: str_time
                    });
                } else {
                    newPostRef.push({
                        username: myname,
                        text: $("#text2").val(),
                        time: str_time
                    });
                }
            }

            $("#text2").val("");

            $('#output').animate({
                scrollTop: $('#output')[0].scrollHeight
            }, 'fast');
        }

        //送信処理
        $("#send2").on("click", sendMsg);

        //受信
        newPostRef.on("child_added", function(data) {

            if (localStorage.getItem("id")) {
                let value = localStorage.getItem("id");
                $("#user_id").html(value);
            }

            const v = data.val();
            const k = data.key;
            const message = v.text;
            if (v.username == localStorage.getItem("myname")){
            const str = `
                <<p class="right_balloon">
                <dl id="${k}">
                    <dt id = "time">${v.time}</dt>
                    <dt>${v.username}</dt>
                    <dd>${v.text}</dd>
                </dl></p>`;
                
                $("#output").append(str);
            }else{
                const str = `
                <p class="left_balloon">
                <dl id="${k}" >
                    <dt id = "time">${v.time}</dt>
                    <dt>${v.username}</dt>
                    <dd>${v.text}</dd>
                </dl></p>`;
                
                $("#output").append(str);
            }



            //取得したデータの名前が自分の名前なら右側に吹き出しを出す
//            if (username == nameField.val()) {
//
//                var messageElement = $("<il><p class='sender_name me'>" + username + "</p><p class='right_balloon'>" + message + "</p><p class='clear_balloon'></p></il>");
//
//            } else {
//
//                var messageElement = $("<il><p class='sender_name'>" + username + "</p><p class='left_balloon'>" + message + "</p><p class='clear_balloon'></p></il>");
//
//            }

//            $("#output").append(str);

            // スクロール要素の高さ
            var scrollHeight = document.getElementById("output").scrollHeight;
            document.getElementById("output").scrollTop = scrollHeight;

        });

        //エンターキー送信
        $("#text2").on("keydown", function(e) {
            //            console.log(e);
            if (e.keyCode == 13) {
                sendMsg();
            }
        });

    </script>

</body>

</html>
