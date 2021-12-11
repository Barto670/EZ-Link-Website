<?php

session_start();
$_SESSION['chatOpen'] = false; 
$_SESSION["loggedin"] = false;

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit;
}


?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body class="background">
<link rel="stylesheet" href="styles.css">
        <div class="container">
 
            <div class="row" style="padding-top:27vh">
                <div class="center-block">
                    <div class="col-sm-12">
                    <img src="./assets/images/homelogo.PNG" class="img-fluid" style="margin-left:5vh">
                    </div>
                    <div style="padding-left: 20%" class="row">
                    <div class ="col-sm-4">
                        <button class="animation" style="font-family: sans-serif;" onclick="location.href='login.php'"><span>Login</span></button>
                        </div>
                        <div class ="col-sm-4"/>
                        <div class ="col-sm-4">
                            <button class="animation buttonmain" style="font-family: sans-serif;" onclick="location.href='register.php'"><span>Register</span></button>
                        </div>

                          
                    </div>  
                </div>  
            </div> 
        </div>   
        <div class="center">
    <div class="col-sm-12 roundborder greybackground wrapper" style=" margin-left:5% ; margin-top: 30px">
    <p class="white wrap"> EZ-Link is a online website to help you connect with other players and esport organisations all over the world. <br><br>
                Join other players and professionals interested in competing in the same games as you.
    </p>
    </div>

        </div>
</body>
</html>