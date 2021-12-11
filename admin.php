<!DOCTYPE html>
<html style="background-color: #451093">
<head >
    <meta charset="UTF-8">
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<?php include('navbar.php'); 
require "db_connection.php";

session_start();

if($_SESSION["Admin"] === false){
    header("location: index.php");
    exit;
}




if($_POST["deleteUser"]){
    $tempuserID = $_POST["UserID"];

    echo $tempuserID;
    $sql = "DELETE FROM Users WHERE UserID = ?";


        if($stmt = mysqli_prepare($link, $sql) )
                        {
                            mysqli_stmt_bind_param($stmt, "s", $tempuserID);

                            if(mysqli_stmt_execute($stmt) ){
                                header("Refresh:0");
                            } else{
                                echo "Something went wrong. Please try again later.";
                                printf("Error: %s.\n", $stmt->error);
                            }

                            
                            mysqli_stmt_close($stmt);
                            
                        }
}



if($_POST["ban"] || $_POST["unban"]){

    $userID = $_POST["UserID"];

    if($_POST["ban"]){
        $Banned = 1;
    }else if($_POST["unban"]){
        $Banned = 0;
    }
            

    $sql = "SELECT * FROM Users WHERE UserID = $userID";
            
    $result = mysqli_query($link, $sql);
            
    if (mysqli_num_rows($result) > 0) 
    {
        while($row = mysqli_fetch_assoc($result)) 
        {
            
            $username = $row["username"];
            $password = $row["password"];
            $Email = $row["Email"];
            $Admin = $row["Admin"];
            $Age = $row["Age"];
            $Region = $row["Region"];
            $Platform = $row["Platform"];
            $About = $row["About"];
            $Languages = $row["Languages"];
            $Online = $row["Online"];
            $Level = $row["Level"];
            $LevelProgress = $row["LevelProgress"];
            
        }
    }
  
                     $sql = "REPLACE INTO Users SET UserID = ?, username = ?, password = ?, Age = ? , Email = ? , Region = ? , Platform = ? , Languages = ? , About = ? , Level= ?, LevelProgress = ?, Admin = ?, Banned = ?";
                    if($stmt = mysqli_prepare($link, $sql) )
                    {
                        mysqli_stmt_bind_param($stmt, "sssssssssssss", $userID, $username , $password, $Age , $Email , $Region , $Platform , $Languages , $About,
                        $Level, $LevelProgress,$Admin, $Banned);

                        if(mysqli_stmt_execute($stmt) ){
                            header("Refresh:0");
                        } else{
                            echo "Something went wrong. Please try again later.";
                            printf("Error: %s.\n", $stmt->error);
                        }

                        
                        mysqli_stmt_close($stmt);
                        
                    }



    }

?>






<body style="background-color: #451093 ; display:block">

<h3 style="color:white; font-weight:bold ; text-align: -webkit-center;">Admin</h3>

   <div class="row col-sm-11" style=" margin-left:5% ; margin-top: 15px ; position:relative;">
        <div class="col-sm-12" >
            <div class="col-sm-3 roundborder greybackground wrapper ">


                <label class="white" for="file" >All Users</label>
                <?php


                        echo "<table border='1' style='background-color:white ;padding:10px;'>

                        <tr>
                        <th>Online</th>
                        <th>Username</th>
                        <th>Ban/Unban</th>
                        <th>Remove User</th>
                        </tr>";


                                
                                $temp = $row['FriendID'];

                                $sql2 = "SELECT * FROM Users";

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
                                
                                        if($row2['Banned'] == 0){
                                            echo "<td >" . 
                                                "<form method='post' style='text-align: -webkit-center;'>
                                                    <input id='UserID' name='UserID' type='hidden' value='$temp2'>
                                                    <button type='submit' name='ban' class='button' value='button' style='background: #0080008c;'>Not Banned</button> 
                                                </form>". 
                                                "</td>";
                                        }else{
                                            echo "<td >" . 
                                                "<form method='post' style='text-align: -webkit-center;'>
                                                    <input id='UserID' name='UserID' type='hidden' value='$temp2'>
                                                    <button type='submit' name='unban' class='button' value='button' style='background: #8c2929;color: white;'>Banned</button> 
                                                </form>". 
                                                "</td>";
                                        }

                                        echo "<td >" . 
                                                "<form method='post' style='text-align: -webkit-center;'>
                                                    <input id='UserID' name='UserID' type='hidden' value='$temp2'>
                                                    <button type='submit' name='deleteUser' class='button' value='button' style='background: red;color: white;'>Remove</button> 
                                                </form>". 
                                                "</td>";


                                        echo "</tr>";

                            }
                        } else{
                            printf("Error: %s.\n", $row->$result);
                        }

                            echo "</table>";

                        ?>

             </div>
        </div>
   </div>


</body>
</html>