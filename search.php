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



if(isset($_POST['search1'])){ //check if form was submitted
  $search1 = $_POST["search1"];
}   

if(isset($_POST['search2'])){ //check if form was submitted
  $search2 = $_POST["search2"];
  $search1 = $_POST["search1"];
}   

if(isset($_POST['search3'])){ //check if form was submitted
  $search3 = $_POST["search3"];
  $search1 = $_POST["search1"];
}   


if(isset($_POST['users'])){ //check if form was submitted
  $type = "users";
  $searchby = $_POST["searchby"];
  $value = $_POST["value"];

    $_SESSION['type'] = "users";
    $_SESSION['searchby'] = $_POST["searchby"];
    $_SESSION['value'] = $_POST["value"];

    
}   

if(isset($_POST['organisations'])){ //check if form was submitted
  $type = "organisations";
  $searchby = $_POST["searchby"];
  $value = $_POST["value"];

    $_SESSION['type'] = "organisations";
    $_SESSION['searchby'] = $_POST["searchby"];
    $_SESSION['value'] = $_POST["value"];
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

    <div class="col-sm-12">

        <div class="col-sm-2 row" style="padding:5px">
            
            <div class="roundborder greybackground " style="padding:15px; min-height:21vh ; max-height:21vh">
            

                        <form action="<?php echo htmlspecialchars($_SERVER["search1"]); ?>" method="post" style="text-align-last: center;">
                            <div style="text-align-last: center;">
                                <label class="white" for="file" style="font-size:22px; color:#FFFFFF;">Search Options</label>
                            </div>
                            <label style="margin-top:10px;color:grey" >User/Organisation</label>
                            <div class="col-12">
                                <select name="search1" id="search1" onchange="this.form.submit()">
                                    <option disabled selected value> -- select an option -- </option>
                                    <option value="user">User</option>
                                    <option value="organisation">Organisation</option>
                                </select>
                            </div>
                        </form>

                        <script>
                            document.getElementById("search1").value = "<?php echo $search1; ?>";
                        </script>

                        <?php
                        if($search1 == "user")
                        {
                        ?>
                            <form action="<?php echo htmlspecialchars($_SERVER["search2"]); ?>" method="post" style="text-align-last: center; margin-top:20px">
                                <label style="color:grey">Search by:</label>
                                <div class="col-12">
                                    <select name="search2" id="search2" onchange="this.form.submit()">
                                        <option disabled selected value> -- select an option -- </option>
                                        <option value="age">Age</option>
                                        <option value="region">Region</option>
                                        <option value="platform">Platform</option>
                                        <option value="language">Language</option>
                                        <option value="level">Level</option>
                                        <input id='search1' name='search1' type='hidden' value=<?php echo $search1; ?>>
                                    </select>
                                </div>
                            </form>

                        
                        <script>
                            document.getElementById("search2").value = "<?php echo $search2; ?>";
                        </script>

                        <?php
                        }
                        else if($search1 == "organisation")
                        {
                        ?>

                            <form action="<?php echo htmlspecialchars($_SERVER["search3"]); ?>" method="post" style="text-align-last: center; margin-top:20px">
                                <label style="color:grey">Search by:</label>
                                <div class="col-12">
                                    <select name="search3" id="search3" onchange="this.form.submit()">
                                        <option disabled selected value> -- select an option -- </option>
                                        <option value="region">Region</option>
                                        <option value="platform">Platform</option>
                                        <input id='search1' name='search1' type='hidden' value=<?php echo $search1; ?>>
                                    </select>
                                </div>
                            </form>
                        <?php
                        }
                        ?>
                        <script>
                            document.getElementById("search3").value = "<?php echo $search3; ?>";
                        </script>
    
                
            </div>
            
        </div>   

        <?php
        if($search2 != "" || $search3 != "" )
        {
        ?>
            <div class="col-sm-2 row " style="padding:5px ; margin-left:15px" >
                <div class=" roundborder greybackground " style="padding:15px">
                    <?php
                    if($search2 == "age"){
                    ?>
                        <div style="text-align-last: center;">
                            <label class="white" for="file" style="font-size:22px; color:#FFFFFF;">Search Users</label>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["users"]); ?>" method="post" style="margin-top:10px">
                            <div class="form-group">
                                <label style="color:grey">Enter Age:</label>
                                <input type="text" name="value" class="form-control">
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                                        <input type="submit" id="users" name="users" class="btn btn-primary" value="Search">
                                        <input id='type' name='type' type='hidden' value=<?php echo $search1; ?>>
                                        <input id='searchby' name='searchby' type='hidden' value=<?php echo $search2; ?>>
                            </div>
                        </form>
                    <?php
                    }
                    ?>

                    <?php
                    if($search2 == "region"){
                    ?>
                        <div style="text-align-last: center;">
                            <label class="white" for="file" style="font-size:22px; color:#FFFFFF;">Search Users</label>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["users"]); ?>" method="post" style="margin-top:10px">
                            <div class="form-group">
                                <label style="color:grey">Enter Region:</label>
                                <select name="value" id="Region" ">
                                    <option disabled value> -- select an option -- </option>
                                    <option value="Africa">Africa</option>
                                    <option value="Asia">Asia</option>
                                    <option value="The Caribbean">The Caribbean</option>
                                    <option value="Central America">Central America</option>
                                    <option value="Europe">Europe</option>
                                    <option value="North America">North America</option>
                                    <option value="Oceania">Oceania</option>
                                    <option value="South America">South America</option>
                                </select>
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                                        <input type="submit" id="users" name="users" class="btn btn-primary" value="Search">
                                        <input id='type' name='type' type='hidden' value=<?php echo $search1; ?>>
                                        <input id='searchby' name='searchby' type='hidden' value=<?php echo $search2; ?>>
                            </div>
                        </form>
                    <?php
                    }
                    ?>

                    <?php
                    if($search2 == "platform"){
                    ?>
                        <div style="text-align-last: center;">
                            <label class="white" for="file" style="font-size:22px; color:#FFFFFF;">Search Users</label>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["users"]); ?>" method="post" style="margin-top:10px">
                            <div class="form-group">
                                <label style="color:grey">Enter Platform:</label>
                                <select name="value" id="Platform">
                                            <option disabled value> -- select an option -- </option>
                                            <option value="PC">PC</option>
                                            <option value="XBOX">XBOX</option>
                                            <option value="Playstation">Playstation</option>
                                            <option value="Nintendo">Nintendo</option>
                                        </select>
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                                        <input type="submit" id="users" name="users" class="btn btn-primary" value="Search">
                                        <input id='type' name='type' type='hidden' value=<?php echo $search1; ?>>
                                        <input id='searchby' name='searchby' type='hidden' value=<?php echo $search2; ?>>
                            </div>
                        </form>
                    <?php
                    }
                    ?>

                    <?php
                    if($search2 == "language"){
                    ?>
                        <div style="text-align-last: center;">
                            <label class="white" for="file" style="font-size:22px; color:#FFFFFF;">Search Users</label>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["users"]); ?>" method="post" style="margin-top:10px">
                            <div class="form-group">
                                <label style="color:grey">Enter Language:</label>
                                <select name="value" id="value" >
                                    <option disabled value> -- select an option -- </option>
                                    <option value="Albanian">Albanian</option>
                                    <option value="Armenian">Armenian</option>
                                    <option value="Azeri">Azeri</option>
                                    <option value="Bosnian">Bosnian</option>
                                    <option value="Bulgarian">Bulgarian</option>
                                    <option value="Byelorussian">Byelorussian</option>
                                    <option value="Catalan">Catalan</option>
                                    <option value="Chinese">Chinese</option>
                                    <option value="Croatian">Croatian</option>
                                    <option value="Czech">Czech</option>
                                    <option value="Danish">Danish</option>
                                    <option value="Dari">Dari</option>
                                    <option value="Dutch">Dutch</option>
                                    <option value="English">English</option>
                                    <option value="Estonian">Estonian</option>
                                    <option value="Finnish">Finnish</option>
                                    <option value="Flemish">Flemish</option>
                                    <option value="French">French</option>
                                    <option value="Georgian">Georgian</option>
                                    <option value="German">German</option>
                                    <option value="Gilbertesian">Gilbertesian</option>
                                    <option value="Greek">Greek</option>
                                    <option value="Hebrew">Hebrew</option>
                                    <option value="Hindi">Hindi</option>
                                    <option value="Hungarian">Hungarian</option>
                                    <option value="Icelandic">Icelandic</option>
                                    <option value="Irish">Irish</option>
                                    <option value="Italian">Italian</option>
                                    <option value="Japanese">Japanese</option>
                                    <option value="Kazakh">Kazakh</option>
                                    <option value="Kirgiz">Kirgiz</option>
                                    <option value="Korean">Korean</option>
                                    <option value="Kurdish">Kurdish</option>
                                    <option value="Lao">Lao</option>
                                    <option value="Latin">Latin</option>
                                    <option value="Latvian">Latvian</option>
                                    <option value="Letzebuergesh">Letzebuergesh</option>
                                    <option value="Lithuanian">Lithuanian</option>
                                    <option value="Macedonian">Macedonian</option>
                                    <option value="Maltese">Maltese</option>
                                    <option value="Moldawian">Moldawian</option>
                                    <option value="Mongolian">Mongolian</option>
                                    <option value="Norwegian">Norwegian</option>
                                    <option value="Pashtu">Pashtu</option>
                                    <option value="Persian">Persian</option>
                                    <option value="Polish">Polish</option>
                                    <option value="Portuguese">Portuguese</option>
                                    <option value="Quechua">Quechua</option>
                                    <option value="Romanian">Romanian</option>
                                    <option value="Russian">Russian</option>
                                    <option value="Serbian">Serbian</option>
                                    <option value="Sindhi">Sindhi</option>
                                    <option value="Singhalese">Singhalese</option>
                                    <option value="Slovak">Slovak</option>
                                    <option value="Slovenian">Slovenian</option>
                                    <option value="Spanish">Spanish</option>
                                    <option value="Swedish">Swedish</option>
                                    <option value="Tajikian">Tajikian</option>
                                    <option value="Tamil">Tamil</option>
                                    <option value="Thai">Thai</option>
                                    <option value="Turkish">Turkish</option>
                                    <option value="Turkmenian">Turkmenian</option>
                                    <option value="Ukrainian">Ukrainian</option>
                                    <option value="Urdu">Urdu</option>
                                    <option value="Usbekian">Usbekian</option>

                                </select>
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                                        <input type="submit" id="users" name="users"class="btn btn-primary" value="Search">
                                        <input id='type' name='type' type='hidden' value=<?php echo $search1; ?>>
                                        <input id='searchby' name='searchby' type='hidden' value=<?php echo $search2; ?>>
                            </div>
                        </form>
                    <?php
                    }
                    ?>

                    <?php
                    if($search2 == "level"){
                    ?>
                        <div style="text-align-last: center;">
                            <label class="white" for="file" style="font-size:22px; color:#FFFFFF;">Search Users</label>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["users"]); ?>" method="post" style="margin-top:10px">
                            <div class="form-group">
                                <label style="color:grey">Enter Level:</label>
                                <input type="text" name="value" class="form-control">
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                                        <input type="submit" id="users" name="users"class="btn btn-primary" value="Search">
                                        <input id='type' name='type' type='hidden' value=<?php echo $search1; ?>>
                                        <input id='searchby' name='searchby' type='hidden' value=<?php echo $search2; ?>>
                            </div>
                        </form>
                    <?php
                    }
                    ?>

                    <?php
                    if($search3 == "region"){
                    ?>
                        <div style="text-align-last: center;">
                            <label class="white" for="file" style="font-size:22px; color:#FFFFFF;">Search Organisations</label>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["organisations"]); ?>" method="post" style="margin-top:10px">
                            <div class="form-group">
                                <label style="color:grey">Enter Region:</label>
                                <select name="value" id="Region" ">
                                    <option disabled value> -- select an option -- </option>
                                    <option value="Africa">Africa</option>
                                    <option value="Asia">Asia</option>
                                    <option value="The Caribbean">The Caribbean</option>
                                    <option value="Central America">Central America</option>
                                    <option value="Europe">Europe</option>
                                    <option value="North America">North America</option>
                                    <option value="Oceania">Oceania</option>
                                    <option value="South America">South America</option>
                                </select>
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                                        <input type="submit" id="organisations" name="organisations" class="btn btn-primary" value="Search">
                                        <input id='type' name='type' type='hidden' value=<?php echo $search1; ?>>
                                        <input id='searchby' name='searchby' type='hidden' value=<?php echo $search3; ?>>
                            </div>
                        </form>
                    <?php
                    }
                    ?>

                    <?php
                    if($search3 == "platform"){
                    ?>
                        <div style="text-align-last: center;">
                            <label class="white" for="file" style="font-size:22px; color:#FFFFFF;">Search Organisations</label>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["organisations"]); ?>" method="post" style="margin-top:10px">
                            <div class="form-group">
                                <label style="color:grey">Enter Platform:</label>
                                <select name="value" id="Platform">
                                            <option disabled value> -- select an option -- </option>
                                            <option value="PC">PC</option>
                                            <option value="XBOX">XBOX</option>
                                            <option value="Playstation">Playstation</option>
                                            <option value="Nintendo">Nintendo</option>
                                        </select>
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group">
                                        <input type="submit" id="organisations" name="organisations" class="btn btn-primary" value="Search">
                                        <input id='type' name='type' type='hidden' value=<?php echo $search1; ?>>
                                        <input id='searchby' name='searchby' type='hidden' value=<?php echo $search3; ?>>
                            </div>
                        </form>
                    <?php
                    }
                    ?>

                </div>
            </div> 
        <?php
        }
        ?>

        <?php
            if($_SESSION['type'] !=""){

                
        ?>
        
    <div class="col-sm-12 row ">
        
        <div class="col-sm-4 row ">
        
            <div class=" roundborder greybackground " style="padding:15px; margin-top:15px; min-height:15vh">
            <div style="text-align-last: center;">
                <label class="white" for="file" style="font-size:22px; color:#451093;">Last Search</label>
            </div>
                <?php
                if($_SESSION['type'] =="users"){
                    



                        
                                $value = $_SESSION['value'];
                                if($_SESSION['searchby'] == "age"){ 
                                    $sql2 = "SELECT * FROM Users WHERE Age = '$value'";
                                }else if($_SESSION['searchby'] == "region"){
                                    $sql2 = "SELECT * FROM Users WHERE Region = '$value'";
                                }else if($_SESSION['searchby'] == "platform"){
                                    $sql2 = "SELECT * FROM Users WHERE Platform = '$value'";
                                }else if($_SESSION['searchby'] == "language"){
                                    $sql2 = "SELECT * FROM Users WHERE Languages = '$value'";
                                }else if($_SESSION['searchby'] == "level"){
                                    $sql2 = "SELECT * FROM Users WHERE Level = '$value'";
                                }

                                $result2 = mysqli_query($link, $sql2);

                                 if (mysqli_num_rows($result2) > 0) 
                                 {
                                     ?>
                                    <label class="white" for="file" >Users | Results = (<?php echo mysqli_num_rows($result2) ?>) | Searched: <?php echo strtoupper($_SESSION['searchby']) ?> = <?php echo strtoupper($_SESSION['value']) ?></label>
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
                                                <label class="white col-sm-12" for="file" style=" color:white;font-weight:bolder"><?php echo strtoupper($_SESSION['searchby']) ?> = <?php echo strtoupper($_SESSION['value']) ?></label>

                                            </div>
                                            <?php
                                 }
                            
                        

                            echo "</table>";



                        }else if ($_SESSION['type'] == "organisations"){
                        


                        $value = $_SESSION['value'];
                                if($_SESSION['searchby'] == "platform"){ 
                                    $sql2 = "SELECT * FROM Organisations WHERE Platform = '$value'";
                                }else if($_SESSION['searchby'] == "region"){
                                    $sql2 = "SELECT * FROM Organisations WHERE Region = '$value'";
                                }

                                $result2 = mysqli_query($link, $sql2);

                                 if (mysqli_num_rows($result2) > 0) 
                                 {
                                     ?>
                                    <label class="white" for="file" >Organisation | Results = (<?php echo mysqli_num_rows($result2) ?>) | Searched: <?php echo strtoupper($_SESSION['searchby']) ?> = <?php echo strtoupper($_SESSION['value']) ?></label>
                                    <?php
                                     echo "<table border='1' style='background-color:white ;padding:10px'>

                                        <tr>
                                        <th>Name</th>
                                        <th>Region</th>
                                        <th>Platform</th>
                                        <th>Profile</th>";
                                        echo "</tr>";  
                                    while($row2 = mysqli_fetch_assoc($result2))
                                    {
                                    
                                        

                                        $visitOrganisationID = $row2['OrganisationID'];

                                           

                                        echo "<tr>";

                                        echo "<td style='text-align: center'>" . $row2['Nickname'] . "</td>";

                                        echo "<td style='text-align: center'>" . $row2['Region'] . "</td>";

                                        echo "<td style='text-align: center'>" . $row2['Platform'] . "</td>";

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
                                                <label class="white col-sm-12" for="file" style=" color:white;font-weight:bolder"><?php echo strtoupper($_SESSION['searchby']) ?> = <?php echo strtoupper($_SESSION['value']) ?></label>

                                            </div>
                                            <?php
                                 }
                            
                        

                            echo "</table>";

                            mysqli_close($link);

                        ?>

                        <?php
                        }
                        ?>

            </div>
        </div>
        </div>
        <?php
        }
        ?>





        
</div> 

</body>
</html>