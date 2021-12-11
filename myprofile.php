<!DOCTYPE html>
<html style="background-color: #451093">
<head>
<title>My Profile</title>
</head>
<body>
<?php 

include('navbar.php'); 
require "db_connection.php";

session_start();

$sessionUserID = $_SESSION['UserID'];
$sessionUsername = $_SESSION['username'];
$sessionPassword = $_SESSION['password']; 
$sessionLevel = $_SESSION['Level'];
$sessionLevelProgress = $_SESSION['LevelProgress']; 

$organisation_err= "";

$_SESSION['button']="edit";
$_SESSION['nickname']="disable";
$_SESSION['email']="disable";
$_SESSION['age']="disable";
$_SESSION['region']="disable";
$_SESSION['platform']="disable";
$_SESSION['languages']="disable";
$_SESSION['about']="disable";

    $sql = "SELECT UserID, username, Email , Admin , Age, Region , Platform , About , Languages, Online FROM Users WHERE UserID = '$sessionUserID'";
            
    $result = mysqli_query($link, $sql);
            
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $userID = (int)$row["UserID"];
            $username = $row["username"];
            $Email = $row["Email"];
            $Admin = $row["Admin"];
            $Age = $row["Age"];
            $Region = $row["Region"];
            $Platform = $row["Platform"];
            $About = $row["About"];
            $Languages = $row["Languages"];
            $Online = $row["Online"];
            $username_err = "";
        }
    }



if($_POST["Update"])
{
    $sql = "UPDATE Users SET Age = ? , Email = ? , Region = ? , Platform = ? , Languages = ? , About = ? WHERE UserID = ?";
    if($stmt = mysqli_prepare($link, $sql) )
    {
        mysqli_stmt_bind_param($stmt, "sssssss", $_POST['Age'] , $_POST['Email'] , $_POST['Region'] , 
        $_POST['Platform'] , $_POST['Languages'] , $_POST['About'], $sessionUserID);
        if(mysqli_stmt_execute($stmt) ){
            getUserData();
            header("Refresh:0");
        }else{
            echo "Something went wrong. Please try again later.";
            printf("Error: %s.\n", $stmt->error);
        }
        mysqli_stmt_close($stmt);
    }
}

if(isset($_POST['editProfile'])) { 
        $_SESSION['button']="view";
        $_SESSION['nickname']="enabled";
        $_SESSION['email']="enabled";
        $_SESSION['age']="enabled";
        $_SESSION['region']="enabled";
        $_SESSION['platform']="enabled";
        $_SESSION['languages']="enabled";
        $_SESSION['about']="enabled";
    }

if(isset($_POST['viewProfile'])) { 
        $_SESSION['button']="edit";
        $_SESSION['nickname']="disable";
        $_SESSION['email']="disable";
        $_SESSION['age']="disable";
        $_SESSION['region']="disable";
        $_SESSION['platform']="disable";
        $_SESSION['languages']="disable";
        $_SESSION['about']="disable";
} 


function getUserData() {

    $sql = "SELECT UserID, username, Email , Admin , Age, Region , Platform , About , Languages, Online FROM Users WHERE UserID = '$sessionUserID'";
            
    $result = mysqli_query($link, $sql);
            
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $userID = (int)$row["UserID"];
            $username = $row["username"];
            $Email = $row["Email"];
            $Admin = $row["Admin"];
            $Age = $row["Age"];
            $Region = $row["Region"];
            $Platform = $row["Platform"];
            $About = $row["About"];
            $Languages = $row["Languages"];
            $Online = $row["Online"];
            $username_err = "";
            $sendusername = "";
        }
    }
}

if($_POST["openChat"]) { 
        echo "opened";
        $_SESSION['chatOpen'] = true;  
        $_SESSION['chatUserID'] = $_POST["UserID"];
        $_SESSION['chatusername'] = $_POST["username"]; 
        $_SESSION['chatOnline'] = $_POST["Online"];
        header( "refresh:0",false);
}

if($_POST["goToUserProfile"]) { 
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



if($_POST["createOrganisation"]) { 
            $newOrganisationName = $_POST["name"];

            $sql = "SELECT OrganisationID FROM Organisations WHERE Nickname = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_name);
            
            $param_name= $newOrganisationName;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 0){
                    $organisation_err= "";

                    $temp;
                    $sql = "SELECT OrganisationID FROM Organisations ORDER BY OrganisationID DESC LIMIT 1";
                
                    
                    $result = mysqli_query($link, $sql);
                    
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            $temp = (int)$row["OrganisationID"] + 1;
                        }
                    }


                    $sql = "INSERT INTO Organisations (OwnerID , Nickname , OrganisationID) values (?, ?, ?)";

                    if($stmt = mysqli_prepare($link, $sql)){
                        mysqli_stmt_bind_param($stmt, "sss", $sessionUserID , $newOrganisationName, $temp);

                        if(mysqli_stmt_execute($stmt)){
                            
                        $sql = "INSERT INTO Members (UserID, OrganisationID) values (?, ?)";

                        if($stmt = mysqli_prepare($link, $sql)){
                        mysqli_stmt_bind_param($stmt, "ss", $sessionUserID ,$temp);

                        if(mysqli_stmt_execute($stmt)){
                            

                            $sql = "SELECT * FROM Organisations WHERE OrganisationID = $temp ";
            
                            $result = mysqli_query($link, $sql);
                            
                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    $_SESSION['visitOrganisationOwnerID'] = $row["OwnerID"];
                                    $_SESSION['visitOrganisationNickname'] = $row["Nickname"];
                                    $_SESSION['visitOrganisationAbout'] = $row["About"];
                                    $_SESSION['visitOrganisationRegion'] = $row["Region"];
                                    $_SESSION['visitOrganisationPlatform'] = $row["Platform"];
                                    header("location: myprofile.php");
                                    header("location: organisation.php");
                                }
                            }else{
                                echo "Could not successfully run query ($sql) from DB: " . mysqli_error();
                            }


                        } else{
                            echo "Something went wrong. Please try again later.";
                            printf("Error: %s.\n", $stmt->error);
                        }

                    mysqli_stmt_close($stmt);
                    }




                        } else{
                            echo "Something went wrong. Please try again later.";
                            printf("Error: %s.\n", $stmt->error);
                        }

                    mysqli_stmt_close($stmt);
                    }





                }else{
                    $organisation_err= "This Organisation already exists";
                }
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


if($_POST["decline"]) { 
            $tempRequestID = (int)$_POST["RequestID"];

            $sql = "DELETE FROM FriendRequests WHERE RequestID = $tempRequestID;";
            
            mysqli_query($link, $sql);
            
        }  

if($_POST["accept"]) { 
            $tempRequestID = (int)$_POST["RequestID"];
            $tempAccepterID = $sessionUserID;
            $tempRequesterID = (int)$_POST["RequesterID"];

            $sql = "DELETE FROM FriendRequests WHERE RequestID = $tempRequestID;";
            
            mysqli_query($link, $sql);

            $sql = "INSERT INTO FriendsList (UserID, FriendID) values (?, ?)";
            if($stmt = mysqli_prepare($link, $sql))
            {
                mysqli_stmt_bind_param($stmt, "ss", $tempAccepterID, $tempRequesterID);

                mysqli_stmt_execute($stmt);


            mysqli_stmt_close($stmt);
            }

            $sql2 = "INSERT INTO FriendsList (FriendID, UserID) values (?, ?)";
            if($stmt2 = mysqli_prepare($link, $sql2))
            {
                mysqli_stmt_bind_param($stmt2, "ss", $tempAccepterID, $tempRequesterID);

                mysqli_stmt_execute($stmt2);


            mysqli_stmt_close($stmt2);
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

<h3 style="color:white; font-weight:bold ; text-align: -webkit-center;">My Profile</h3>

<div class="col-sm-12">

    <div class="col-sm-12">

        <div class="col-sm-4 row " style="padding:5px;min-width:610px; max-width:610px" >
            <div class=" roundborder greybackground " style="padding:20px">
                <button class="namebutton"><?php echo $_SESSION["username"] ?></button>
                <img src="./assets/icons/male.png" width="40" height "40" ">
                <label class="white" for="file" >Level <?php echo $_SESSION["Level"] ?></label>
                <progress id="file" value=<?php echo $sessionLevelProgress?>  max="100" ></progress>
            </div>
        </div>

        
        

    </div>

    <div class="col-sm-12">

        <div class="col-sm-4 row" style="padding:5px;min-width:310px; max-width:310px">
            
            <div class="roundborder greybackground " style="padding:15px">

                <form method="post" style="margin-bottom:10px">
                            <?php
                            if($_SESSION['button']=="view")
                            {?>
                                <input type="submit" name="viewProfile" class="editbutton btn" value="Click to View"/> 
                            <?
                            } 
                            else
                            {?> 
                                <input type="submit" name="editProfile" class="editbutton btn" value="Click to Edit"/> 
                            <? 
                            }
                        ?>
                    
                </form> 
                <form method="post" style="margin-bottom:10px">
                    <div class="form-group">
                        <label class="white">Email</label>
                        <?php
                            if($_SESSION['email']=="disable")
                            {?>
                                <input type="text" name="Email" class="form-control" disabled value="<?php echo $Email; ?>">
                            <?
                            } 
                            else
                            {?> 
                                <input type="text" name="Email" class="form-control" value="<?php echo $Email; ?>">
                            <? 
                            }
                        ?>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label class="white">Age</label>
                        <?php
                            if($_SESSION['age']=="disable")
                            {?>
                                <input type="text" name="Age" class="form-control" disabled value="<?php echo $Age; ?>">
                            <?
                            } 
                            else
                            {?> 
                                <input type="text" name="Age" class="form-control" value="<?php echo $Age; ?>">
                            <? 
                            }
                        ?>
                        
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label class="white">Region</label>
                            <?php
                            if($_SESSION['region']=="disable")
                            {?>
                                <input type="text" name="Region" class="form-control" disabled value="<?php echo $Region; ?>">
                            <?
                            } 
                            else
                            {
                            ?> 
                            <div class="col-12">
                                <select name="Region" id="Region" ">
                                    <option <?php if($Region == ''){echo("selected");}?> disabled value> -- select an option -- </option>
                                    <option <?php if($Region == 'Africa'){echo("selected");}?> value="Africa">Africa</option>
                                    <option <?php if($Region == 'Asia'){echo("selected");}?> value="Asia">Asia</option>
                                    <option <?php if($Region == 'The Caribbean'){echo("selected");}?> value="The Caribbean">The Caribbean</option>
                                    <option <?php if($Region == 'Central America'){echo("selected");}?> value="Central America">Central America</option>
                                    <option <?php if($Region == 'Europe'){echo("selected");}?> value="Europe">Europe</option>
                                    <option <?php if($Region == 'North America'){echo("selected");}?> value="North America">North America</option>
                                    <option <?php if($Region == 'Oceania'){echo("selected");}?> value="Oceania">Oceania</option>
                                    <option <?php if($Region == 'South America'){echo("selected");}?> value="South America">South America</option>
                                </select>
                            </div>
                            <? 
                            }
                            ?>
                        
                        <span class="help-block"></span>
                    </div>

                    <div class="form-group">
                        <label class="white">Platform</label>

                            <?php
                            if($_SESSION['platform']=="disable")
                            {?>
                                <input type="text" name="Platform" class="form-control" disabled value="<?php echo $Platform; ?>">
                            <?
                            } 
                            else
                            {?> 
                            <div class="col-12">
                                <select name="Platform" id="Platform">
                                    <option <?php if($Platform == ''){echo("selected");}?> disabled value> -- select an option -- </option>
                                    <option <?php if($Platform == 'PC'){echo("selected");}?> value="PC">PC</option>
                                    <option <?php if($Platform == 'XBOX'){echo("selected");}?> value="XBOX">XBOX</option>
                                    <option <?php if($Platform == 'Playstation'){echo("selected");}?> value="Playstation">Playstation</option>
                                    <option <?php if($Platform == 'Nintendo'){echo("selected");}?> value="Nintendo">Nintendo</option>
                                </select>
                            </div>
                            <? 
                            }
                            ?>
                        
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label class="white">Languages</label>

                        <?php
                            if($_SESSION['languages']=="disable")
                            {?>
                                <input type="text" name="Languages" class="form-control" disabled value="<?php echo $Languages; ?>">
                            <?
                            } 
                            else
                            {?> 
                            <div class="col-12">
                                <select name="Languages" id="Languages" >
                                    <option <?php if($Languages == ''){echo("selected");}?> disabled value> -- select an option -- </option>
                                    <option <?php if($Languages == 'Albanian'){echo("selected");}?> value="Albanian">Albanian</option>
                                    <option <?php if($Languages == 'Armenian'){echo("selected");}?> value="Armenian">Armenian</option>
                                    <option <?php if($Languages == 'Azeri'){echo("selected");}?> value="Azeri">Azeri</option>
                                    <option <?php if($Languages == 'Bosnian'){echo("selected");}?> value="Bosnian">Bosnian</option>
                                    <option <?php if($Languages == 'Bulgarian'){echo("selected");}?> value="Bulgarian">Bulgarian</option>
                                    <option <?php if($Languages == 'Byelorussian'){echo("selected");}?> value="Byelorussian">Byelorussian</option>
                                    <option <?php if($Languages == 'Catalan'){echo("selected");}?> value="Catalan">Catalan</option>
                                    <option <?php if($Languages == 'Chinese'){echo("selected");}?> value="Chinese">Chinese</option>
                                    <option <?php if($Languages == 'Croatian'){echo("selected");}?> value="Croatian">Croatian</option>
                                    <option <?php if($Languages == 'Czech'){echo("selected");}?> value="Czech">Czech</option>
                                    <option <?php if($Languages == 'Danish'){echo("selected");}?> value="Danish">Danish</option>
                                    <option <?php if($Languages == 'Dari'){echo("selected");}?> value="Dari">Dari</option>
                                    <option <?php if($Languages == 'Dutch'){echo("selected");}?> value="Dutch">Dutch</option>
                                    <option <?php if($Languages == 'English'){echo("selected");}?> value="English">English</option>
                                    <option <?php if($Languages == 'Estonian'){echo("selected");}?> value="Estonian">Estonian</option>
                                    <option <?php if($Languages == 'Finnish'){echo("selected");}?> value="Finnish">Finnish</option>
                                    <option <?php if($Languages == 'Flemish'){echo("selected");}?> value="Flemish">Flemish</option>
                                    <option <?php if($Languages == 'French'){echo("selected");}?> value="French">French</option>
                                    <option <?php if($Languages == 'Georgian'){echo("selected");}?> value="Georgian">Georgian</option>
                                    <option <?php if($Languages == 'German'){echo("selected");}?> value="German">German</option>
                                    <option <?php if($Languages == 'Gilbertesian'){echo("selected");}?> value="Gilbertesian">Gilbertesian</option>
                                    <option <?php if($Languages == 'Greek'){echo("selected");}?> value="Greek">Greek</option>
                                    <option <?php if($Languages == 'Hebrew'){echo("selected");}?> value="Hebrew">Hebrew</option>
                                    <option <?php if($Languages == 'Hindi'){echo("selected");}?> value="Hindi">Hindi</option>
                                    <option <?php if($Languages == 'Hungarian'){echo("selected");}?> value="Hungarian">Hungarian</option>
                                    <option <?php if($Languages == 'Icelandic'){echo("selected");}?> value="Icelandic">Icelandic</option>
                                    <option <?php if($Languages == 'Irish'){echo("selected");}?> value="Irish">Irish</option>
                                    <option <?php if($Languages == 'Italian'){echo("selected");}?> value="Italian">Italian</option>
                                    <option <?php if($Languages == 'Japanese'){echo("selected");}?> value="Japanese">Japanese</option>
                                    <option <?php if($Languages == 'Kazakh'){echo("selected");}?> value="Kazakh">Kazakh</option>
                                    <option <?php if($Languages == 'Kirgiz'){echo("selected");}?> value="Kirgiz">Kirgiz</option>
                                    <option <?php if($Languages == 'Korean'){echo("selected");}?> value="Korean">Korean</option>
                                    <option <?php if($Languages == 'Kurdish'){echo("selected");}?> value="Kurdish">Kurdish</option>
                                    <option <?php if($Languages == 'Lao'){echo("selected");}?> value="Lao">Lao</option>
                                    <option <?php if($Languages == 'Latin'){echo("selected");}?> value="Latin">Latin</option>
                                    <option <?php if($Languages == 'Latvian'){echo("selected");}?> value="Latvian">Latvian</option>
                                    <option <?php if($Languages == 'Letzebuergesh'){echo("selected");}?> value="Letzebuergesh">Letzebuergesh</option>
                                    <option <?php if($Languages == 'Lithuanian'){echo("selected");}?> value="Lithuanian">Lithuanian</option>
                                    <option <?php if($Languages == 'Macedonian'){echo("selected");}?> value="Macedonian">Macedonian</option>
                                    <option <?php if($Languages == 'Maltese'){echo("selected");}?> value="Maltese">Maltese</option>
                                    <option <?php if($Languages == 'Moldawian'){echo("selected");}?> value="Moldawian">Moldawian</option>
                                    <option <?php if($Languages == 'Mongolian'){echo("selected");}?> value="Mongolian">Mongolian</option>
                                    <option <?php if($Languages == 'Norwegian'){echo("selected");}?> value="Norwegian">Norwegian</option>
                                    <option <?php if($Languages == 'Pashtu'){echo("selected");}?> value="Pashtu">Pashtu</option>
                                    <option <?php if($Languages == 'Persian'){echo("selected");}?> value="Persian">Persian</option>
                                    <option <?php if($Languages == 'Polish'){echo("selected");}?> value="Polish">Polish</option>
                                    <option <?php if($Languages == 'Portuguese'){echo("selected");}?> value="Portuguese">Portuguese</option>
                                    <option <?php if($Languages == 'Quechua'){echo("selected");}?> value="Quechua">Quechua</option>
                                    <option <?php if($Languages == 'Romanian'){echo("selected");}?> value="Romanian">Romanian</option>
                                    <option <?php if($Languages == 'Russian'){echo("selected");}?> value="Russian">Russian</option>
                                    <option <?php if($Languages == 'Serbian'){echo("selected");}?> value="Serbian">Serbian</option>
                                    <option <?php if($Languages == 'Sindhi'){echo("selected");}?> value="Sindhi">Sindhi</option>
                                    <option <?php if($Languages == 'Singhalese'){echo("selected");}?> value="Singhalese">Singhalese</option>
                                    <option <?php if($Languages == 'Slovak'){echo("selected");}?> value="Slovak">Slovak</option>
                                    <option <?php if($Languages == 'Slovenian'){echo("selected");}?> value="Slovenian">Slovenian</option>
                                    <option <?php if($Languages == 'Spanish'){echo("selected");}?> value="Spanish">Spanish</option>
                                    <option <?php if($Languages == 'Swedish'){echo("selected");}?> value="Swedish">Swedish</option>
                                    <option <?php if($Languages == 'Tajikian'){echo("selected");}?> value="Tajikian">Tajikian</option>
                                    <option <?php if($Languages == 'Tamil'){echo("selected");}?> value="Tamil">Tamil</option>
                                    <option <?php if($Languages == 'Thai'){echo("selected");}?> value="Thai">Thai</option>
                                    <option <?php if($Languages == 'Turkish'){echo("selected");}?> value="Turkish">Turkish</option>
                                    <option <?php if($Languages == 'Turkmenian'){echo("selected");}?> value="Turkmenian">Turkmenian</option>
                                    <option <?php if($Languages == 'Ukrainian'){echo("selected");}?> value="Ukrainian">Ukrainian</option>
                                    <option <?php if($Languages == 'Urdu'){echo("selected");}?> value="Urdu">Urdu</option>
                                    <option <?php if($Languages == 'Usbekian'){echo("selected");}?> value="Usbekian">Usbekian</option>

                                </select>
                            </div>
                            <? 
                            }
                            ?>
                        
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label class="white">About</label>

                        <?php
                            if($_SESSION['about']=="disable")
                            {?>
                                <textarea rows="4" cols="40" maxlength="200" type="text" disabled name="About" style="resize: none;min-height:120px ;max-width:260px; color:white; background-color:#232323" value="<?php echo $About; ?>"><?php echo $About; ?></textarea>
                            <?
                            } 
                            else
                            {?> 
                                <textarea rows="4" cols="40" maxlength="200" type="text" name="About" style="resize: none;min-height:120px ;max-width:260px; color:white; background-color:#232323" value="<?php echo $About; ?>"><?php echo $About; ?></textarea>
                            <? 
                            }
                        ?>
                        
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                    <?php
                            if($_SESSION['button']=="view")
                            {?>
                                <input type="submit" name="Update" class="editbutton btn" value="Update">
                            <?
                            } 
                            ?>
                        
                    </div>
                </form>
    
                
            </div>
            
        </div>   

        <div class="col-sm-2 row " style="padding:5px ; margin-left:15px; min-width:310px; max-width:310px">
            <div class=" roundborder greybackground " style="padding:15px" >

                <?php
                $sql = "SELECT * FROM FriendRequests WHERE AccepterID = '$sessionUserID'";

                $result = mysqli_query($link, $sql);
                if (mysqli_num_rows($result) > 0)
                {
                ?>
                    <label class="white" for="file" >Friend Requests</label>

                <?php

                echo "<table class='styled-table' border='1' style='background-color:white ;padding:10px'>

                        <tr>
                        <th>Username</th>
                        <th>Profile</th>
                        <th>Accept</th>
                        <th>Decline</th>";
                    
                        
                            while($row = mysqli_fetch_assoc($result))
                            {

                                $tempRequesterID = $row['RequesterID'];
                                $tempRequestID = $row['RequestID'];

                                $sql2 = "SELECT * FROM Users WHERE UserID = '$tempRequesterID'";

                                $result2 = mysqli_query($link, $sql2);

                                if (mysqli_num_rows($result2) > 0) 
                                 {
                                    while($row2 = mysqli_fetch_assoc($result2))
                                    {

                                        echo "<tr>";
                                        
                                        echo "<td style='text-align: center'>" . $row2['username'] . "</td>";

                                        echo "<td style='text-align: center'>" . 
                                        "<form method='post'>
                                            <input id='UserID' name='UserID' type='hidden' value='$tempRequesterID'>
                                            <button type='submit' name='goToUserProfile' class='button tablebutton' value='button'><i class='large material-icons'>account_circle</i></button> 
                                        </form>". 
                                        "</td>";

                                            echo "<td >" . 
                                                "<form method='post' style='text-align: -webkit-center;'>
                                                    <input id='RequestID' name='RequestID' type='hidden' value='$tempRequestID'>
                                                    <input id='RequesterID' name='RequesterID' type='hidden' value='$tempRequesterID'>

                                                    <button type='submit' name='accept' class='button tablebutton' value='button'><i class='large material-icons' style='color:green '>check_circle</i></button> 
                                                </form>". 
                                                "</td>";
                                            echo "<td >" . 
                                                "<form method='post' style='text-align: -webkit-center;'>
                                                    <input id='RequestID' name='RequestID' type='hidden' value='$tempRequestID'>
                                                    <button type='submit' name='decline' class='button tablebutton' value='button'><i class='large material-icons' style='color:red '>remove_circle</i></button> 
                                                </form>". 
                                                "</td>";

                                    }
                                 }

                            }
                        

                
                }
                ?>
                </table>


                <label class="white" for="file" >Friends</label>
                <?php

                        $sql = "SELECT * FROM FriendsList WHERE UserID = '$sessionUserID'";
            
                        $result = mysqli_query($link, $sql);

                        


                        echo "<table class='styled-table' border='1' style='background-color:white ;padding:10px'>

                        <tr>
                        <th>Online</th>
                        <th>Username</th>
                        <th>Profile</th>";
                        if($_SESSION['chatOpen'] === false){
                            echo "<th>Chat</th>";
                        }
                        echo "</tr>";

                        if (mysqli_num_rows($result) > 0) 
                        {
                            while($row = mysqli_fetch_assoc($result))
                            {
                                
                                $temp = $row['FriendID'];

                                $sql2 = "SELECT * FROM Users WHERE UserID = '$temp'";

                                $result2 = mysqli_query($link, $sql2);

                                 if (mysqli_num_rows($result2) > 0) 
                                 {
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

                                        echo "<td style='text-align: center'>" . 
                                        "<form method='post'>
                                            <input id='UserID' name='UserID' type='hidden' value='$tempUserID'>
                                            <button type='submit' name='goToUserProfile' class='button tablebutton' value='button'><i class='large material-icons'>account_circle</i></button> 
                                        </form>". 
                                        "</td>";
                                        if($_SESSION['chatOpen'] === false){
                                            echo "<td >" . 
                                            "<form method='post'>
                                                <input id='UserID' name='UserID' type='hidden' value='$tempUserID'>
                                                <input id='Online' name='Online' type='hidden' value='$tempOnline'>
                                                <input id='username' name='username' type='hidden' value='$tempusername'>
                                                <button type='submit' name='openChat' class='button tablebutton' value='button'><i class='large material-icons'>chat</i></button> 
                                            </form>". 
                                            "</td>";
                                        }
                                        
                                        echo "</tr>";
                                    }
                                 }
                            }
                        }

                            echo "</table>";


                        ?>
            </div>
        </div>

        <div class="col-sm-2 row " style="padding:5px ; margin-left:15px ;min-width:310px; max-width:310px" >
            <div class=" roundborder greybackground " style="padding:15px">


                <label class="white" for="file" >New organisation</label>


                <form method='post'>
                    <input id='name' name='name' type='text' value='' style="border-radius:5px">
                    <button type='submit' name='createOrganisation' class='button tablebutton' value='button' style="color:white">Create</button> 
                    <span class="help-block"><?php echo $organisation_err; ?></span>
                </form> 





                <label class="white" for="file" style="margin-top:10px">Organisations</label>
                <?php


                                echo "<table border='1' style='background-color:white ;padding:10px'>
                                <tr>
                                <th>Name</th>
                                <th>Members</th>
                                <th>Profile</th>
                                </tr>";

                                $sql3 = "SELECT * FROM Members WHERE UserID = '$sessionUserID' ";

                                $result3 = mysqli_query($link, $sql3);

                                 if (mysqli_num_rows($result3) > 0) 
                                 {
                                    while($row3 = mysqli_fetch_assoc($result3))
                                    {

                                        $tempOrganisationID = $row3['OrganisationID'];

                                        $sql5 = "SELECT COUNT(OrganisationID) From `Members` WHERE OrganisationID = '$tempOrganisationID'";

                                        $result5 = mysqli_query($link, $sql5);

                                        if (mysqli_num_rows($result5) > 0) 
                                        {
                                            while($row5 = mysqli_fetch_assoc($result5))
                                            {
                                                $memberCount = $row5['COUNT(OrganisationID)'];
                                            }
                                        }


                                        $tempOrganisationID = $row3['OrganisationID'];

                                        $sql4 = "SELECT * FROM Organisations WHERE OrganisationID = '$tempOrganisationID' ";

                                        $result4 = mysqli_query($link, $sql4);

                                        if (mysqli_num_rows($result4) > 0) 
                                        {
                                            while($row4 = mysqli_fetch_assoc($result4))
                                            {

                                            echo "<tr>";
                                            
                                            echo "<td style='text-align: center'>" . $row4['Nickname'] . "</td>";

                                            echo "<td style='text-align: center;'>" . $memberCount .  "</td>";

                                            echo "<td >" . 
                                            "<form method='post'>
                                                <input id='OrganisationID' name='OrganisationID' type='hidden' value='$tempOrganisationID'>
                                                <button type='submit' name='goToOrganisationProfile' class='button tablebutton' value='button'><i class='large material-icons'>account_circle</i></button> 
                                            </form>". 
                                            "</td>";

                                            echo "</tr>";
                                                
                                            }
                                        }

                                        
                                    }
                                 }

                            echo "</table>";

                            mysqli_close($link);

                        ?>
            </div>
        </div>



        
</div> 

</body>
</html>