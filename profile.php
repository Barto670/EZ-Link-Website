<!DOCTYPE html>
<html style="background-color: #451093">
<head>
<title>Profile</title>
</head>
<body>
<?php 

include('navbar.php'); 
require_once "db_connection.php";

session_start();

$sessionUserID = $_SESSION['UserID'];
$visitUserID = $_SESSION['visitUserID'];
$visitLevelProgress = $_SESSION['visitLevelProgress'];
$visitUsername = $_SESSION['visitusername'];


$_SESSION['profilebutton']="edit";
$_SESSION['profilenickname']="disable";
$_SESSION['profileemail']="disable";
$_SESSION['profileage']="disable";
$_SESSION['profileregion']="disable";
$_SESSION['profileplatform']="disable";
$_SESSION['profilelanguages']="disable";
$_SESSION['profileabout']="disable";

$sql = "SELECT * FROM FriendsList WHERE UserID = '$sessionUserID' AND FriendID = '$visitUserID' ";
            
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) == 1) 
{                       
    $friendStatus = 2;     
}else{
    $sql2 = "SELECT * FROM FriendRequests WHERE RequesterID = '$sessionUserID' AND AccepterID = '$visitUserID' OR AccepterID = '$sessionUserID' AND RequesterID = '$visitUserID'";
            
    $result2 = mysqli_query($link, $sql2);

    if (mysqli_num_rows($result2) == 1) 
    {                       
        $friendStatus = 1;   
    }else{

        $friendStatus = 0;   
    }
}


if($_POST["Update"]){


                     $sql = "UPDATE Users SET Age = ? , Email = ? , Region = ? , Platform = ? , Languages = ? , About = ? WHERE UserID = ?";
                    if($stmt = mysqli_prepare($link, $sql) )
                    {
                        mysqli_stmt_bind_param($stmt, "sssssss", $_POST['Age'] , $_POST['Email'] , $_POST['Region'] , $_POST['Platform'] , $_POST['Languages'] , $_POST['About'], $visitUserID);
                        if(mysqli_stmt_execute($stmt) ){
                            
                            $_SESSION['visitEmail'] = $_POST['Email'];
                            $_SESSION['visitAge'] = $_POST['Age'];
                            $_SESSION['visitRegion'] = $_POST['Region'];
                            $_SESSION['visitPlatform'] = $_POST['Platform'];
                            $_SESSION['visitAbout'] = $_POST['About'];
                            $_SESSION['visitLanguages'] = $_POST['Languages'];
                            header("Refresh:0");
                        } else{
                            echo "Something went wrong. Please try again later.";
                            printf("Error: %s.\n", $stmt->error);
                        }

                        mysqli_stmt_close($stmt);

                        
                    }
    }

if(isset($_POST['editProfile'])) { 
        $_SESSION['profilebutton']="view";
        $_SESSION['profilenickname']="enabled";
        $_SESSION['profileemail']="enabled";
        $_SESSION['profileage']="enabled";
        $_SESSION['profileregion']="enabled";
        $_SESSION['profileplatform']="enabled";
        $_SESSION['profilelanguages']="enabled";
        $_SESSION['profileabout']="enabled";
    }

if(isset($_POST['viewProfile'])) { 
        $_SESSION['profilebutton']="edit";
        $_SESSION['profilenickname']="disable";
        $_SESSION['profileemail']="disable";
        $_SESSION['profileage']="disable";
        $_SESSION['profileregion']="disable";
        $_SESSION['profileplatform']="disable";
        $_SESSION['profilelanguages']="disable";
        $_SESSION['profileabout']="disable";
} 



if(array_key_exists('button', $_POST)) { 
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

if(array_key_exists('addFriend', $_POST)) { 

        $temp;
        $sql = "SELECT RequestID FROM FriendRequests ORDER BY RequestID DESC LIMIT 1";
      
        $result = mysqli_query($link, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $temp = (int)$row["RequestID"] + 1;
            }
        } else {
            $temp = 0;
        }

        echo $temp;

        $sql = "INSERT INTO FriendRequests (RequestID, AccepterID,RequesterID) values (?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql))
            {
                mysqli_stmt_bind_param($stmt, "sss", $temp, $visitUserID, $sessionUserID);

                if(mysqli_stmt_execute($stmt)){
                    header("Refresh:0");
                }

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

<h3 style="color:white; font-weight:bold ; text-align: -webkit-center;">Profile</h3>

<div class="col-sm-12">

    <div class="col-sm-12">

    <div class="col-sm-12">

                <div class="col-sm-4 row " style="padding:5px" >
            <div class=" roundborder greybackground " style="padding:20px">
                <button class="namebutton"><?php echo $_SESSION["visitusername"] ?></button>
                <img src="./assets/icons/male.png" width="40" height "40" ">
                <label class="white" for="file" >Level <?php echo $_SESSION["visitLevel"] ?></label>
                <progress id="file" value=<?php echo $visitLevelProgress?>  max="100" ></progress>
            </div>
        </div>

        <div class="col-sm-1 row " style="padding:5px ; margin-left:15px" >
            <div class="col-sm-1 row"></div>
                <div class="   col-sm-10 row" style="padding:5px ;margin-left: 5px;">
                    <div class="form-group">
                            <?php 
                            if($friendStatus == 2){
                            ?>
                                <input type="submit"  class="btn addfriendbutton" style="margin-top:5px" disabled value="Already Friends">
                            <?php 
                            }else if ($friendStatus == 1){
                            ?>
                                <input type="submit" class="btn addfriendbutton" style="margin-top:5px" disabled value="Request Pending">
                            <?php 
                            }else if ($friendStatus == 0){
                            ?>
                            <form method='post'>
                                <input type="submit" name='addFriend' class="btn addfriendbutton" style="margin-top:5px" value="Add Friend">
                            </form>
                            <?php 
                            }
                            ?>
                                 
                </div>
            </div>
        </div>

    </div>

    <div class="col-sm-12">

        <div class="col-sm-4 row" style="padding:5px;min-width:310px; max-width:310px">
            
            <div class="roundborder greybackground " style="padding:15px">

                <?php
                if($_SESSION['Admin']=="1")
                {?>
                <form method="post" style="margin-bottom:10px">
                            <?php
                            if($_SESSION['profilebutton']=="view")
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

                <? 
                }
                ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">   
                    <div class="form-group">
                        <label class="white">Email</label>
                        <?php
                            if($_SESSION['profileemail']=="disable")
                            {?>
                                <input type="text" name="Email" class="form-control" disabled value="<?php echo $_SESSION['visitEmail']; ?>">
                            <?
                            } 
                            else
                            {?> 
                                <input type="text" name="Email" class="form-control" value="<?php echo $_SESSION['visitEmail']; ?>">
                            <? 
                            }
                        ?>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label class="white">Age</label>
                        <?php
                            if($_SESSION['profileage']=="disable")
                            {?>
                                <input type="text" name="Age" class="form-control" disabled value="<?php echo $_SESSION['visitAge']; ?>">
                            <?
                            } 
                            else
                            {?> 
                                <input type="text" name="Age" class="form-control" value="<?php echo $_SESSION['visitAge']; ?>">
                            <? 
                            }
                        ?>
                        
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label class="white">Region</label>
                            <?php
                            if($_SESSION['profileregion']=="disable")
                            {?>
                                <input type="text" name="Region" class="form-control" disabled value="<?php echo $_SESSION['visitRegion']; ?>">
                            <?
                            } 
                            else
                            {
                            ?> 
                            <div class="col-12">
                                <select name="Region" id="Region" ">
                                    <option <?php if($_SESSION['visitRegion'] == ''){echo("selected");}?> disabled value> -- select an option -- </option>
                                    <option <?php if($_SESSION['visitRegion'] == 'Africa'){echo("selected");}?> value="Africa">Africa</option>
                                    <option <?php if($_SESSION['visitRegion'] == 'Asia'){echo("selected");}?> value="Asia">Asia</option>
                                    <option <?php if($_SESSION['visitRegion'] == 'The Caribbean'){echo("selected");}?> value="The Caribbean">The Caribbean</option>
                                    <option <?php if($_SESSION['visitRegion'] == 'Central America'){echo("selected");}?> value="Central America">Central America</option>
                                    <option <?php if($_SESSION['visitRegion'] == 'Europe'){echo("selected");}?> value="Europe">Europe</option>
                                    <option <?php if($_SESSION['visitRegion'] == 'North America'){echo("selected");}?> value="North America">North America</option>
                                    <option <?php if($_SESSION['visitRegion'] == 'Oceania'){echo("selected");}?> value="Oceania">Oceania</option>
                                    <option <?php if($_SESSION['visitRegion'] == 'South America'){echo("selected");}?> value="South America">South America</option>
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
                            if($_SESSION['profileplatform']=="disable")
                            {?>
                                <input type="text" name="Platform" class="form-control" disabled value="<?php echo $_SESSION['visitPlatform']; ?>">
                            <?
                            } 
                            else
                            {?> 
                            <div class="col-12">
                                <select name="Platform" id="Platform">
                                    <option <?php if($_SESSION['visitPlatform'] == ''){echo("selected");}?> disabled value> -- select an option -- </option>
                                    <option <?php if($_SESSION['visitPlatform'] == 'PC'){echo("selected");}?> value="PC">PC</option>
                                    <option <?php if($_SESSION['visitPlatform'] == 'XBOX'){echo("selected");}?> value="XBOX">XBOX</option>
                                    <option <?php if($_SESSION['visitPlatform'] == 'Playstation'){echo("selected");}?> value="Playstation">Playstation</option>
                                    <option <?php if($_SESSION['visitPlatform'] == 'Nintendo'){echo("selected");}?> value="Nintendo">Nintendo</option>
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
                            if($_SESSION['profilelanguages']=="disable")
                            {?>
                                <input type="text" name="Languages" class="form-control" disabled value="<?php echo $_SESSION['visitLanguages']; ?>">
                            <?
                            } 
                            else
                            {?> 
                            <div class="col-12">
                                <select name="Languages" id="Languages" >
                                    <option <?php if($_SESSION['visitLanguages'] == ''){echo("selected");}?> disabled value> -- select an option -- </option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Albanian'){echo("selected");}?> value="Albanian">Albanian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Armenian'){echo("selected");}?> value="Armenian">Armenian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Azeri'){echo("selected");}?> value="Azeri">Azeri</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Bosnian'){echo("selected");}?> value="Bosnian">Bosnian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Bulgarian'){echo("selected");}?> value="Bulgarian">Bulgarian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Byelorussian'){echo("selected");}?> value="Byelorussian">Byelorussian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Catalan'){echo("selected");}?> value="Catalan">Catalan</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Chinese'){echo("selected");}?> value="Chinese">Chinese</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Croatian'){echo("selected");}?> value="Croatian">Croatian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Czech'){echo("selected");}?> value="Czech">Czech</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Danish'){echo("selected");}?> value="Danish">Danish</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Dari'){echo("selected");}?> value="Dari">Dari</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Dutch'){echo("selected");}?> value="Dutch">Dutch</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'English'){echo("selected");}?> value="English">English</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Estonian'){echo("selected");}?> value="Estonian">Estonian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Finnish'){echo("selected");}?> value="Finnish">Finnish</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Flemish'){echo("selected");}?> value="Flemish">Flemish</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'French'){echo("selected");}?> value="French">French</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Georgian'){echo("selected");}?> value="Georgian">Georgian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'German'){echo("selected");}?> value="German">German</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Gilbertesian'){echo("selected");}?> value="Gilbertesian">Gilbertesian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Greek'){echo("selected");}?> value="Greek">Greek</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Hebrew'){echo("selected");}?> value="Hebrew">Hebrew</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Hindi'){echo("selected");}?> value="Hindi">Hindi</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Hungarian'){echo("selected");}?> value="Hungarian">Hungarian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Icelandic'){echo("selected");}?> value="Icelandic">Icelandic</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Irish'){echo("selected");}?> value="Irish">Irish</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Italian'){echo("selected");}?> value="Italian">Italian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Japanese'){echo("selected");}?> value="Japanese">Japanese</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Kazakh'){echo("selected");}?> value="Kazakh">Kazakh</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Kirgiz'){echo("selected");}?> value="Kirgiz">Kirgiz</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Korean'){echo("selected");}?> value="Korean">Korean</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Kurdish'){echo("selected");}?> value="Kurdish">Kurdish</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Lao'){echo("selected");}?> value="Lao">Lao</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Latin'){echo("selected");}?> value="Latin">Latin</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Latvian'){echo("selected");}?> value="Latvian">Latvian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Letzebuergesh'){echo("selected");}?> value="Letzebuergesh">Letzebuergesh</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Lithuanian'){echo("selected");}?> value="Lithuanian">Lithuanian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Macedonian'){echo("selected");}?> value="Macedonian">Macedonian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Maltese'){echo("selected");}?> value="Maltese">Maltese</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Moldawian'){echo("selected");}?> value="Moldawian">Moldawian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Mongolian'){echo("selected");}?> value="Mongolian">Mongolian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Norwegian'){echo("selected");}?> value="Norwegian">Norwegian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Pashtu'){echo("selected");}?> value="Pashtu">Pashtu</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Persian'){echo("selected");}?> value="Persian">Persian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Polish'){echo("selected");}?> value="Polish">Polish</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Portuguese'){echo("selected");}?> value="Portuguese">Portuguese</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Quechua'){echo("selected");}?> value="Quechua">Quechua</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Romanian'){echo("selected");}?> value="Romanian">Romanian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Russian'){echo("selected");}?> value="Russian">Russian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Serbian'){echo("selected");}?> value="Serbian">Serbian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Sindhi'){echo("selected");}?> value="Sindhi">Sindhi</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Singhalese'){echo("selected");}?> value="Singhalese">Singhalese</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Slovak'){echo("selected");}?> value="Slovak">Slovak</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Slovenian'){echo("selected");}?> value="Slovenian">Slovenian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Spanish'){echo("selected");}?> value="Spanish">Spanish</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Swedish'){echo("selected");}?> value="Swedish">Swedish</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Tajikian'){echo("selected");}?> value="Tajikian">Tajikian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Tamil'){echo("selected");}?> value="Tamil">Tamil</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Thai'){echo("selected");}?> value="Thai">Thai</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Turkish'){echo("selected");}?> value="Turkish">Turkish</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Turkmenian'){echo("selected");}?> value="Turkmenian">Turkmenian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Ukrainian'){echo("selected");}?> value="Ukrainian">Ukrainian</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Urdu'){echo("selected");}?> value="Urdu">Urdu</option>
                                    <option <?php if($_SESSION['visitLanguages'] == 'Usbekian'){echo("selected");}?> value="Usbekian">Usbekian</option>

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
                            if($_SESSION['profileabout']=="disable")
                            {?>
                                <textarea rows="4" cols="40" maxlength="200" type="text" disabled name="About" style="resize: none;min-height:120px ;max-width:260px; color:white; background-color:#232323" value="<?php echo $About; ?>"><?php echo $_SESSION['visitAbout']; ?></textarea>
                            <?
                            } 
                            else
                            {?> 
                                <textarea rows="4" cols="40" maxlength="200" type="text" name="About" style="resize: none;min-height:120px ;max-width:260px; color:white; background-color:#232323" value="<?php echo $About; ?>"><?php echo $_SESSION['visitAbout']; ?></textarea>
                            <? 
                            }
                        ?>
                        
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                    <?php
                            if($_SESSION['profilebutton']=="view")
                            {?>
                                <input type="submit" name="Update" class="editbutton btn" value="Update">
                            <?
                            } 
                            ?>
                        
                    </div>
                </form>
    
                
            </div>
            
        </div>   

        <div class="col-sm-2 row " style="padding:5px ; margin-left:15px; min-width:250px; max-width:250px" >
            <div class=" roundborder greybackground " style="padding:15px">
                <label class="white" for="file" >Friends</label>
                <?php

                        $sql = "SELECT * FROM FriendsList WHERE UserID = '$visitUserID'";
            
                        $result = mysqli_query($link, $sql);

                        echo "<table border='1' style='background-color:white ;padding:10px'>

                        <tr>
                        <th>Online</th>
                        <th>Username</th>
                        <th>Profile</th>
                        </tr>";

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

                                        $temp2 = $row2['UserID'];

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
                                        
                                        echo "<td>" . $row2['username'] . "</td>";

                                        echo "<td >" . 
                                        "<form method='post'>
                                            <input id='UserID' name='UserID' type='hidden' value='$temp2'>
                                            <button type='submit' name='button' class='button tablebutton' value='button'><i class='large material-icons'>account_circle</i></button> 
                                        </form>". 
                                        "</td>";

                                        echo "</tr>";
                                    }
                                 }
                            }
                        }

                            echo "</table>";

                        ?>
            </div>
        </div>

                <div class="col-sm-2 row " style="padding:5px ; margin-left:15px; min-width:250px; max-width:250px" >
            <div class=" roundborder greybackground " style="padding:15px">
                <label class="white" for="file" >Organisations</label>
                <?php


                                echo "<table border='1' style='background-color:white ;padding:10px'>
                                <tr>
                                <th>Name</th>
                                <th>Members</th>
                                <th>Profile</th>
                                </tr>";

                                $sql3 = "SELECT * FROM Members WHERE UserID = '$visitUserID' ";

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