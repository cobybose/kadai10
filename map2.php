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
    <meta name="viewport" content="initial-scale=1.0">
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
    
    <!--以下divをGoogleMap化-->
    <div id="wrap">
        <div id="map2"></div>
        <div id="box">
            <!--            <h3 id=user_id2></h3>-->
            <h3>グループチャット</h3>
            <div id="output2"></div>

            <div>
                <!--                <div>名前<input type="text" id="username"></div>-->
                <div id="wrap-send">
                    <textarea rows="2" id="text2"></textarea>
                    <button id="send2">Send</button>
                </div>
            </div>
        </div>
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

            $('#output2').animate({
                scrollTop: $('#output2')[0].scrollHeight
            }, 'fast');
        }

        //送信処理
        $("#send2").on("click", sendMsg);

        //受信
        newPostRef.on("child_added", function(data) {

            if (localStorage.getItem("id")) {
                var value = localStorage.getItem("id");
                $("#user_id2").html(value);
            }

            const v = data.val();
            const k = data.key;
            const str = `
                <dl id="${k}">
                    <dt id = "time">${v.time}</dt>
                    <dt>${v.username}</dt>
                    <dd>${v.text}</dd>
                </dl>`;
            $("#output2").append(str);

            // スクロール要素の高さ
            var scrollHeight = document.getElementById("output2").scrollHeight;
            document.getElementById("output2").scrollTop = scrollHeight;

        });

        //エンターキー送信
        $("#text2").on("keydown", function(e) {
            //            console.log(e);
            if (e.keyCode == 13) {
                sendMsg();
            }
        });



        var map;
        var zoom = 12; //zoom値１〜２０

        //GoogleMapsAPIのURLパラメータにコールバック関数としてinitMap()を実行
        //Main:位置情報を取得する処理 //getCurrentPosition :or: watchPosition
        function initMap() {
            navigator.geolocation.watchPosition(mapsInit, mapsError, set);
        }

        //1．位置情報の取得に成功した時の処理
        function mapsInit(position) {
            //lat=緯度、lon=経度 を取得
            var lat = position.coords.latitude;
            var lon = position.coords.longitude;
            $("#map2").html("緯度" + lat + ",  " + "経度" + lon);
            map = new google.maps.Map(document.getElementById('map2'), {
                center: {
                    lat: lat + 0.001,
                    lng: lon + 0.001
                }, //緯度,経度を設定
                zoom: zoom //Zoom値設定
            });

            var people = {
                a_san: {
                    center: {
                        lat: lat + 0.01,
                        lng: lon + 0.06
                    },
                    url: "chat.php",
                    name: "ぐてたま",
                    where: "イタリア"
                },
                b_san: {
                    center: {
                        lat: lat + 0.03,
                        lng: lon - 0.02
                    },
                    url: "chat.php",
                    name: "スライム",
                    where: "フランス"
                },
                c_san: {
                    center: {
                        lat: lat - 0.05,
                        lng: lon - 0.09
                    },
                    url: "chat.php",
                    name: "はかせ",
                    where: "スイス"
                },
                d_san: {
                    center: {
                        lat: lat - 0.06,
                        lng: lon + 0.07
                    },
                    url: "chat.php",
                    name: "長沢くん",
                    where: "ドイツ"
                },
                e_san: {
                    center: {
                        lat: lat + 0.01,
                        lng: lon - 0.06
                    },
                    url: "chat.php",
                    name: "ピカチュウ",
                    where: "スペイン"
                }
            };

            let x = 50;
            let y = 50;

            var beachMarker = new google.maps.Marker({
                position: {
                    lat: lat + 0.001,
                    lng: lon + 0.001
                },
                map: map,
                icon: {
                    url: "img/mypic.png",
                    scaledSize: new google.maps.Size(x, y)
                }
            });

            for (var i in people) {
                let url = `img/${i}.png`;

                //プッシュピンを置く
                var beachMarker = new google.maps.Marker({
                    position: {
                        lat: people[i].center.lat + 0.001,
                        lng: people[i].center.lng + 0.001
                    },
                    map: map,
                    animation: google.maps.Animation.DROP,

                    icon: {
                        url: url,
                        scaledSize: new google.maps.Size(x, y)
                    }
                });

                //クリックしたら指定したurlに遷移するイベント＋ユーザー名をストレージに保存
                google.maps.event.addListener(beachMarker, 'click', (function(url, name) {
                    return function() {

                        let user = name;
                        console.log(user);
                        let json_user = JSON.stringify(user);
                        console.log(json_user);
                        localStorage.setItem("id", json_user);
                        location.href = url;
                    };

                })(people[i].url, people[i].name));


                //                google.maps.event.addListener(beachMarker, 'mouseover', (function(where) {
                //                    return function() {
                //
                //                        let infowindow = new google.maps.InfoWindow({
                //                            content: where
                //                        });
                //                        infowindow.open(map, beachMarker);
                //                    };
                //                })(people[i].where));
                //
                //
                //                google.maps.event.addListener(beachMarker, 'mouseout', (function(where) {
                //                    return function() {
                //                        let infowindow = new google.maps.InfoWindow({
                //                            content: where
                //                        });
                //                        infowindow.close();
                //                        console.log("aaa");
                //                    };
                //                })(people[i].where));


            }

            var cityCircle = new google.maps.Circle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                map: map,
                center: map.getCenter(),
                radius: 8000
            });

        };


        //2． 位置情報の取得に失敗した場合の処理
        function mapsError(error) {
            var e = "";
            if (error.code == 1) { //1＝位置情報取得が許可されてない（ブラウザの設定）
                e = "位置情報が許可されてません";
            }
            if (error.code == 2) { //2＝現在地を特定できない
                e = "現在位置を特定できません";
            }
            if (error.code == 3) { //3＝位置情報を取得する前にタイムアウトになった場合
                e = "位置情報を取得する前にタイムアウトになりました";
            }
            alert("エラー：" + e);
        };

        //3.位置情報取得オプション
        var set = {
            enableHighAccuracy: true, //より高精度な位置を求める
            maximumAge: 20000, //最後の現在地情報取得が20秒以内であればその情報を再利用する設定
            timeout: 10000 //10秒以内に現在地情報を取得できなければ、処理を終了
        };

    </script>




    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAE5TwLkkFTOwM1XtW0AckmROVrDMBvCV0&libraries=places&callback=initMap">


    </script>

</body>

</html>
