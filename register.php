<?php
require_once "db_connection.php";
 
$username = $password = $confirm_password = $age = "";
$username_err = $password_err = $confirm_password_err = $age_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    }elseif(strlen(trim($_POST["username"])) < 6){
        $username_err = "Username must have atleast 6 characters.";
    }else{
        $sql = "SELECT UserID FROM Users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = trim($_POST["username"]);
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an e-mail.";     
    }else{
        if (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
            $email_err = "This email is invalid";
        }else{
            $email = trim($_POST["email"]);
        }
    }

    if(empty($_POST["age"])){
        $age_err = "Please enter an age.";     
    }elseif($_POST["age"] < 16){
        $age_err = "User must be atleast 16 to register";
    }elseif($_POST["age"] > 150){
        $age_err = "Age is invalid";
    }else{
        $age = $_POST["age"];
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    
    
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)){

        $temp;
        $sql = "SELECT UserID FROM Users ORDER BY UserID DESC LIMIT 1";
      
        
        $result = mysqli_query($link, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $temp = (int)$row["UserID"] + 1;
            }
        }


            $sql = "INSERT INTO Users (username, password , UserID , Email , Age) values (?, ?, ?, ?, ?)";

            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "sssss", $username, $param_password, $temp , $email , $age);

                if(mysqli_stmt_execute($stmt)){
                    header("location: login.php");
                } else{
                    echo "Something went wrong. Please try again later.";
                    printf("Error: %s.\n", $stmt->error);
                }

            mysqli_stmt_close($stmt);
        }
        
       mysqli_stmt_close($stmt);
    }
    
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body class="background">
<link rel="stylesheet" href="styles.css">
    <div class="wrapper middle">
        <div class="wrapper roundborder">
        <h2 class="white">Register</h2>
        <p class="white">Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label class="white">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label class="white">E-mail</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>   
            <div class="form-group <?php echo (!empty(age_err)) ? 'has-error' : ''; ?>">
                <label class="white">Age</label>
                <input type="text" name="age" class="form-control" value="<?php echo $age; ?>">
                <span class="help-block"><?php echo $age_err; ?></span>
            </div>   
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label class="white">Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label class="white">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p class="white">Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
        </div> 
    </div>    
</body>
</html>