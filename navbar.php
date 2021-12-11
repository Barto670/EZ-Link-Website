<!DOCTYPE html>
<html lang="en">

<?php 
require "db_connection.php";
session_start();

$sessionUserID = $_SESSION['UserID'];
$sessionLevel = $_SESSION['Level'];
$sessionLevelProgress = $_SESSION['LevelProgress']; 
$chatUserID = $_SESSION['chatUserID'];
$chatOnline = $_SESSION['chatOnline'];
$chatusername = $_SESSION['chatusername'];



if($_SESSION["loggedin"] === false || $sessionUserID === ""){
    header("location: index.php");
}

if(array_key_exists('close', $_POST)) { 
        $_SESSION['chatOpen'] = false;     
}


if($_POST["search"]){
    $_SESSION['search'] = $_POST["search"];
    header("location: searchresults.php");
}

if($_POST["message"]){
    
        $message = $_POST["message"];
        $lastMessageID;
        $sql5 = "SELECT MessageID FROM Messages ORDER BY MessageID DESC LIMIT 1";
      
        $result5 = mysqli_query($link, $sql5);
        
        if (mysqli_num_rows($result5) > 0) {
            while($row5 = mysqli_fetch_assoc($result5)) {
                $lastMessageID = (int)$row5["MessageID"] + 1;


                $sql = "INSERT INTO Messages (MessageID, SenderID , ReceiverID , message) values (?, ?, ?, ?)";
                if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "ssss", $lastMessageID, $sessionUserID, $chatUserID , $message);

                    if(mysqli_stmt_execute($stmt)){
                        header("Refresh:0");
                    }
                }

            
            }
        }
}


if(array_key_exists('logout', $_POST))
{

    $sql2 = "UPDATE Users SET Online = ? WHERE UserID = ?";
    if($stmt2 = mysqli_prepare($link, $sql2))
    {
        $temp = 0;
        mysqli_stmt_bind_param($stmt2, "ss", $temp, $_SESSION["UserID"]);

        if(mysqli_stmt_execute($stmt2)){
            $_SESSION["LoggedIn"] = false;

            $_SESSION["UserID"] = null;
            $_SESSION["username"] = null;       
            $_SESSION["Admin"] = null;     
            $_SESSION["password"] = null;       
            $_SESSION["Online"] = null;       
            $_SESSION["Level"] = null;  
            $_SESSION["LevelProgress"] = null;   
            header("location: index.php");
        }

    }
}





?>

<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>

<body>



<header style="background-color:#232323;" class="navbar navbar-default navbar-static-top" >
    <div class="container-fluid">
        <!--<div class="navbar-header">
            <a href="#" class="navbar-brand whitetopurple"></a>
            <button class="whitetopurple" type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><i class="fa fa-bars"></i></button>
        </div> -->
        
        <div class="navbar-collapse">
            
            <ul class="nav navbar-nav navbar-left ">
                <li><img src="./assets/images/logoOnlyOutline.png" alt="logoOnlyOutline" width="100" height="40" style="margin-top:5px; margin-right:10px"></li>
                <li class="borderleftgrey borderrightgrey topnavbutton"><a href="home.php">Home</a></li>                    
                <li class="borderrightgrey topnavbutton"><a href="search.php">Search</a></li>
                <li class="borderrightgrey topnavbutton"><a href="vacancies.php">Vacancies</a></li>
                <?php
                if($_SESSION['Admin'] == 1){
                ?>
                    <li class="borderrightgrey topnavbutton"><a href="admin.php">Admin</a></li>
                <?php
                }
                ?>
            </ul>

            <div style="margin-left:10vw; margin-top: 10px; border-radius:5px ; max-width:38vw ; min-width:38vw" class="navbar-left" >
                        <form method='post' class="example">
                            <input type="text" placeholder="Search.." name="search">
                        </form>
                </div>


            <ul class="nav navbar-nav navbar-right">
                    
                    
                    <?php
                    if($_SESSION['Admin'] == 1){
                    ?>
                    
                    <li style="margin-right: 2px;">
                        <label class="white" for="file" style="margin-top:45% ;color:green">Admin</label>
                    </li>
                    <li style="margin-right: 30px;">
                        <i class='large material-icons' style='margin-top:60%; color:green '>check_circle</i>
                    </li>
                    <?php
                    }
                    ?>
                    
                    <li style="margin-right: 10px;">
                        <form method='post'>
                            <mat-card style="padding:10px">
                                <button style ='margin-top:14px;padding:5px' type='submit' name='logout' class="purplebutton">Logout</button>
                            </mat-card>
                        </form>
                    </li>
                    <li>
                        <label class="white" for="file" style="margin-top:40%">Level <?php echo $_SESSION["Level"] ?></label>
                    </li>
                    <li>
                            
                            <progress id="file" value=<?php echo $sessionLevelProgress?> max="100" style="margin-top: 14%; margin-left: 4%;"></progress>
                    </li>
                    <li>
                        <a href="myprofile.php">
                            <button class="namebutton"><?php echo $_SESSION["username"] ?>
                            </button>
                        </a>
                    </li>

                    <li>
                        <mat-card >
                            <a href="myprofile.php">
                                <img src="./assets/icons/male.png" width="40" height "40" style="margin-top:5px ; margin-right: 10px;">
                            </a>
                        </mat-card>
                    </li>   
                     
            </ul>

            


</header>
<?php

    if($_SESSION['chatOpen']=="true")
    {
    
    ?>
        
        <div class="sidenav col-sm-11 ">
            <div id="scrollable" class="col-sm-12 row" >
                    
                        
                        <?php


                        echo "<table border='0' style='background-color:white ;padding:10px ;max-height:10vw; min-height:10vh; min-width: 13vw;max-width: 13vw;'>";


                                $sql4 = "SELECT * FROM Messages WHERE SenderID = '$sessionUserID' AND ReceiverID = '$chatUserID' OR ReceiverID = '$sessionUserID' AND SenderID = '$chatUserID' ORDER BY MessageID DESC";

                                $result4 = mysqli_query($link, $sql4);

                                 if (mysqli_num_rows($result4) > 0) 
                                 {
                                    while($row4 = mysqli_fetch_assoc($result4))
                                    {

                                        
                                        
                                        if($chatUserID == $row4['SenderID']){
                                            echo "<tr>";
                                            echo "<td style='min-width: 90px !important;'>" . $row4['message'] . "</td>";
                                            echo "<td style='min-width: 90px !important;'>" . "" . "</td>";
                                            echo "</tr>";
                                        }else{
                                            echo "<tr>";
                                            echo "<td style='min-width: 90px !important;'>" . "" . "</td>";
                                            echo "<td style='text-align: end; min-width: 90px !important;'>" . $row4['message'] . "</td>";
                                            echo "</tr>";
                                        }

                                        
                                    }
                                 }

                            echo "</table>";

                            mysqli_close($link);

                        ?>


                    
                </div>

            <div style="margin-top: 5px;">

            </div>

            <div class="sidenav2 col-sm-11 ">
                <div>
                    <form method='post'>
                    <button type='submit' name='close' value='button' style="background-color:grey"><i class='tiny material-icons' style="font-size: 12px;">close</i></button> 
                        
                            <label class="white" for="file" style="margin-left:10px;"><?php echo $chatusername ?></label>
                            <?php
                            if($chatOnline == 0)
                            {
                            ?>
                                <label class="white" for="file" style='color:red'>Offline</label>
                                <i class='tiny material-icons' style='color:red ; font-size: 15px'>remove_circle</i>
                            <?php
                            }else{
                            ?>
                                <label class="white" for="file"style='color:green'>Online</label>
                                <i class='tiny material-icons' style='color:green ; font-size: 15px'>check_circle</i>
                            <?php
                            }
                            ?>
                            
                        
                    </form>
                </div>
            </div>

        </div>

        <div class="sidenav3 col-sm-11 ">
                <div>
                    <form method='post'>
                                <input type="text" name="message" style="max-width:12vw" sty class="form-control" autocomplete="off">  
                    </form>
                </div>
            </div>
    <?
    } 
    ?>
        
</body>

</html>