<?php
session_start();
if (isset($_SESSION["username"])) {
    header("Location: home_page.php");
} else {
    //session_destroy();
}
?>
<html>
    <head>
        <title>Login</title>
        <link rel="SHORTCUT ICON" href="image/bb.jpg">
        <link rel="stylesheet" href="css/LoginCss.css">
        <link rel="stylesheet" href="jquery/jSlider.css">
        <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="jquery/jquery-latest.min.js"></script>
        <script src="jquery/jquery.jSlider.js"></script>
        <script>
            $(document).ready(function () {
                $('#password').keypress(function (e) {
                    if (e.keyCode === 13)
                        $('#login').click();
                });
                
                $('#demo').sliderInit({});


                $(document).on("click", "#login", function () {
                    var username1 = jQuery.trim($('#username').val());
                    var password1 = $('#password').val();
                    if (username1.length >= 0 && password1.length >= 6) {
                        $.post('userlogincheck.php', {username: username1, password: password1}, function (data) {
                            if ($.trim(data) === "ACCEPT USER") {
                                $("#loginredirect").html("<form name='toHome' action='userlogin.php' method='post'>"
                                        + "<input type='hidden' name='username' value='" + username1 + "'>"
                                        + "</form>");
                                document.forms['toHome'].submit();
                            }
                            else {
                                alert("Invalid Username or Password:" + data);
                            }
                        });
                    }
                });
            });
        </script>
        <style>
            /* jSlider*/
            #demo{                
                width: 700px;
                height: auto;
                margin: 10px auto;
                border: 3px solid #fff;
                -webkit-box-shadow: 2px 2px 10px 0 rgba(0,0,0,0.3);
                box-shadow: 2px 2px 10px 0 rgba(0,0,0,0.3);
                -webkit-border-radius: 5px;
                border-radius: 5px;
            }
            /* responsive rules */
            @media (max-width: 713px) {                
                #demo {
                    width: 100%;
                    height: auto; /* reset slider height to automatically fix with the first image height. */
                    border: none;
                    margin-top: 0;
                    -webkit-border-radius: 0;
                    border-radius: 0;
                }
            }
        </style>
    </head>
    <body>
        <div id="header">
            <h1 style="cursor:default">BillBoard</h1>
            <table>                
                <tr>
                    <td style="cursor:default">Username: </td>
                    <td>
                        <input class="tb" id="username" type="text" placeholder="Enter Username">
                    </td>
                </tr>
                <tr>
                    <td style="cursor:default">Password: </td>
                    <td><input class="tb" id="password" type="password" placeholder="Enter password"></td>
                </tr>
                <tr>
                    <td id='co' colspan="2" style="cursor:default">Haven't Signed in ??
                        <a href='Register.php' style="cursor:pointer"> Sign in</a>
                        <input type="button" id="login" value="Login"></td>
                </tr>                
            </table>
        </div>
        <div id="demo" class="jSlider"
             data-navigation="hover" 
             data-indicator="always"
             data-speed="500"
             data-transition="slide"
             data-loop="true"
             data-group="1">
            <div><img src="image/1.jpg" alt="1"></div>
            <div><img src="image/2.jpg" alt="2"></div>
            <div><img src="image/3.JPG" alt="3"></div>
            <div><img src="image/4.jpg" alt="4"></div>                
        </div>

        <?php
        if (isset($_GET["register"])) {
            $register = $_GET["register"];
            if ($register === "true") {
                echo "<script type='text/javascript'>";
                echo "alert('Successfully Registered.');";
                echo "</script>";
            }
        }
        ?>
        <div id="loginredirect"></div>
    </body>
</html>
