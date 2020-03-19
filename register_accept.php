<!DOCTYPE html>
<html>
    <head>
        <title>
            Registration
        </title>
    </head>    
    <body>
        <?php
        $db = mysqli_connect("localhost", "root", "", "billboard");

        //all values
        $name = $_POST["fname"];
        $surname = $_POST["lname"];
        $year = $_POST["year"];
        $dept = $_POST["dept"];
        $gender = $_POST["r"];
        $emailid = $_POST["email"];
        $username = $_POST["username"];
        $password = $_POST["pass1"];
        $contact = $_POST["contact"];
        $image_type;
        $image;
        $xyz = false;

        if (isset($_FILES['image'])) {
            $maxsize = 10000000; //set to approx 10 MB
            //check whether file is uploaded with HTTP POST
            if (is_uploaded_file($_FILES['image']['tmp_name'])) {
                //checks size of uploaded image on server side
                if ($_FILES['image']['size'] < $maxsize) {
                    //checks whether uploaded file is of image type                    
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    if (strpos(finfo_file($finfo, $_FILES['image']['tmp_name']), "image") === 0) {
                        // prepare the image for insertion
                        $image_type = $_FILES['image']['name'];
                        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
                        $xyz = true;
                    }
                }
            }
        }
        if (!$xyz) {
            $image = addslashes(file_get_contents("image/profile_default.jpg"));
            $image_type = "profile_default.jpg";
        }



        if (isset($name) && isset($surname) && isset($year) && isset($dept) && isset($gender) && isset($emailid) && isset($username) && isset($password) && isset($contact)) {
            $name = trim($name);
            $surname = trim($surname);
            $dept = trim($dept);
            $year = trim($year);
            $gender = trim($gender);
            $emailid = trim($emailid);
            $username = trim($username);

            $check = "select `username` from `usersdetails` where `username`='$username'";
            $res = mysqli_query($db, $check);
            if (mysqli_num_rows($res) > 0) {
                echo "<h1>Failed to add Username already exists</h1>";
            } else {
                $query = "insert into `usersdetails` (`name`,`surname`,`year`,`dept`,`gender`,`contact`,`emailid`"
                        . ",`username`,`password`,`image_type`,`image`)"
                        . " values('$name','$surname','$year','$dept','$gender','$contact','$emailid'"
                        . ",'$username','$password','$image_type','$image')";
                if (mysqli_query($db, $query)) {
                    $query = "create table `$username` like `usersample`";
                    $createtable = mysqli_query($db, $query);
                    //mail
                    require_once('PHPMailer_5.2.4/class.phpmailer.php');
                    
                    $msg = "<h2>Welcome to BillBoard</h2>
                            Thank you for registering <b>$name $surname</b> your details are:
                            <br>
                            Username:<b>".$username."</b>
                            <br>
                            Password:<b>".$password."</b>";

                    $mail = new PHPMailer(); // create a new object
                    $mail->IsSMTP(); // enable SMTP
                    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
                    $mail->SMTPAuth = true; // authentication enabled
                    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
                    $mail->Host = "smtp.gmail.com";
                    $mail->Port = 465; // or 587
                    $mail->IsHTML(true);
                    $mail->Username = "billboard.reg@gmail.com";
                    $mail->Password = "DUAbillboard";

                    $mail->SetFrom("billboard.reg@gmail.com");
                    $mail->Subject = "BillBoard: Registration Successful";
                    $mail->Body = $msg;
                    $mail->AddAddress("$emailid");

                    if (!$mail->Send()) {
                        $mail="no";
                        echo "Mailer Error: " . $mail->ErrorInfo;
                    } else {
                        $mail="yes";
                        echo "Message has been sent";
                    }
                    //echo "<h1>Successfully Registered $username</h1>";
                    header("Location: index.php?register=true&mail=$mail");
                } else {
                    echo "Error: " . mysqli_error($db);
                }
            }

            mysqli_close($db);
        }
        ?>
    </body>
</html>


