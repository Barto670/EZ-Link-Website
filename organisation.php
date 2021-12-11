<!DOCTYPE html>
<html style="background-color: #451093">
<head>
<title>Profile</title>
</head>
<body>
<?php 

include('navbar.php'); 
require "db_connection.php";

session_start();

$UserID = $_SESSION['UserID'];
$sessionUsername = $_SESSION['username'];
$sessionPassword = $_SESSION['password'];

$organisationID = $_SESSION['visitOrganisationID'];
$ownerIdOrganisation = $_SESSION['visitOrganisationOwnerID'];
$nicknameOrganisation = $_SESSION['visitOrganisationNickname'];
$aboutOrganisation =$_SESSION['visitOrganisationAbout'];
$regionOrganisation =$_SESSION['visitOrganisationRegion'];
$platformOrganisation =$_SESSION['visitOrganisationPlatform'];

$_SESSION['buttonOrganisation']="edit";
$_SESSION['buttonRegionOrganisation']="disable";
$_SESSION['buttonPlatformOrganisation']="disable";
$_SESSION['buttonAboutOrganisation']="disable";




$sql = "SELECT * FROM Members WHERE UserID = '$sessionUserID' AND OrganisationID = '$organisationID' ";
            
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) == 1) 
{                       
    $memberStatus = 2;     
}else{
    $sql2 = "SELECT * FROM MembershipRequests WHERE RequesterID = '$sessionUserID' AND OrganisationID = '$organisationID'";
            
    $result2 = mysqli_query($link, $sql2);

    if (mysqli_num_rows($result2) == 1) 
    {                       
        $memberStatus = 1;   
    }else{

        $memberStatus = 0;   
    }
}

if(array_key_exists('requestOrganisation', $_POST)) { 

        $temp;
        $sql = "SELECT RequestID FROM MembershipRequests ORDER BY RequestID DESC LIMIT 1";
      
        $result = mysqli_query($link, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $temp = (int)$row["RequestID"] + 1;
            }
        } else {
            $temp = 0;
        }

        echo $temp;

        $sql = "INSERT INTO MembershipRequests (RequestID,RequesterID,OrganisationID) values (?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql))
            {
                mysqli_stmt_bind_param($stmt, "sss", $temp, $sessionUserID, $organisationID);

                if(mysqli_stmt_execute($stmt)){
                    header("Refresh:0");
                }

            }
}

if($_POST["orderBy"]) 
        { 
            $temp2 = $_POST["orderBy"];
            $_SESSION['vacancysearchby2']= $temp2;

            header("Refresh:0");
            
        }  



if($_POST["Update"])
{
    $sql = "UPDATE Organisations SET Region = ?, Platform = ? , About = ? WHERE OrganisationID = ?";
    if($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "ssss", $_POST['Region'] , $_POST['Platform'] , $_POST['About'] , $organisationID);
        if(mysqli_stmt_execute($stmt) ){
            $_SESSION['visitOrganisationAbout'] = $_POST['About'];
            $_SESSION['visitOrganisationRegion'] = $_POST['Region'];
            $_SESSION['visitOrganisationPlatform'] = $_POST['Platform'];
            header("Refresh:0");
            }else{
                echo "Something went wrong. Please try again later.";
                printf("Error: %s.\n", $stmt->error);
            }
    }else{
         echo "error";
    }
}


if($_POST["decline"]) { 
            $tempRequestID = (int)$_POST["RequestID"];

            $sql = "DELETE FROM MembershipRequests WHERE RequestID = $tempRequestID;";
            
            mysqli_query($link, $sql);
            
        }  

if($_POST["accept"]) { 
            $tempRequestID = (int)$_POST["RequestID"];
            $tempRequesterID = (int)$_POST["RequesterID"];

            $sql = "DELETE FROM MembershipRequests WHERE RequestID = $tempRequestID;";
            
            mysqli_query($link, $sql);

            $sql = "SELECT * FROM Members WHERE OrganisationID = $organisationID AND UserID = $tempRequesterID";
      
            $result = mysqli_query($link, $sql);
            
            if (mysqli_num_rows($result) == 0) {

                $sql = "INSERT INTO Members (OrganisationID, UserID) values (?, ?)";
            if($stmt = mysqli_prepare($link, $sql))
            {
                mysqli_stmt_bind_param($stmt, "ss", $organisationID, $tempRequesterID);

                mysqli_stmt_execute($stmt);
                header("Refresh:0");

            mysqli_stmt_close($stmt);
            }

            }

            
            
        }  

if(isset($_POST['editProfile'])) { 
        $_SESSION['buttonOrganisation']="view";
        $_SESSION['buttonRegionOrganisation']="enable";
        $_SESSION['buttonPlatformOrganisation']="enable";
        $_SESSION['buttonAboutOrganisation']="enable";
    }

if(isset($_POST['viewProfile'])) { 
        $_SESSION['buttonOrganisation']="edit";
        $_SESSION['buttonRegionOrganisation']="disable";
        $_SESSION['buttonPlatformOrganisation']="disable";
        $_SESSION['buttonAboutOrganisation']="disable";
} 

if($_POST["goToUserProfile"]) { 
            if($_POST["UserID"] != $_SESSION['UserID']){

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
            }else{
                header("location: myprofile.php");
            }
        }    

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Organisation Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body class="background display:block">
<link rel="stylesheet" href="styles.css">

<div class="col-sm-12">

    <div class="col-sm-12">

        <div class="col-sm-4 row " style="padding:5px" >
            <div class=" roundborder greybackground " style="padding:20px">
            <div class="form-group">
                <button class="namebutton"><?php echo $nicknameOrganisation ?></button>
                <img src="./assets/icons/organisation.png" width="40" height "40" ">
                
                
                            
                
                </div>
            </div>
        </div>

        <div class="col-sm-2 row " style="padding:5px ; margin-left:15px " >
            <div class=" roundborder greybackground " style="padding:20px">

                <?php

                                        $sql5 = "SELECT username From `Users` WHERE UserID = '$ownerIdOrganisation'";

                                        $result5 = mysqli_query($link, $sql5);

                                        if (mysqli_num_rows($result5) > 0) 
                                        {
                                            while($row5 = mysqli_fetch_assoc($result5))
                                            {
                                                $ownerUsername = $row5['username'];
                                            }
                                        }

                ?>


                
                <form method="post">
                    <label class="white" for="file" >Owner:</label>
                    <input id='UserID' name='UserID' type='hidden' value="<?php echo $ownerIdOrganisation; ?>" >
                    <button type='submit' class="namebutton" name='goToUserProfile' value='button'><?php echo $ownerUsername ?></button>
                    
                
                </form> 

            </div>

            
        </div>

        <div class="col-sm-2 row " style="padding:5px ; margin-left:15px; margin-top:10px " >

        <?php 
                            if($memberStatus == 2){
                            ?>
                                <input type="submit" class="btn addfriendbutton" disabled style="margin-top:5px" value="Already a member">
                            <?php 
                            }else if ($memberStatus == 1){
                            ?>
                                <input type="submit" class="btn addfriendbutton" disabled style="margin-top:5px" value="Request Pending">
                            <?php 
                            }else if ($memberStatus == 0){
                            ?>
                            <form method='post' style='max-width:100px'>
                                <input type="submit" name='requestOrganisation' class="btn addfriendbutton" style="margin-top:5px" value="Request to join">
                            </form>
                            <?php 
                            }
                            ?>

        </div>
        

        
        

    </div>

    <div class="col-sm-12">

        <div class="col-sm-4 row" style="padding:5px;min-width:310px; max-width:310px">
            
            <div class="roundborder greybackground " style="padding:15px">

                <form method="post" style="margin-bottom:10px">
                            <?php
                            if($_SESSION['buttonOrganisation']=="view" && ($ownerIdOrganisation == $UserID || $_SESSION["Admin"] == 1))
                            {?>
                                <input type="submit" name="viewProfile" value="Click to View"/> 
                            <?
                            } 
                            else if($_SESSION['buttonOrganisation']=="edit" && ($ownerIdOrganisation == $UserID || $_SESSION["Admin"] == 1))
                            {?> 
                                <input type="submit" name="editProfile" value="Click to Edit"/> 
                            <? 
                            }
                        ?>
                    
                </form> 

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">  
                    <div class="form-group">
                        <label class="white">Region</label>
                            <?php
                            if($_SESSION['buttonRegionOrganisation']=="disable")
                            {?>
                                <input type="text" name="Region" class="form-control" disabled value="<?php echo $regionOrganisation; ?>">
                            <?
                            } 
                            else
                            {
                            ?> 
                            <div class="col-12">
                                <select name="Region" id="Region" ">
                                    <option <?php if($regionOrganisation == ''){echo("selected");}?> disabled value> -- select an option -- </option>
                                    <option <?php if($regionOrganisation == 'Africa'){echo("selected");}?> value="Africa">Africa</option>
                                    <option <?php if($regionOrganisation == 'Asia'){echo("selected");}?> value="Asia">Asia</option>
                                    <option <?php if($regionOrganisation == 'The Caribbean'){echo("selected");}?> value="The Caribbean">The Caribbean</option>
                                    <option <?php if($regionOrganisation == 'Central America'){echo("selected");}?> value="Central America">Central America</option>
                                    <option <?php if($regionOrganisation == 'Europe'){echo("selected");}?> value="Europe">Europe</option>
                                    <option <?php if($regionOrganisation == 'North America'){echo("selected");}?> value="North America">North America</option>
                                    <option <?php if($regionOrganisation == 'Oceania'){echo("selected");}?> value="Oceania">Oceania</option>
                                    <option <?php if($regionOrganisation == 'South America'){echo("selected");}?> value="South America">South America</option>
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
                            if($_SESSION['buttonPlatformOrganisation']=="disable")
                            {?>
                                <input type="text" name="Platform" class="form-control" disabled value="<?php echo $platformOrganisation; ?>">
                            <?
                            } 
                            else
                            {?> 
                            <div class="col-12">
                                <select name="Platform" id="Platform">
                                    <option <?php if($platformOrganisation == ''){echo("selected");}?> disabled value> -- select an option -- </option>
                                    <option <?php if($platformOrganisation == 'PC'){echo("selected");}?> value="PC">PC</option>
                                    <option <?php if($platformOrganisation == 'XBOX'){echo("selected");}?> value="XBOX">XBOX</option>
                                    <option <?php if($platformOrganisation == 'Playstation'){echo("selected");}?> value="Playstation">Playstation</option>
                                    <option <?php if($platformOrganisation == 'Nintendo'){echo("selected");}?> value="Nintendo">Nintendo</option>
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
                            if($_SESSION['buttonAboutOrganisation']=="disable")
                            {?>
                                <input type="text" name="About" class="form-control" disabled value="<?php echo $aboutOrganisation; ?>">
                            <?
                            } 
                            else
                            {?> 
                                <input type="text" name="About" class="form-control" value="<?php echo $aboutOrganisation; ?>">
                            <? 
                            }
                        ?>
                        
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                    <?php
                            if($_SESSION['buttonOrganisation']=="view")
                            {?>
                                <input type="submit" name="Update" class="btn btn-primary" value="Update">
                            <?
                            } 
                            ?>
                        
                    </div>
                </form>
    
                
            </div>
            
        </div>   

        <div class="col-sm-2 row " style="padding:5px ; margin-left:15px ; min-width:310px; max-width:310px " >
            <div class=" roundborder greybackground " style="padding:15px">



            <?php
                $sql = "SELECT * FROM MembershipRequests WHERE OrganisationID = '$organisationID'";

                $result = mysqli_query($link, $sql);

                if ((mysqli_num_rows($result) > 0) & ($ownerIdOrganisation == $UserID))
                {
                ?>
                    <label class="white" for="file" >Membership Requests</label>

                <?php
                
                echo "<table border='1' style='background-color:white ;padding:10px'>

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
                                            <button type='submit' name='goToUserProfile' class='button' value='button'><i class='large material-icons'>account_circle</i></button> 
                                        </form>". 
                                        "</td>";

                                            echo "<td >" . 
                                                "<form method='post' style='text-align: -webkit-center;'>
                                                    <input id='RequestID' name='RequestID' type='hidden' value='$tempRequestID'>
                                                    <input id='RequesterID' name='RequesterID' type='hidden' value='$tempRequesterID'>

                                                    <button type='submit' name='accept' class='button' value='button'><i class='large material-icons' style='color:green '>check_circle</i></button> 
                                                </form>". 
                                                "</td>";
                                            echo "<td >" . 
                                                "<form method='post' style='text-align: -webkit-center;'>
                                                    <input id='RequestID' name='RequestID' type='hidden' value='$tempRequestID'>
                                                    <button type='submit' name='decline' class='button' value='button'><i class='large material-icons' style='color:red '>remove_circle</i></button> 
                                                </form>". 
                                                "</td>";

                                    }
                                 }

                            }
                        

                
                }
                ?>
                </table>

                <?php
                $sql5 = "SELECT COUNT(OrganisationID) From `Members` WHERE OrganisationID = '$organisationID'";

                                        $result5 = mysqli_query($link, $sql5);

                                        if (mysqli_num_rows($result5) > 0) 
                                        {
                                            while($row5 = mysqli_fetch_assoc($result5))
                                            {
                                                $memberCount = $row5['COUNT(OrganisationID)'];
                                            }
                                        }
                ?>

                <label class="white" for="file" ><?php echo "Members (Total: " . $memberCount  . ")" ?></label>
                <?php

                        $sql = "SELECT * FROM Members WHERE OrganisationID = '$organisationID'";
            
                        $result = mysqli_query($link, $sql);

                        echo "<table border='1' style='background-color:white ;padding:10px'>

                        <tr>
                        <th>Online</th>
                        <th>Username</th>
                        <th>Profile</th>";
                        echo "</tr>";

                        if (mysqli_num_rows($result) > 0) 
                        {
                            while($row = mysqli_fetch_assoc($result))
                            {
                                
                                $temp = $row['UserID'];

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

                                        
                                        echo "</tr>";
                                    }
                                 }
                            }
                        } else{
                            printf("Error: %s.\n", $row->$result);
                        }

                            echo "</table>";


                        ?>
            </div>







            
        </div>






<div class="card col-sm-6 roundborder greybackground " style="padding-bottom:10px ; margin-left:20px; margin-top:5px">




<h3 style="color:white; font-weight:bold ; text-align: -webkit-center;">Vacancy Listings</h3>


                
                <form method="post">
                    <div style="margin-top:5px">
                        <label class="white">Order By:</label>
                        <select name="orderBy" class="selectvacancy" type="submit" id="orderBy" onchange="this.form.submit()">
                                <option <?php if($_SESSION['vacancysearchby2'] == ''){echo("selected");}?> disabled value> -- select an option -- </option>
                                <option <?php if($_SESSION['vacancysearchby2'] == 'PC'){echo("selected");}?> value="PC">PC</option>
                                <option <?php if($_SESSION['vacancysearchby2'] == 'XBOX'){echo("selected");}?> value="XBOX">XBOX</option>
                                <option <?php if($_SESSION['vacancysearchby2'] == 'Playstation'){echo("selected");}?> value="Playstation">Playstation</option>
                                <option <?php if($_SESSION['vacancysearchby2'] == 'Nintendo'){echo("selected");}?> value="Nintendo">Nintendo</option>
                        </select>
                    </div>  
                </form>
                <?php
                $searchvacancy2 = $_SESSION['vacancysearchby2'];
                if($searchvacancy2 ==""){
                    $sql = "SELECT * FROM VacancyListings WHERE OrganisationID = $organisationID ORDER BY VacancyID DESC;";
                }else{
                    $sql = "SELECT * FROM VacancyListings WHERE OrganisationID = $organisationID ORDER BY FIELD(Platform, '$searchvacancy2') DESC;";
                }

                $result = mysqli_query($link, $sql);
                if (mysqli_num_rows($result) > 0)
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                        $tempVacancyID = $row['VacancyID'];
                        $tempOrganisationID = $row['OrganisationID'];
                        $tempPlatform = $row['Platform'];
                        $tempDescription = $row['Description'];
                        $tempDate = $row['Date'];

                        $sql2 = "SELECT * FROM Organisations WHERE OrganisationID = $tempOrganisationID"; //Get Organisation data
                        $result2 = mysqli_query($link, $sql2);
                        $row2 = mysqli_fetch_assoc($result2);

                        $tempOwnerID = $row2['OwnerID'];
                        $tempNickname = $row2['Nickname'];
                        $tempRegion = $row2['Region'];

                        $sql3 = "SELECT * FROM Users WHERE UserID = $tempOwnerID"; //Get Owner data
                        $result3 = mysqli_query($link, $sql3);
                        $row3 = mysqli_fetch_assoc($result3);

                        $tempOwnerUsername = $row3['username'];



                            ?>
                            
                            <div class="card-body" style="margin-top:10px ; padding:10px; background-color:#451093; border-radius:10px;">
                                <div class="item-jobpost">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <h4 style="color:white; font-weight:bold"><i class="material-icons" style="font-size: 18px;margin-left:2px">gamepad</i><?php echo $tempDescription ?></h4>
                                            <ul class="list-inline">
                                                <li style="color:white"><i class="material-icons" style="font-size: 15px;margin-left:2px">account_circle</i><?php echo $tempOwnerUsername ?></li>
                                            </ul>
                                        </div>
                                        
                                        <div class="col-md-4 jobpost-location">

                                            <span style="color:white"><i class="material-icons" style="font-size: 15px;margin-left:2px">account_circle</i><?php echo $tempNickname ?> - <i class="material-icons" style="font-size: 20px">location_on</i><?php echo $tempRegion ?></span>
                                        </div>


                                        <div class="col-md-4 jobpost-location" style="margin-top:5px">
                                            <span style="color:white">Platform Required<i class="material-icons" style="font-size: 15px;margin-left:2px">gamepad</i>: <?php echo $tempPlatform ?></span>
                                        </div>

                                        
                                        
                                        
                                    </div>
                                </div>
                            </div>
                            <?php
        
                    }
                }else{
                    echo "<h4 style='color:white'>-No vacancies are currently listed-</h4>";
                }
                    ?>


</div>




        
</div> 


</body>
</html>