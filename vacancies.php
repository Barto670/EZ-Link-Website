<!DOCTYPE html>
<html style="background-color: #451093">
<head >
    <meta charset="UTF-8">
    <title>Vacancies</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<?php include('navbar.php'); 

require "db_connection.php";

session_start();

$vacancyStatus = "off";


$sessionUserID = $_SESSION['UserID'];

        if(isset($_POST['turnonadd']) ){
            $vacancyStatus = "add";
        }

        if(isset($_POST['turnoffadd'])){
            $vacancyStatus = "off";
        }

        if(isset($_POST['editVacancy'])){
            $vacancyStatus = "edit";
            $editOrganisationID = $_POST["OrganisationID"];
            $editPlatform = $_POST["Platform"];
            $editOrganisationName = $_POST["OrganisationName"];
            $editVacancyID = $_POST["VacancyID"];

            $sql = "SELECT Description FROM VacancyListings WHERE VacancyID = $editVacancyID";
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_assoc($result);
            $editOrganisationDescription = $row["Description"];
        }

        if($_POST["removeVacancy"]) 
        { 

            $temp = $_POST["VacancyID"];

            echo $temp;

            $sql = "DELETE FROM VacancyListings WHERE VacancyID = $temp";
            
            mysqli_query($link, $sql);

            header("Refresh:0");
            
        }  


        if($_POST["orderBy"]) 
        { 
            $temp2 = $_POST["orderBy"];
            $_SESSION['vacancysearchby']= $temp2;

            header("Refresh:0");
            
        }  


        if($_POST["viewOrganisation"]) { 
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


        if($_POST["addVacancy"]) 
        { 
            $temp;

            $tempOrganisationID = $_POST["OrganisationID"];
            $tempDescription = $_POST["Description"];
            $tempPlatform = $_POST["Platform"];


            echo $tempOrganisationID;

            $sql = "SELECT VacancyID FROM VacancyListings ORDER BY VacancyID DESC LIMIT 1";
        
            $result = mysqli_query($link, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $temp = (int)$row["VacancyID"] + 1;
                }
            } else {
                $temp = 0;
            }


                $sql = "INSERT INTO VacancyListings (VacancyID, OrganisationID , Description , Platform) values (?, ?, ?, ?)";
                if($stmt = mysqli_prepare($link, $sql)){
                    mysqli_stmt_bind_param($stmt, "ssss", $temp, $tempOrganisationID, $tempDescription , $tempPlatform);

                    if(mysqli_stmt_execute($stmt)){
                        header("Refresh:0");
                        $vacancyStatus = "none";
                    } else{
                        echo "Something went wrong. Please try again later.";
                        printf("Error: %s.\n", $stmt->error);
                    }

                mysqli_stmt_close($stmt);
            }
        } 

        if($_POST["updateVacancy"]) 
        {
            $tempOrganisationIDUpdate = $_POST["OrganisationID"];
            $tempVacancyIDUpdate = $_POST["VacancyID"]; 
            $tempDescriptionUpdate = $_POST["Description"]; 
            $tempPlatformUpdate = $_POST["Platform"]; 

            

            $sql2 = "UPDATE VacancyListings SET Description = ?, Platform = ? WHERE VacancyID = ?";
            if($stmt2 = mysqli_prepare($link, $sql2))
            {
                $temp = 0;
                mysqli_stmt_bind_param($stmt2, "sss", $tempDescriptionUpdate, $tempPlatformUpdate, $tempVacancyIDUpdate);

                if(mysqli_stmt_execute($stmt2)){
                    header("Refresh:0");
                }

            }


        }
    

?>

<body style="background-color: #451093 ; display:block">

<div class="card col-sm-1"></div>
<div class="card col-sm-10 roundborder greybackground " style="padding-bottom:10px">


<?php
$sql = "SELECT * FROM Organisations WHERE OwnerID = $sessionUserID";
$result = mysqli_query($link, $sql);

if($_SESSION['Admin'] == 1 || mysqli_fetch_assoc($result) >= 1)
{  
?>
    <form method="post" style="margin-top:5px">
        <?php
        if($vacancyStatus == "add"){
        ?>
            <input type="submit" name="turnoffadd" class="btn editbutton" value="Close">
        <?php
        }else if($vacancyStatus == "off"){
        ?>
            <input type="submit" name="turnonadd" class="btn editbutton" value="Add Vacancy">
        <?php
        }else if($vacancyStatus == "edit"){
        ?>
            <input type="submit" name="turnoffadd" class="btn editbutton" value="Close">
        <?php
        }
        ?>
    </form>
<?php
}
?>


<?php
if($vacancyStatus != "none")
{
?>
        
    <?php
    if($vacancyStatus == "add")
    {
        
        $sql = "SELECT * FROM Organisations WHERE OwnerID = $sessionUserID";

        $result = mysqli_query($link, $sql);
    ?>
        <h3 style="color:white; font-weight:bold ;">Add Vacancy</h3>

        <form method="post">
            <div style="max-width: max-content; background-color:#451093; padding:10px; text-align: -webkit-left;" class="roundborder">

                
                <div >
                    <label class="white">Organisation:</label>
                    <select name="OrganisationID" class="selectvacancy" id="OrganisationID">
                        <?php
                        while($row = mysqli_fetch_assoc($result))
                        {
                            $tempOrganisationname = $row['Nickname'];
                            $tempOrganisationID = $row['OrganisationID'];
                        
                            
                        ?>
                            <option value=<?php echo $tempOrganisationID ?>><?php echo $tempOrganisationname ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>   

                <div style="margin-top:5px">
                    <label class="white">Platform Required:</label>
                    <select name="Platform" class="selectvacancy" id="Platform">
                            <option selected disabled value> -- select an option -- </option>
                            <option value="PC">PC</option>
                            <option value="XBOX">XBOX</option>
                            <option value="Playstation">Playstation</option>
                            <option value="Nintendo">Nintendo</option>
                    </select>
                </div>   
                
                <div> 
                <label class="white" style="vertical-align: top;">Description:</label>
                </div> 
                <div> 
                    <textarea rows="4" cols="50" maxlength="100" type="text" name="Description" style="resize: none;max-height:200px ; color:white; background-color:#232323"value=""></textarea>
                </div>  
                <div class="form-group">
                    <input type="submit" name="addVacancy" class="btn greybutton" value="Submit">
                    <input type="reset" class="btn greybutton" value="Reset">
                </div>
            </div>
        </form>

    <?php
    }
    else if($vacancyStatus == "edit")
    {
    ?>
    <h3 style="color:white; font-weight:bold ;">Edit Vacancy</h3>


    <form method="post">
            <div style="max-width: max-content; background-color:#451093; padding:10px; text-align: -webkit-left;" class="roundborder">

                
                <div >
                    <label class="white">Organisation:</label>
                    <select name="OrganisationID" class="selectvacancy" id="OrganisationID" disabled>
                        <option value=<?php echo $editOrganisationID ?>><?php echo $editOrganisationName ?></option>
                    </select>
                </div>   

                <div style="margin-top:5px">
                    <label class="white">Platform Required:</label>
                    <select name="Platform" class="selectvacancy" id="Platform">
                            <option <?php if($editPlatform == ''){echo("selected");}?> disabled value> -- select an option -- </option>
                            <option <?php if($editPlatform == 'PC'){echo("selected");}?> value="PC">PC</option>
                            <option <?php if($editPlatform == 'XBOX'){echo("selected");}?> value="XBOX">XBOX</option>
                            <option <?php if($editPlatform == 'Playstation'){echo("selected");}?> value="Playstation">Playstation</option>
                            <option <?php if($editPlatform == 'Nintendo'){echo("selected");}?> value="Nintendo">Nintendo</option>
                    </select>
                </div>   
                
                <div> 
                <label class="white" style="vertical-align: top;">Description:</label>
                </div> 
                <div> 
                    <textarea rows="4" cols="50" maxlength="100" type="text" name="Description" style="resize: none;max-height:200px ; color:white; background-color:#232323" value=""><?php echo $editOrganisationDescription ?></textarea>
                </div>  
                <div class="form-group">
                    <input id='VacancyID' name='VacancyID' type='hidden' value=<?php echo $editVacancyID ?>>
                    <input type="submit" name="updateVacancy" class="btn greybutton" value="Update">
                </div>
            </div>
        </form>
    <?php
    }
    ?>
<?php
}
?>




<h3 style="color:white; font-weight:bold ; text-align: -webkit-center;">Vacancy Listings</h3>


                
                <form method="post">
                    <div style="margin-top:5px">
                        <label class="white">Order By:</label>
                        <select name="orderBy" class="selectvacancy" type="submit" id="orderBy" onchange="this.form.submit()">
                                <option <?php if($_SESSION['vacancysearchby'] == ''){echo("selected");}?> disabled value> -- select an option -- </option>
                                <option <?php if($_SESSION['vacancysearchby'] == 'PC'){echo("selected");}?> value="PC">PC</option>
                                <option <?php if($_SESSION['vacancysearchby'] == 'XBOX'){echo("selected");}?> value="XBOX">XBOX</option>
                                <option <?php if($_SESSION['vacancysearchby'] == 'Playstation'){echo("selected");}?> value="Playstation">Playstation</option>
                                <option <?php if($_SESSION['vacancysearchby'] == 'Nintendo'){echo("selected");}?> value="Nintendo">Nintendo</option>
                        </select>
                    </div>  
                </form>
                <?php
                $searchvacancy = $_SESSION['vacancysearchby'];
                if($searchvacancy ==""){
                    $sql = "SELECT * FROM VacancyListings ORDER BY VacancyID DESC;";
                }else{
                    $sql = "SELECT * FROM VacancyListings ORDER BY FIELD(Platform, '$searchvacancy') DESC;";
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

                                        <?php
                                            if($_SESSION['Admin'] == 1 || $tempOwnerID == $_SESSION['UserID'])
                                            {
                                        ?>
                                            <div class="col-md-3 jobpost-apply-btn" style="text-align: -webkit-right;">
                                                <form method="post" style="margin-bottom:10px">
                                                    <input id='OrganisationID' name='OrganisationID' type='hidden' value=<?php echo $tempOrganisationID ?>>
                                                    <input id='OrganisationName' name='OrganisationName' type='hidden' value=<?php echo $tempNickname ?>>
                                                    <input id='Platform' name='Platform' type='hidden' value=<?php echo $tempPlatform ?>>
                                                    <input id='VacancyID' name='VacancyID' type='hidden' value=<?php echo $tempVacancyID ?>>
                                                    <input id='VacancyDescription' name='VacancyDescription' type='hidden' value=<?php echo $tempDescription ?>>
                                                    <input type="submit" name="viewOrganisation" class="greybutton btn" value="View Organisation"/> 
                                                    <button type='submit' name='removeVacancy' class='btn transparentButton' value='button'><i class='large material-icons' style='color:red '>delete</i></button> 
                                                    <button type='submit' name='editVacancy' class='btn transparentButton' value='button'><i class='large material-icons' style='color:white ; opacity:1'>edit</i></button> 
                                                </form> 
                                            </div>

                                        <?php
                                            }else{
                                        ?>

                                            <div class="col-md-3 jobpost-apply-btn" style="text-align: -webkit-right;">
                                                <form method="post" style="margin-bottom:10px">
                                                    <input id='OrganisationID' name='OrganisationID' type='hidden' value=<?php echo $tempOrganisationID ?>>
                                                    <input type="submit" name="viewOrganisation" class="greybutton btn" value="View Organisation"/> 
                                                </form> 
                                            </div>

                                        <?php
                                            }
                                        ?>
                                        
                                        
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
<div class="card col-sm-1"></div>

</body>
</html>