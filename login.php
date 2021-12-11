<?php

session_start();
$_SESSION["loggedin"] = false;

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit;
}
 

require_once "db_connection.php";
 

$username = $pass = "";
$username_err = $password_err = "";
 

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    }elseif(strlen(trim($_POST["username"])) < 6){
        $username_err = "Username must have atleast 6 characters.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $pass = trim($_POST["password"]);
    }
    
    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT UserID, username, password , Level , LevelProgress, Admin, Banned, Platform FROM Users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){

            
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = $username;

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    mysqli_stmt_bind_result($stmt, $UserID, $username, $password , $Level , $LevelProgress, $Admin, $Banned, $Platform);
                    
                    if(mysqli_stmt_fetch($stmt)){
                        
                        if(password_verify($pass, $password)){
                            session_start();
                            
                            $_SESSION["Banned"] = $Banned;    

                            if($_SESSION["Banned"] != 1){
                                $_SESSION["loggedin"] = true;
                                $_SESSION["UserID"] = $UserID;
                                $_SESSION["username"] = $username;       
                                $_SESSION["Admin"] = $Admin;     
                                $_SESSION["password"] = $password;      
                                $_SESSION["Online"] = 1;       
                                $_SESSION["Level"] = $Level;  
                                $_SESSION["Platform"] = $Platform;  
                                if($_SESSION["Platform"] == ""){
                                    $_SESSION['vacancysearchby']= "";
                                    $_SESSION['vacancysearchby2']= "";
                                }else{
                                    $_SESSION['vacancysearchby'] = $_SESSION["Platform"];
                                    $_SESSION['vacancysearchby2'] = $_SESSION["Platform"];
                                }
                                $_SESSION["LevelProgress"] = $LevelProgress;    

                                            $sql = "UPDATE Users SET Online = ? WHERE UserID = ?";
                                            if($stmt = mysqli_prepare($link, $sql))
                                            {
                                                mysqli_stmt_bind_param($stmt, "ss", $_SESSION["Online"], $_SESSION["UserID"]);

                                                if(mysqli_stmt_execute($stmt)){
                                                    header("location: home.php");
                                                }

                                            }

                                
                            }           
                            
                            
                        } else{
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body class="background">
<link rel="stylesheet" href="styles.css">
    <div class="wrapper middle">
        <div class="wrapper roundborder">
        <h2 class="white">Login</h2>
        <p class="white">Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label class="white">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label class="white">Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <?php
                if($_SESSION["Banned"] == 1){
                    echo "<label style='color:red'>Denied Access |  Reason : Banned</label>";
                }        
            ?>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p class="white">Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
        </div>
    </div>    
</body>
</html>