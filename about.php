<?php
session_start();
if (!isset($_SESSION["username"])) {
    if (isset($_POST["username"])) {
        $_SESSION["username"] = $_POST["username"];
    } else {
        header("Location: index.php");
        die();
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <meta charset="UTF-8">
        <link rel="SHORTCUT ICON" href="image/bb.jpg">
        <link rel="stylesheet" type="text/css" href="css/Home.css">        
        <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>        
        <div><!--nav start -->
            <div id='header'>
                <h1>Billboard</h1>
            </div>
            <hr id="header_navbar">
            <div id="navbar_container">
                <ul id="navbar">
                    <li class="navitem"><a href="home_page.php">Buy</a></li>
                    <li class="navitem"><a href="sell.php">Sell</a></li>
                    <li class="navitem"><a href="edit.php">Edit Profile</a></li>
                    <li class="navitem"><a href="chat.php">Chats</a></li>
                    <li class="navitem"><a class="active" href="about.php">About Us</a></li>

                    <li class="navitem logout" id="logout"><a href="userlogout.php">Logout</a></li>
                </ul>
            </div>   
        </div><!--nav end -->
    <center>
        <p style="width: 1000px;text-align: justify;padding:20px;font-size: 25px;background-color: white;color: black;border-color: black;border-radius: 5px;border-width: 2px;">
            &emsp;We all own certain things which aren’t useful to us anymore. Then why not sell those things and earn 
            money instead of keeping it useless at home? In our educational institutes we as students need to buy certain 
            stationaries and reference books for subjects that are required for just 1 or 2 semesters. For e.g., for 
            Information Technology and Computer Engineering students some stationaries such as drafters, engineering 
            drawing sheets container, etc. aren’t required after 1st year. Then why not give these stationaries to 
            juniors who would need it? 
            <br><br>
            &emsp;Our Website <b>BILLBOARD</b> helps students sell their textbooks, reference books, drafters, sheets container, 
            notes for certain subject, etc. to other junior students who would need it. Thus, here the buyer and seller 
            need to register to the website and log in to sell the item and buy it. We have provided the facility for the 
            buyer to chat with the seller and confirm the deal. Hence,  
            <br><b>‘‘It’s not about how much money you made, but how many people you helped!!’’</b>

        </p>
        
        <h3>Happy buying!</h3>

    </center>    
</body>
</html>
