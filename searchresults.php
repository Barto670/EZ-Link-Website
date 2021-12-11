<!DOCTYPE html>
<html style="background-color: #451093">
<head>
<title>Search</title>
</head>
<body>
<?php 

include('navbar.php'); 
require "db_connection.php";

session_start();

$sessionUserID = $_SESSION['UserID'];
$sessionUsername = $_SESSION['username'];

$type="";

$search = $_SESSION['search'];

if($_POST["goToUserProfile"]) { 
            if($_POST["UserID"] == $sessionUserID){
                header("location: myprofile.php");
            }else{
                $visitUserID = (int)$_POST["UserID"];
                $_SESSION['visitUserID']= (int)$visitUserID;

                $sql = "SELECT * FROM Users WHERE UserID = $visitUserID ";
                
                $result = mysqli_query($link, $sql);
                
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $_SESSION['visitusername'] = $row["username"];
                        $_SESSION['visitEmail'] = $row["Email"];
                        $_SESSION['visitAdmin'] = $row["Admin"];
                        $_SESSION['visitAge'] = $row["Age"];
                        $_SESSION['visitRegion'] = $row["Region"];
                        $_SESSION['visitPlatform'] = $row["Platform"];
                        $_SESSION['visitAbout'] = $row["About"];
                        $_SESSION['visitLanguages'] = $row["Languages"];
                        $_SESSION['visitOnline'] = $row["Online"];    
                        $_SESSION['visitLevel'] = $row["Level"];    
                        $_SESSION['visitLevelProgress'] = $row["LevelProgress"]; 
                        header("location: profile.php");
                    }
                }else{
                    echo "Could not successfully run query ($sql) from DB: " . mysqli_error();
                }
            } 
        }    


if($_POST["goToOrganisationProfile"]) { 
            $visitOrganisationID = (int)$_POST["OrganisationID"];
            $_SESSION['visitOrganisationID']= (int)$visitOrganisationID;

            $sql = "SELECT * FROM Organisations WHERE OrganisationID = $visitOrganisationID ";
            
            $result = mysqli_query($link, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $_SESSION['visitOrganisationOwnerID'] = $row["OwnerID"];
                    $_SESSION['visitOrganisationNickname'] = $row["Nickname"];
                    $_SESSION['visitOrganisationAbout'] = $row["About"];
                    $_SESSION['visitOrganisationRegion'] = $row["Region"];
                    $_SESSION['visitOrganisationPlatform'] = $row["Platform"];
                    header("location: organisation.php");
                }
            }else{
                echo "Could not successfully run query ($sql) from DB: " . mysqli_error();
            }
        }    
?>







 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body class="background display:block">
<link rel="stylesheet" href="styles.css">

<h3 style="color:white; font-weight:bold ; text-align: -webkit-center;">Search</h3>

<div class="col-sm-12">


    <?php
    if($_SESSION['search'] !=""){

                
    ?>
        
    <div class="col-sm-12 row ">
        <div class="col-sm-2 row "></div>
        <div class="col-sm-4 row " style="min-width:500px">
        
            <div class=" roundborder greybackground " style="padding:15px; margin-top:15px; min-height:15vh">
            <div style="text-align-last: center;">
                <label class="white" for="file" style="font-size:22px; color:#FFFFFFF;">Last User Search</label>
            </div>
                <?php

                                $sql2 = " SELECT * FROM `Users` WHERE `username` LIKE '%$search%' OR `Age` LIKE '%$search%' OR `Region` LIKE '%$search%' OR `Languages` LIKE '%$search%' OR `Platform` LIKE '%$search%'";

                                $result2 = mysqli_query($link, $sql2);

                                 if (mysqli_num_rows($result2) > 0) 
                                 {
                                     ?>
                                    <label class="white" for="file" >Users | Results = (<?php echo mysqli_num_rows($result2) ?>) | Searched: <?php echo strtoupper($search) ?></label>
                                    <?php
                                     echo "<table border='1' style='background-color:white ;padding:10px'>

                                        <tr>
                                        <th>Online</th>
                                        <th>Username</th>
                                        <th>Age</th>
                                        <th>Region</th>
                                        <th>Language</th>
                                        <th>Platform</th>
                                        <th>Profile</th>";
                                        echo "</tr>";  
                                    while($row2 = mysqli_fetch_assoc($result2))
                                    {
                                    
                                        

                                        $tempUserID = $row2['UserID'];
                                        $tempusername = $row2['username'];
                                        $tempOnline = $row2['Online'];

                                           



                                        echo "<tr>";

                                        if($row2['Online'] == 0){
                                            echo "<td>" . 
                                            "<i class='large material-icons' style='margin-left:10px; color:red '>remove_circle</i>" . 
                                            "</td>";
                                        }else{
                                            echo "<td>" . 
                                            "<i class='large material-icons' style='margin-left:10px; color:green '>check_circle</i>" . 
                                            "</td>";
                                        }
                                        
                                        echo "<td style='text-align: center'>" . $row2['username'] . "</td>";

                                        echo "<td style='text-align: center'>" . $row2['Age'] . "</td>";

                                        echo "<td style='text-align: center'>" . $row2['Region'] . "</td>";

                                        echo "<td style='text-align: center'>" . $row2['Languages'] . "</td>";

                                        echo "<td style='text-align: center'>" . $row2['Platform'] . "</td>";

                                        echo "<td style='text-align: center'>" . 
                                        "<form method='post'>
                                            <input id='UserID' name='UserID' type='hidden' value='$tempUserID'>
                                            <button type='submit' name='goToUserProfile' class='button tablebutton' value='button'><i class='large material-icons'>account_circle</i></button> 
                                        </form>". 
                                        "</td>";
                                        
                                        echo "</tr>";

                                        
                                    }
                                }else{
                                            ?>
                                            <div style="text-align-last: center; background: #451093; min-height:7vh; border-radius:5px;margin:10px">
                                                <h5 class="white" for="file" style="color:white;font-weight:bolder">No Users found..</h5>
                                                <label class="white col-sm-12" for="file" style=" color:white;font-weight:bolder"><u>Searched</u></label>
                                                <label class="white col-sm-12" for="file" style=" color:white;font-weight:bolder"><?php echo strtoupper($search) ?></label>

                                            </div>
                                            <?php
                                 }
                            
                        

                            echo "</table>";



                        ?>

            </div>
        </div>


        <div class="col-sm-4 row " style="margin-left:15px ;min-width:500px">
        
            <div class=" roundborder greybackground " style="padding:15px; margin-top:15px; min-height:15vh">
            <div style="text-align-last: center;">
                <label class="white" for="file" style="font-size:22px; color:#FFFFFFF;">Last Organisation Search</label>
            </div>
                <?php
                        

                               $sql3 = " SELECT * FROM `Organisations` WHERE `Nickname` LIKE '%$search%' OR `Region` LIKE '%$search%' OR `Platform` LIKE '%$search%'";

                                $result3 = mysqli_query($link, $sql3);

                                 if (mysqli_num_rows($result3) > 0) 
                                 {
                                     ?>
                                    <label class="white" for="file" >Organisation | Results = (<?php echo mysqli_num_rows($result3) ?>) | Searched: <?php echo strtoupper($search) ?></label>
                                    <?php
                                     echo "<table border='1' style='background-color:white ;padding:10px'>

                                        <tr>
                                        <th>Name</th>
                                        <th>Region</th>
                                        <th>Platform</th>
                                        <th>Profile</th>";
                                        echo "</tr>";  
                                    while($row3 = mysqli_fetch_assoc($result3))
                                    {
                                    
                                        

                                        $visitOrganisationID = $row3['OrganisationID'];

                                           

                                        echo "<tr>";

                                        echo "<td style='text-align: center'>" . $row3['Nickname'] . "</td>";

                                        echo "<td style='text-align: center'>" . $row3['Region'] . "</td>";

                                        echo "<td style='text-align: center'>" . $row3['Platform'] . "</td>";

                                        echo "<td >" . 
                                            "<form method='post'>
                                                <input id='OrganisationID' name='OrganisationID' type='hidden' value='$visitOrganisationID'>
                                                <button type='submit' name='goToOrganisationProfile' class='button tablebutton' value='button'><i class='large material-icons'>account_circle</i></button> 
                                            </form>". 
                                            "</td>";
                                        
                                        echo "</tr>";

                                        
                                    }
                                }else{
                                            ?>
                                            <div style="text-align-last: center; background: #451093; min-height:7vh; border-radius:5px;margin:10px">
                                                <h5 class="white" for="file" style="color:white;font-weight:bolder">No Organisations found..</h5>
                                                <label class="white col-sm-12" for="file" style=" color:white;font-weight:bolder"><u>Searched</u></label>
                                                <label class="white col-sm-12" for="file" style=" color:white;font-weight:bolder"> <?php echo strtoupper($search)?></label>

                                            </div>
                                            <?php
                                 }
                            
                        

                            echo "</table>";

                            mysqli_close($link);

                        ?>




        </div>
        <?php
        }
        ?>

            </div>
        </div>


        
</div> 

</body>
</html>