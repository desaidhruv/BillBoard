<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    die();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <meta charset="UTF-8">
        <link rel="SHORTCUT ICON" href="image/bb.jpg">
        <link rel="stylesheet" type="text/css" href="css/chat.css">        
        <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script type="text/javascript">
            var other1 =<?php
if (isset($_POST["other"])) {
    echo json_encode($_POST["other"]);
} else
    echo "''";
?>;
            var current =<?php echo json_encode($_SESSION["username"]); ?>;
            var users;
            var userint;
            var msgint;
        </script>
        <script type="text/javascript">
            var lastIndex = -1;
            var lasttime = new Date(2015, 1, 1);
            $(document).ready(function () {
                $('#newMessage').keypress(function (e) {
                    if (e.keyCode == 13)
                        $('#sendMessage').click();
                });

                userint = window.setInterval(refreshUsers, 1000);
                if (typeof other1 != "undefined" && other1 != "") {
                    refreshMessages();
                    msgint = setInterval(refreshMessages, 1000);
                }
            });

            function refreshMessages() {
                if (typeof other1 != "undefined" && other1 != "") {
                    $.post('returnMessages.php', {other: other1}, function (data) {
                        var jsn = $.parseJSON(data);
                        var messages = jsn.Messages;
                        var maxid = jsn.maxid;
                        if (lastIndex < maxid) {
                            document.getElementById("messageContainer").innerHTML = "";
                            for (var i = 0; i < messages.length; i++) {
                                if (messages[i].sender === current) {
                                    document.getElementById("messageContainer").innerHTML +=
                                            '<div id="' + messages[i].id + '" class="sender">'
                                            + messages[i].message
                                            + '<span class="mtime">'
                                            + messages[i].time
                                            + '</span>'
                                            + '</div>';
                                }
                                else {
                                    document.getElementById("messageContainer").innerHTML +=
                                            '<div id="' + messages[i].id + '" class="reciever">'
                                            + messages[i].message
                                            + '<span class="mtime">'
                                            + messages[i].time
                                            + '</span>'
                                            + '</div>';
                                }
                            }
                            lastIndex = maxid;
                            document.getElementById("" + lastIndex).scrollIntoView();
                        }
                    });
                }
            }

            function userClick(i) {
                if (typeof other1 != "undefined") {
                    $("#" + other1).removeClass("muser");
                }
                other1 = users[i].username;
                $("#" + other1).addClass("muser");
                if (typeof msgint !== "undefined") {
                    clearInterval(msgint);
                }
                lastIndex = -1;
                refreshMessages();
                msgint = setInterval(refreshMessages, 1000);
            }

            function sendMessage() {
                var msg = document.getElementById("newMessage").value;
                if (msg !== "" && typeof other1 !== "undefined" && other1 != "") {
                    $.post('sendMessage.php', {other: other1, message: $.trim(msg)}, function (data) {
                        if ($.trim(data) == "SENT") {
                            refreshMessages();
                            document.getElementById("newMessage").value = "";
                        }
                        else {
                            alert("Message Not Sent!");
                        }
                    });
                }
                else {
                    if (msg == "") {
                        alert("Enter Message");
                    }
                    else {
                        alert("Select a user");
                    }
                }
            }

            function getDate(x) {
                var dateTime = x.split(" ");
                var date = dateTime[0].split("-");
                var yyyy = date[0];
                var mm = date[1] - 1;
                var dd = date[2];

                var time = dateTime[1].split(":");
                var h = time[0];
                var m = time[1];
                var s = parseInt(time[2]); //get rid of that 00.0;
                return new Date(yyyy, mm, dd, h, m, s);
            }

            function refreshUsers() {
                $.post('chatusers.php', {}, function (data) {
                    var jsn = $.parseJSON(data);
                    users = jsn.users;
                    var lt = getDate(jsn.lasttime.date);
                    //alert(lt);
                    if (lt > lasttime) {
                        document.getElementById("users").innerHTML = "";
                        for (var i = 0; i < users.length; i++) {                            
                            document.getElementById("users").innerHTML +=
                                    '<div id="' + users[i].username + '" class="user" onclick="userClick(' + i + ')">'
                                    //+ '<span class="time">' + users[i].lasttime + '</span>'
                                    + '</div>';

                            $.post("update_user.php", {username: users[i].username}, function (data) {
                                var js = $.parseJSON(data).user;
                                var name = js.fname + " " + js.lname;
                                if (name.length > 9) {
                                    name = name.substr(0, 9) + "...";
                                }
                                for (var i = 0; i < users.length; i++) {
                                    if (users[i].username == js.username) {
                                        break;
                                    }
                                }
                                var left = users[i].lastrec - users[i].lastread;
                                document.getElementById(js.username).innerHTML +=
                                        '<img src="display.php?username=' + js.username + '">'
                                        + name
                                        + '<span class="left">' + left + '</span>';
                                $("#" + js.username).hover(function (event) {
                                    document.getElementById("userDetails").style.display = "inline-block";
                                    //$("#userdetails").fadeIn('slow');
                                    document.getElementById("userDetails").style.top = event.clientY + "px";
                                    document.getElementById("userDetails").innerHTML =
                                            '<img src="display.php?username=' + js.username + '">'
                                            + '<br><b>' + js.fname + ' ' + js.lname + '</b>'
                                            + '<br>' + js.year + ',' + js.dept
                                            + '<br>' + js.r
                                            + '<br>' + js.email;
                                }, function () {
                                    //$("#userdetails").fadeOut(5000);
                                    document.getElementById("userDetails").style.display = "none";
                                });
                            });
                        }
                        lasttime = lt;
                        if (typeof other1 != "undefined") {
                            $("#" + other1).addClass("muser");
                        }
                    }
                });
            }
        </script>
    </head>
    <body>
        <div><!--nav start -->
            <div id="header">
                <h1>Billboard</h1>
            </div>
            <hr id="header_navbar">
            <div id="navbar_container">
                <ul id="navbar">
                    <li class="navitem"><a href="home_page.php">Buy</a></li>
                    <li class="navitem"><a href="sell.php">Sell</a></li>
                    <li class="navitem"><a href="edit.php">Edit Profile</a></li>
                    <li class="navitem"><a class="active" href="chat.php">Chats</a></li>
                    <li class="navitem"><a href="about.php">About Us</a></li>

                    <li class="navitem logout" id="logout"><a href="userlogout.php">Logout</a></li>
                </ul>
            </div>   
        </div>
        <!--nav end -->
    <center>
        <div id="layout">
            <div id="messageContainer">

            </div>            
            <input type="text" id="newMessage" maxlength="200" name="newMessage">
            <input type="button" id="sendMessage" name="sendMessage" onclick="sendMessage()" value="Send">            
        </div>

        <div id="users">

        </div>
        <div id="userDetails">

        </div>

    </center>
    <script>
        refreshUsers();
    </script>
</body>
</html>
