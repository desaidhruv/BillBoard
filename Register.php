<!DOCTYPE html>

<html>
    <head>
        <title>Register here..!!</title>
        <link rel="stylesheet" type="text/css" href="css/RegisterCss.css">
        <link rel="SHORTCUT ICON" href="image/bb.jpg">
        <script src="jquery/jquery.js"></script>
        <script src="jquery/jquery_validate.js"></script>
        <script>
            pic1 = new Image(16, 16);
            pic1.src = "image/loader.gif";
            $(document).ready(function () {
                $("#1").addClass("cActive");//current page active
                $("#form1").validate({
                    rules:
                            {
                                fname: "required",
                                lname: "required",
                                email:
                                        {
                                            required: true,
                                            email: true
                                        },
                                year: "required",
                                dept: "required",
                                //contact: "required",
                                contact: {
                                    //regex:"/^\d{10}$/",
                                    required: true,
                                    number: true,
                                    maxlength: 10,
                                    minlength: 10
                                },
                                r: "required",
                                username: "required",
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
                                fname: {required: "     Enter your name"},
                                lname: {required: "   Enter your last name"},
                                email: {required: "    Enter correct email id"},
                                username: {required: "  Enter username"},
                                year: {required: "    Select Year"},
                                dept: "    Select Department",
                                pass1: {required: "    Enter the password(min 6:max 8)"},
                                pass2: {equalTo: "  Passwords dont match"},
                                contact: {
                                    required: "    Enter Number",
                                    regex: "    Please enter valid number"
                                }
                            }
                });

                //starts username
                $("#username").keyup(function () {
                    var usr = $("#username").val();
                    if (usr.length >= 1)
                    {
                        $("#status").html(/*'<img align="absmiddle" src="loader.gif" />*/ 'Checking availability...');
                        $.post('usercheck.php', {username: usr}, function (data) {
                            $('#status').html(data);
                        });
                    }
                    else {
                        $("#status").html('');
                    }
                });
                //here ends username
            });
            //stop enter events
            function stopRKey(evt) {
                var evt = (evt) ? evt : ((event) ? event : null);
                var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
                if ((evt.keyCode == 13) && (node.type == "text")) {
                    return false;
                }
            }
            document.onkeypress = stopRKey;

            function valid1() {
                var z = $("#form1").validate();
                var r = [z.element("#fname"),
                    z.element("#lname"),
                    z.element("#year"),
                    z.element("#dept"),
                    //z.element("#r"),
                    z.element("#contact")];
                for (var i = 0; i < 5; i++) {
                    if (!r[i])
                        return false;
                }
                //if($("#r").)
                return true;
            }
            function valid2() {

                var z = $("#form1").validate();
                var r = [z.element("#email"),
                    z.element("#username"),
                    z.element("#pass1"),
                    z.element("#pass2")];
                for (var i = 0; i < 4; i++) {
                    if (!r[i])
                        return false;
                }
                return true;
            }
        </script> 

        <script type="text/javascript">
            function change(i)
            {
                switch (i)
                {
                    case 1:
                        var a = valid1();
                        if (a) {
                            document.getElementById("personal").style.display = 'none';
                            document.getElementById("userpass").style.display = 'block';
                            document.getElementById("profilepic").style.display = 'none';
                            $("#1").removeClass("cActive");
                            $("#2").addClass("cActive");
                            $("#3").removeClass("cActive");
                        }
                        break;
                    case 2:
                        var a = valid2();
                        if (a) {
                            document.getElementById("profilepic").style.display = 'block';
                            document.getElementById("userpass").style.display = 'none';
                            document.getElementById("personal").style.display = 'none';
                            $("#1").removeClass("cActive");
                            $("#3").addClass("cActive");
                            $("#2").removeClass("cActive");
                        }
                        break;
                    case 3:
                        document.getElementById("profilepic").style.display = 'none';
                        document.getElementById("userpass").style.display = 'none';
                        document.getElementById("personal").style.display = 'block';
                        $("#2").removeClass("cActive");
                        $("#1").addClass("cActive");
                        $("#3").removeClass("cActive");
                        break;
                    case 4:
                        var a1 = valid1();
                        if (a1) {
                            document.getElementById("profilepic").style.display = 'none';
                            document.getElementById("userpass").style.display = 'block';
                            document.getElementById("personal").style.display = 'none';
                            $("#1").removeClass("cActive");
                            $("#2").addClass("cActive");
                            $("#3").removeClass("cActive");
                        }
                        break;
                    case 5:
                        var a = valid2();
                        if (a) {
                            var a1 = valid1();
                            if (a1) {
                                document.getElementById("profilepic").style.display = 'block';
                                document.getElementById("userpass").style.display = 'none';
                                document.getElementById("personal").style.display = 'none';
                                $("#1").removeClass("cActive");
                                $("#3").addClass("cActive");
                                $("#2").removeClass("cActive");
                            }
                        }
                        break;
                }
            }
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#blah').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>


        <link rel ="stylesheet" href="RegisterCss.css">
    </head>
    <body>
        <div id='header'>
            <h1>Billboard</h1>
        </div>
    <center>
        <div class="circle"  id="1" onclick="change(3)">Personal Details</div>   
        <div class="circle"  id="2" onclick="change(4)">Login Details</div>    
        <div class="circle"  id="3" onclick="change(5)">Profile picture</div>   
    </center>

    <form id="form1" method="post" action="register_accept.php" enctype="multipart/form-data"><center><fieldset>
                <div id="personal">
                    <table>
                        <tr>
                            <td style="cursor:default">NAME :</td><td> <input type="text" id="fname" name="fname"></td>
                        </tr>
                        <tr><td style="cursor:default">SURNAME : </td><td><input type="text" id="lname" name="lname" ></td></tr>
                        <tr><td style="cursor:default">YEAR :</td><td> <select id="year" name="year">
                                    <option value="" selected>Select...</option>
                                    <option>First year</option>
                                    <option>Second year</option>
                                    <option>Third year</option>
                                </select></td></tr>
                        <tr><td style="cursor:default">DEPARTMENT :</td><td> <select id="dept" name="dept">
                                    <option value="" selected>Select...</option>
                                    <option>COMPS</option>
                                    <option>IT</option>
                                    <option>ELEX</option>
                                    <option>EXTC</option>
                                    <option>BIO-MED</option>
                                    <option>PROD</option>
                                    <option>MECH</option>
                                </select></td></tr>
                        <tr><td style="cursor:default">GENDER:</td><td> <input type="radio" id="r" name="r" checked value="Male">Male<input type="radio"name="r" value="Female">Female</td></tr>
                        <tr><td style="cursor:default">CONTACT :</td><td> <input type="text" id="contact" name="contact" maxlength="10" ></td></tr>
                    </table>
                    <center><input class="btn" type="button" id ="b1" value="Next" onclick="change(1)"></center> 
                </div>

                <div id="userpass">
                    <table>
                        <tr><td style="cursor:default">EMAIL :</td><td> <input type="email" id="email" name="email" required=""></td></tr>
                        <tr><td style="cursor:default">USERNAME :</td><td> <input type="text" id="username" name="username">
                                <div id="status"></div></td></tr>
                        <tr><td style="cursor:default">PASSWORD :</td><td> <input type="password" name="pass1" id="pass1"></td></tr>
                        <tr><td style="cursor:default">RE-ENTER : </td><td><input type="password" name="pass2" id="pass2"></td></tr>
                    </table>
                    <center><input class="btn" type="button" id ="b2" value="Next" onclick="change(2)"></center> 
                </div>   

                <div id="profilepic">
                    <input type="file"  accept="image/*"  onchange="readURL(this)" name="image"><br><br>
                    <img src="image/profile_default.jpg" id="blah" alt="profile"><br>
                    <input type="submit" class="btn" id="b3" value="Submit"> 
                </div>
            </fieldset></center>
    </form>
</body>
</html>
