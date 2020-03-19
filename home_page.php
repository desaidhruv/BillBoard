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
        <script src="jquery/jquery.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script>
            var username =<?php echo json_encode($_SESSION["username"]); ?>;
            var items;
            var current;
        </script>
        <script type="text/javascript">
            function getItems() {
                var search = document.getElementById("search").value;
                var cat = document.getElementById("cat").value;
                var x = document.getElementsByName("sortby");
                var sortby = "";
                for (var i = 0; i < x.length; i++) {
                    if (x[i].checked) {
                        sortby = x[i].value;
                        break;
                    }
                }
                //alert(sortby);
                $.post('buyItems.php', {cat: cat, sortby: sortby, search: search}, function (data) {
                    //alert(data);
                    var jsn = $.parseJSON(data);
                    items = jsn.items;
                    var min = -1;
                    document.getElementById("items").innerHTML = "";
                    for (var i = 0; i < items.length; i++) {
                        if (i == 0) {
                            min = items[i].id;
                        }
                        document.getElementById("items").innerHTML +=
                                '<div id="' + items[i].id + '" class="item" onclick="showDetails(' + i + ')">'
                                + '<img src="display.php?id=' + items[i].id + '" width=30 height=30>'
                                + '<span class="itemName">' + items[i].name + '</span>'
                                + '<span class="itemPrice">&#8377;' + items[i].price + '</span>'
                                //+ '<span class="itemDes">' + items[i].description + '</span>'
                                + '</div>';

                    }
                    //document.getElementById(min).scrollIntoView();                    
                });
            }

            function showDetails(i) {
                document.getElementById("items").style.display = "none";
                document.getElementById("filters").style.display = "none";
                document.getElementById("AddItemModal").style.display = "inline-block";
                document.getElementById("AddItemModal").style.opacity = "1";
                $("#blah").attr("src", "display.php?id=" + items[i].id);
                $("#sr").attr("src", items[i].sell);
                $("#pname").val(items[i].name);
                $("#price").val(items[i].price);
                $("#quantity").val(items[i].quantity);
                $("#cate").val(items[i].category);
                $("#ds").val(items[i].description);
                $("#chat").on('click', function () {
                    chatUser(i);
                });
                current = items[i].username;
                //alert(items[i].username);
                $.post("update_user.php", {username: items[i].username}, function (data) {
                    //alert(data);
                    var d = $.parseJSON(data);
                    var jsn = d.user;
                    current = jsn;
                    $("#simg").attr("src", "display.php?username=" + items[i].username);
                    document.getElementById("name").innerHTML = jsn.fname + " " + jsn.lname + "<br>" + jsn.year + "," + jsn.dept;
                });
            }

            function closeDetails() {
                document.getElementById("items").style.display = "inline-block";
                document.getElementById("filters").style.display = "inline-block";
                document.getElementById("AddItemModal").style.display = "none";
                document.getElementById("userDetails").style.display = "none";
                document.getElementById("AddItemModal").style.opacity = "0";
            }

            function chatUser(i) {
                var x = document.getElementById("dummyForm");
                x.innerHTML = '<form name="chat" action="chat.php" method="post">'
                        + '<input type="hidden" name="other" value="' + items[i].username + '">'
                        + '</form>';
                document.forms["chat"].submit();
            }

            $(document).ready(function () {
                getItems();
                $("#applyFilter").on('click', getItems);
                $("#seller").hover(function(event){
                    showUser(event);    
                }, function () {
                    document.getElementById("userDetails").style.display = "none";
                }
                );
                //$("#search").
            });
            function showUser(event) {
                document.getElementById("userDetails").style.display = "inline-block";
                //document.getElementById("userDetails").style.top = event.clientY+"px";                
                //document.getElementById("userDetails").style.left = event.clientX+"px";
                document.getElementById("userDetails").innerHTML =
                        '<img src="display.php?username=' + current.username + '">'
                        + '<br><b>' + current.fname + ' ' + current.lname + '</b>'
                        + '<br>' + current.year + ',' + current.dept
                        + '<br>' + current.r
                        + '<br>' + current.email;
            }
        </script>
    </head>
    <body>        
        <div><!--nav start -->
            <div id='header'>
                <h1>Billboard</h1>
            </div>
            <hr id="header_navbar">
            <div id="navbar_container">
                <ul id="navbar">
                    <li class="navitem"><a class="active" href="home_page.php">Buy</a></li>
                    <li class="navitem"><a href="sell.php">Sell</a></li>
                    <li class="navitem"><a href="edit.php">Edit Profile</a></li>
                    <li class="navitem"><a href="chat.php">Chats</a></li>
                    <li class="navitem"><a href="about.php">About Us</a></li>

                    <li class="navitem logout" id="logout"><a href="userlogout.php">Logout</a></li>
                </ul>
            </div>   
        </div><!--nav end -->
    <center>
        <div id="items">

        </div>
        <div id="filters">
            <input type="text" id="search" name="search" placeholder="Search">
            <br>
            <br>            
            <b>Sort By:</b>
            <br>
            <b>Price:</b>
            <br>
            <input type="radio" name="sortby" value="High to Low">High to Low
            <br>
            <input type="radio" name="sortby" value="Low to High">Low to High
            <br>
            <b>Name:</b>
            <br>
            <input type="radio" name="sortby" value="A-z" checked="true">A-z
            <br>
            <input type="radio" name="sortby" value="Z-a">Z-a
            <br>
            <br>
            <b>Filter By:</b>
            <br>
            <select class="tb" name="cat" id="cat">
                <option value="" selected>Select...</option>
                <option value="Stationary">Stationary</option>
                <option value="Books">Books</option>
                <option value="Others">Others</option>
            </select>
            <br>
            <br>
            <br>
            <center>
                <input type="button" id="applyFilter" value="Apply">
            </center>
        </div>


        <div id="AddItemModal" class="">
            <a title="Close" onclick="closeDetails()" class="close">x</a>
            <img src="image/bb.jpg" class="modalImage" id="blah">
            <table class="ad">
                <tr>
                    <td>Item name: </td>
                    <td><input class="tb" readonly="true" type="text" id="pname" name="pname"></td>
                </tr>
                <tr>
                    <td>Price: </td>
                    <td><input class="tb" type="text" readonly="true" id="price" name="price"></td>
                </tr>
                <tr>
                    <td>Quantity: </td>
                    <td><input class="tb" type="text" readonly="true" id="quantity" name="quantity"></td>
                </tr>
                <tr>
                    <td>Category: </td>
                    <td><input class="tb" type="text" readonly="true" id="cate" name="cat"></td> 
                </tr>
                <!--<tr>
                    <td colspan="2" align="center">
                        <img id="sr">
                    </td>
                </tr>-->
                <tr>
                    <td>Description: </td>
                    <td><textarea id="ds" name="ds" readonly="true" maxlength="200" class="tb" style="resize: none;height: auto" rows="4"></textarea></td> 
                    <td align="center">
                        <img id="sr">
                    </td>
                </tr>
            </table>
            <br>
            <input type="button" onclick="" value="Chat with Seller" id="chat"/>
            <div id="seller">
                <img id="simg" src="image/profile_default.jpg">
                <span id="name"></span>                
            </div>
        </div>

        <div id="userDetails">

        </div>

        <div id="dummyForm"></div>
    </center> 
</body>
</html>
