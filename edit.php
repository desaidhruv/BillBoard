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
        <link rel="stylesheet" type="text/css" href="css/edit.css">        
        <script src="jquery/jquery.js"></script>
        <script src="jquery/jquery_validate.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script type="text/javascript">
<?php
include 'update_user.php';
?>
            var username =<?php echo json_encode($_SESSION["username"]); ?>;

            var data =<?php echo "'" . json_encode($json) . "'"; ?>;
            var user;
        </script>
        <script type="text/javascript">

            $(document).ready(function () {
                user = $.parseJSON(data);
                setData();
                $('#blah').on('click', function () {
                    $('#f1').trigger('click');
                });

                $("#form1").validate({
                    rules:
                            {
                                fname: "required",
                                lname: "required",                                
                                year: "required",
                                dept: "required",
                                contact: "required",
                                r: "required",
                                pass1:
                                        {
                                            required: true,
                                            minlength: 6,
                                            maxlength: 8
                                        },
                                pass2:
                                        {
                                            required: true,
                                            equalTo: "#pass1",
                                            minlength: 6,
                                            maxlength: 8
                                        }

                            },
                    messages:
                            {
                                fname: {required: "Enter your name"},
                                lname: {required: "Enter your last name"},                                
                                pass1: {required: "Enter the password(min 6:max 8)"},
                                pass2: {equalTo: "Passwords dont match"}
                            }
                });
            });
        </script>
        <script type="text/javascript">
            function setData() {
                $("#fname").val(user.fname);
                $("#lname").val(user.lname);
                $("#year").val(user.year);
                $("#dept").val(user.dept);
                $("#pass1").val(user.pass);
                $("#pass2").val(user.pass);
                $("#contact").val(user.contact);

                if (user.r === "Male")
                    $("#r1").prop('checked', true);
                else
                    $("#r2").prop('checked', true);

                $('#blah').attr('src', 'display.php');
                //$("#email").val(user.email);
            }

            function change()
            {
                if (document.getElementById("edit").value == 'Save' &&
                        document.getElementById("cancel").style.display == 'block') {                                        
                    if($("#form1").valid())            
                        document.forms['form1'].submit();
                    //update();                    
                    //i = 2;
                }
                else
                {
                    dis(1);
                }
            }

            /*function update() {                
                var z = $("#form1").validate();
                var x = [z.element("#fname"),
                    z.element("#lname"),
                    z.element("#year"),
                    z.element("#dept"),
                    //z.element("#r"),
                    //z.element("#contact"),
                    z.element("#pass1"),
                    z.element("#pass2")];
                var valid = true;
                for (var i = 0; i < 6; i++) {
                    if (!x[i])
                        valid = false;
                }                
                if (valid) {                    
                    var fname = $("#fname").val();
                    var lname = $("#lname").val();
                    var year = $("#year").val();
                    var dept = $("#dept").val();
                    //var contact = $("#contact").val();
                    var pass1 = $("#pass1").val();
                    var r = $('input:radio[name="r"]').val();
                    var image=$("#f1").val();
                    alert(image);
                    $.post("update_user.php", {fname: fname, lname: lname, year: year,
                        dept: dept, //contact: contact,
                        pass1: pass1, r: r}, function (data) {
                        if ($.trim(data) == "OK") {
                            user.fname = fname;
                            user.lname = lname;
                            user.year = year;
                            user.dept = dept;
                            //user.contact = contact;
                            user.r = r;
                            user.pass = pass1;
                            setData();
                            dis(2);
                        }
                        else {
                            alert("error updating:"+data);
                        }
                    });
                }
            }*/

            function dis(i) {
                switch (i)
                {
                    case 1:
                        var a = document.getElementsByClassName("tb");
                        var i;
                        for (i = 0; i < a.length; i++) {
                            a[i].style.backgroundColor = "white";
                            a[i].disabled = false;
                        }
                        document.getElementById("dept").disabled = false;
                        document.getElementById("year").disabled = false;
                        document.getElementById("r1").disabled = false;
                        document.getElementById("r2").disabled = false;
                        document.getElementById("f1").disabled = false;
                        document.getElementById("cancel").style.display = 'block';
                        document.getElementById("edit").value = 'Save';
                        break;
                    case 2:
                        setData();
                        var a = document.getElementsByClassName("tb");
                        var i;
                        for (i = 0; i < a.length; i++) {
                            a[i].style.backgroundColor = "#485154";
                            a[i].disabled = true;
                        }
                        document.getElementById("f1").disabled = true;
                        document.getElementById("dept").disabled = true;
                        document.getElementById("year").disabled = true;
                        document.getElementById("r1").disabled = true;
                        document.getElementById("r2").disabled = true;
                        document.getElementById("cancel").style.display = 'none';
                        document.getElementById("edit").value = 'Edit';
                        break;
                }
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
                    <li class="navitem"><a href="sell.php">Sell</a></li>
                    <li class="navitem"><a class="active" href="edit.php">Edit Profile</a></li>
                    <li class="navitem"><a href="chat.php">Chats</a></li>
                    <li class="navitem"><a href="about.php">About Us</a></li>

                    <li class="navitem logout" id="logout"><a href="userlogout.php">Logout</a></li>
                </ul>
            </div>   
        </div><!--nav end -->

    <center>
        <fieldset><img src="display.php" id="blah" alt="profile"><br>
            <form id="form1" name="form1" method="post" action="update_user.php" enctype="multipart/form-data">
                <input type="file" id="f1"  name="image" accept="image/*"  onchange="readURL(this)" disabled>                    
                <table cellspacing="8px">
                    <tr>
                        <td>NAME :</td><td> <input type="text" class="tb" id="fname" name="fname" disabled></td>
                    </tr>
                    <tr><td>SURNAME : </td><td><input type="text"  class="tb" id="lname" name="lname" disabled></td></tr>
                    <tr><td>YEAR :</td><td> <select id="year" name="year" disabled>
                                <option value="" selected>Select...</option>
                                <option>First year</option>
                                <option>Second year</option>
                                <option>Third year</option>
                            </select></td></tr>
                    <tr><td>DEPARTMENT :</td><td> <select id="dept" name="dept" disabled>
                                <option value="" selected>Select...</option>
                                <option>IT</option>
                                <option>COMPS</option>
                                <option>ELEX</option>
                                <option>EXTC</option>
                                <option>BIO-MED</option>
                                <option>PROD</option>
                                <option>MECH</option>
                            </select></td></tr>
                    <tr><td>GENDER:</td><td> <input type="radio" id="r1" name="r" disabled checked value="Male">Male<input type="radio" id="r2" name="r" disabled value="Female">Female</td></tr>
                    <tr><td>PASSWORD :</td><td> <input type="password" name="pass1"  class="tb" id="pass1" disabled></td></tr>
                    <tr><td>RE-ENTER : </td><td><input type="password" name="pass2"  class="tb" id="pass2" disabled></td></tr>
                    <tr><td> <input type="button" class="btn" id="cancel" value="Cancel" onclick="dis(2)"></td>
                        <td><input type="button" id="edit" class="btn" value="Edit" onclick="change()"></td></tr>
                </table>

            </form>
        </fieldset>
    </center>
</body>
</html>
