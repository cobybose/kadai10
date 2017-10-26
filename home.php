<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Share Tabi</title>
    <!--    <link rel="stylesheet" href="css/reset.css">-->
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-image: url(img/home.jpg);
            background-size:70%;
            background-repeat: no-repeat;
            background-position: top;
        }

    </style>
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
    <div id="input">
        <h4 id="inp_title">ログインしてください</h2>
<!--
            <div id="wrap-send">
                <textarea id="username"></textarea>
                <button id="send2">OK</button>
            </div>
-->
            <!-- lLOGINogin_act.php は認証処理用のPHPです。 -->
            <form name="form1" action="login_act.php" method="post">
                ID:<input id="username" type="text" name="lid" /> PW:
                <input id="username" type="password" name="lpw" />
                <input id="send2" type="submit" value="LOGIN" />
            </form>
    </div>

    <footer><small>Produced by tomohiro tengan</small></footer>

    <!-- JS -->
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

        let newPostRef = firebase.database().ref();

//        function login() {
//            let myname = $("#username").val();
//
//            let json_myname = JSON.stringify(myname);
//            localStorage.setItem("myname", json_myname);
//            $("#username").val("");
//            alert("ユーザーネームを登録しました！");
//
//        }
//
//        //ログイン
//        $("#send2").on("click", login);

    </script>

</body>

</html>
