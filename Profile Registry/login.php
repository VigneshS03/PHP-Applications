<?php
session_start();

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=profilejs','fred', 'zap');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_POST['email'])&&isset($_POST['pass']))
{
unset($_SESSION['email']);
unset($_SESSION['user_id']);
$salt = 'XyZzy12*_';
$check = hash('md5', $salt.$_POST['pass']);
$stmt = $pdo->prepare('SELECT user_id, name FROM users
    WHERE email = :em AND password = :pw');
$stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ( $row !== false ) {
    $_SESSION['name'] = $row['name'];
    $_SESSION['user_id'] = $row['user_id'];
    header("Location: index.php");
    return;
}
else
{
$_SESSION['failure']="Incorrect Password";
header("Location:login.php");
return;
}
}

?>
<!DOCTYPE HTML>
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
<input type="submit" onclick="return doValidate();" value="Log In">
<input type='submit' value='cancel' name='cancel'>
</form>
</div>
<script>
function doValidate() {
    console.log('Validating...');
    try {
        addr = document.getElementById('email').value;
        pw = document.getElementById('pass').value;
        console.log("Validating addr="+addr+" pw="+pw);
        if (addr == null || addr == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if ( addr.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}
</script>
</body>
</html>


