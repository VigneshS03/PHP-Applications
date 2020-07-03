<?php
if(isset($_POST['cancel']))
{
header("location:view.php");
return;
}
session_start();

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

if(isset($_POST['email'])&&isset($_POST['pass']))
{
unset($_SESSION['email']);
if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/',$_POST['email']))
{
$matches=true;
}
else
{
$matches=false;
}
if(strlen($_POST['email'])<1||strlen($_POST['pass'])<1)
{
$_SESSION["failure"]="Username and password required";
error_log("Login failed ");
header("Location:login.php");
return;
}
else if($matches==false)
{
$_SESSION["failure"]="Email must have an at-sign (@)";
error_log("Login failed ");
header("Location:login.php");
return;
}
else if($matches==true){
 $check = hash('md5', $salt.$_POST['pass']);
        if ( $check == $stored_hash ) {
            $_SESSION['name'] = $_POST['email'];
            error_log("Login success ".$_POST['email']);
             header("Location:view.php");
            return;
        } else {
            $_SESSION["failure"] = "Incorrect password";
            error_log("Login failed");
            header("Location:login.php");
            return;
            
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<title>
Login Page
</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Please Login</h1>
<?php
if ( isset($_SESSION['failure']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['failure'])."</p>\n");
    unset($_SESSION['failure']);
}
?>
<form method="POST">
<label for='email'>Username</label>
<input type='text' id='email' name='email'></input><br>
<label for='pass'>Password</label>
<input type='text' id='pass' name='pass'></input><br>
<input type='submit' value="Log In" name='login'>
<input type='submit' value='cancel' name='cancel'>
</form>
</div>
</body>
</html>

