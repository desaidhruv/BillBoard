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
        <link rel="stylesheet" type="text/css" href="css/sell.css">                
        <script src="jquery/jquery.js"></script>
        <script src="jquery/jquery_validate.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script>
            var username =<?php echo json_encode($_SESSION["username"]); ?>;
        </script>

        <script type="text/javascript">
            //var lastIndex = -1;
            var items;
            var current = -1;
            var clr;                        
            
            $(document).ready(function () {
                $('#blah').on('click', function () {
                    $('#image').trigger('click');
                });
                $("#form1").validate({
                    rules:
                            {
                                pname: "required",
                                price: {
                                    required: true,
                                    number: true,
                                    min: 1
                                },
                                quantity: {
                                    min: 1,
                                    required: true,
                                    number: true
                                },
                                cat: "required"
                            },
                    messages:
                            {
                            }
                });
                refreshItems();
                sr(1);
            });


            function refreshItems() {
                $.post('sellItems.php', {username: username}, function (data) {
                    var jsn = $.parseJSON(data);
                    items = jsn.items;
                    var maxid = jsn.maxid;
                    //if (lastIndex < maxid) {
                    var min = -1;
                    document.getElementById("items").innerHTML = "";
                    for (var i = 0; i < items.length; i++) {
                        if (i == 0) {
                            min = items[i].id;
                        }
                        document.getElementById("items").innerHTML +=
                                '<div id="' + items[i].id + '" class="item" onclick="editItem(' + i + ')">'
                                + '<img src="display.php?id=' + items[i].id + '" width=30 height=30>'
                                + '<span class="itemName">' + items[i].name + '</span>'
                                + '<span class="itemPrice">&#8377;' + items[i].price + '</span>'
                                //+ '<span class="itemDes">' + items[i].description + '</span>'
                                + '</div>';

                    }
                    //lastIndex = maxid;
                    document.getElementById("" + min).scrollIntoView();
                    //}
                });
            }

            function editItem(id) {
                var x = id;
                document.getElementById("sellContainer").style.display = "none";
                document.getElementById("AddItemModal").style.display = "block";
                document.getElementById("AddItemModal").style.opacity = "1";
                                
                document.getElementById("pname").value = items[x].name;
                document.getElementById("price").value = items[x].price;
                document.getElementById("quantity").value = items[x].quantity;
                document.getElementById("cat").value = items[x].category;
                document.getElementById("ds").value = items[x].description;
                document.getElementById("btn").value = "Update";
                document.getElementById("dele").style.display = "inline-block";
                current = items[x].id;
                if(items[x].sell == "image/sale.jpg"){
                    sr(1);
                }
                else{
                    sr(2);
                }
                $('#blah').attr('src', 'display.php?id='+current);
            }

            function onSell(r) {
                switch (r) {
                    case 1:
                        document.getElementById("sellContainer").style.display = "none";
                        document.getElementById("AddItemModal").style.display = "block";                        
                        document.getElementById("AddItemModal").style.opacity = "1";
                        document.getElementById("btn").value = "Add";
                        document.getElementById("dele").style.display = "none";                        
                        document.getElementById("pname").value = "";
                        document.getElementById("price").value = "";
                        document.getElementById("quantity").value = "";
                        document.getElementById("cat").value = "";
                        document.getElementById("ds").value = "";
                        sr(1);
                        break;
                    case 2:
                        document.getElementById("sellContainer").style.display = "block";
                        document.getElementById("AddItemModal").style.display = "none";
                        document.getElementById("AddItemModal").style.opacity = "0";
                        document.getElementById("dele").style.display = "none";
                        break;
                }
            }

            function sellItem() {
                /*var x = $("#form1").validate();
                if (x.element("#pname") && x.element("#price") && x.element("#quantity") && x.element("#cat")) {
                var name = document.getElementById("pname").value;
                var price = document.getElementById("price").value;
                var quantity = document.getElementById("quantity").value;
                var cat = document.getElementById("cat").value;
                var ds = document.getElementById("ds").value;*/
                if (document.getElementById("btn").value == "Add") {
                    /*$.post('addItem.php', {name: name, price: price, quantity: quantity,
                     category: cat, description: ds, operation: "add"}, function (data) {
                     if ($.trim(data) == "SENT") {
                     //refreshItems();
                     clr = setInterval(function () {
                     refreshItems();
                     clearInterval(clr);
                     }, 500);
                     onSell(2);
                     }
                     else {
                     alert("Item Not Added!");
                     }
                     });*/
                    document.getElementById("operation").value = "add";
                    if ($("#form1").valid()) {
                        //alert(document.getElementById("operation").value);
                        document.forms['form1'].submit();
                    }
                }
                else if (document.getElementById("btn").value == "Update" && current != -1) {
                    /*$.post('addItem.php', {name: name, price: price, quantity: quantity,
                     category: cat, description: ds, operation: "update", id: current}, function (data) {
                     if ($.trim(data) == "UPDATED") {
                     refreshItems();
                     onSell(2);
                     }
                     else {
                     alert("Item Not Updated!");
                     }
                     });*/
                    //$("#operation").val("update");
                    //alert(name + ":" + price + ":" + quantity + ":" + cat + ":" + ds);
                    document.getElementById("operation").value = "update";
                    if ($("#form1").valid()) {
                        document.getElementById("Uid").value=current;
                        //alert(document.getElementById("Uid").value);
                        document.forms['form1'].submit();
                    }
                }
                //}
            }
            
            function del(){
                $.post("addItem.php",{Uid:current,operation:"delete"},function(data){
                    //alert(data);
                    if($.trim(data)==="DELETED"){
                        refreshItems();
                        onSell(2);
                    }
                    else{
                        alert("Error deleting");
                    }
                });
            }
            
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#blah').attr('src', e.target.result).width(250).height(250);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
            
            function sr(i){
                switch(i){
                    case 1:
                        document.getElementById("srSell").style.borderStyle="solid";
                        document.getElementById("srRent").style.borderStyle="none";
                        document.getElementById("sr").value="image/sale.jpg";
                        break;
                    case 2:
                        document.getElementById("srSell").style.borderStyle="none";
                        document.getElementById("srRent").style.borderStyle="solid";
                        document.getElementById("sr").value="image/rent.jpg";
                        break;
                }
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
                    <li class="navitem"><a href="home_page.php">Buy</a></li>
                    <li class="navitem"><a class="active" href="sell.php">Sell</a></li>
                    <li class="navitem"><a href="edit.php">Edit Profile</a></li>
                    <li class="navitem"><a href="chat.php">Chats</a></li>
                    <li class="navitem"><a href="about.php">About Us</a></li>

                    <li class="navitem logout" id="logout"><a href="userlogout.php">Logout</a></li>
                </ul>
            </div>   
        </div><!--nav end -->

        <div>
            <center>
                <div id="sellContainer">
                    <!--<a href="#AddItemModal">-->
                    <div id="addItem" onclick="onSell(1)">
                        + Sell Item
                    </div>
                    <!--</a>-->
                    <div id="items">

                    </div>                        
                </div>            

                <div id="AddItemModal" class="">
                    <a title="Close" onclick="onSell(2)" class="close">x</a>
                    <img src="image/bb.jpg" class="modalImage" id="blah">
                    <!--<center>-->
                        <form id="form1" name="form1" method="post" action="addItem.php" enctype="multipart/form-data">
                            <input type="hidden" id="operation" name="operation" value="abc">
                            <input type="hidden" id="Uid" name="Uid" value="-1">
                            <input type="file" id="image" name="image" accept="image/*" style="display: none" onchange="readURL(this)">
                            <table class="ad">
                                <tr>
                                    <td>Item name: </td>
                                    <td><input class="tb" type="text" id="pname" name="pname"></td>
                                </tr>
                                <tr>
                                    <td>Price: </td>
                                    <td><input class="tb" min="1" type="number" id="price" name="price"></td>
                                </tr>
                                <tr>
                                    <td>Quantity: </td>
                                    <td><input class="tb" min="1" value="1" type="number" id="quantity" name="quantity"></td>
                                </tr>
                                <tr>
                                    <td>Category: </td>
                                    <td><select class="tb" name="cat" id="cat">
                                            <option value="" selected>Select...</option>
                                            <option value="Stationary">Stationary</option>
                                            <option value="Books">Books</option>
                                            <option value="Others">Others</option>
                                        </select></td> 
                                </tr>
                                <tr>
                                <input type="hidden" name="sr" id="sr">
                                    <td align="center"><img id="srSell" onclick="sr(1)" src="image/sale.jpg" class="sr"></td>
                                    <td align="center"><img id="srRent" onclick="sr(2)" src="image/rent.jpg" class="sr"></td>
                                </tr>
                                <tr>
                                    <td>Description: </td>
                                    <td><textarea id="ds" name="ds" maxlength="200" class="tb" style="resize: none;height: auto" rows="4"></textarea></td> 
                                </tr>
                            </table>
                        </form><br>
                        <input type="button" onclick="sellItem()" value="Add" id="btn"/>
                        <input type="button" style="display: none" onclick="del()" value="Delete" id="dele"/>
                    <!--</center>-->
                </div>
            </center>
        </div>
        <script>
            //refreshItems();
        </script>
    </body>
</html>
