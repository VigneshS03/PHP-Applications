<?php
session_start();
if (isset($_POST['cancel'])) {
    header("Location:index.php");
return;
}

if ( ! isset($_SESSION['user_id']) ) {
    die("ACCESS DENIED");
}

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=profilejs','fred', 'zap');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if(isset($_POST['first_name'])&&isset($_POST['last_name'])&&isset($_POST['email'])&&isset($_POST['headline'])&&isset($_POST['summary']))
{
if (preg_match('/@/',$_POST['email']))
{
$matches=true;
}
else
{
$matches=false;
}
if((strlen($_POST['first_name'])<1)||(strlen($_POST['last_name'])<1)||(strlen($_POST['email'])<1)||(strlen($_POST['headline'])<1)||(strlen($_POST['summary'])<1))
{
$_SESSION["failure"]="All values are required";
header("Location: edit.php?profile_id=".$_GET['profile_id']);
return;
}
else if($matches==false)
{
$_SESSION["failure"]="Email must have an at-sign (@)";
header("Location: edit.php?profile_id=".$_GET['profile_id']);
return;
}
else
{
$sql="UPDATE Profile SET first_name=:fn,last_name=:ln,email=:email,headline=:hd,summary=:su WHERE profile_id=:profile_id";

$stmt=$pdo->prepare($sql);

$stmt->execute(array(
        ':fn' => htmlentities($_POST['first_name']),
        ':ln' => htmlentities($_POST['last_name']),
        ':email' => htmlentities($_POST['email']),
        ':hd' => htmlentities($_POST['headline']),
        ':su' => htmlentities($_POST['summary']),
        ':profile_id'=>$_GET['profile_id']));
$_SESSION["success"]="Record updated";
header("location:index.php");
return;
}
}
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location:index.php');
  return;
}
$stmt=$pdo->prepare("SELECT * FROM Profile WHERE profile_id=:id");
$stmt->execute(array(":id"=>$_GET['profile_id']));
$row=$stmt->fetch(PDO::FETCH_ASSOC);
if($row===false)
{
$_SESSION["error"]='Bad value for profile_id';
header("location:index.php");
return;
}
$fn=htmlentities($row['first_name']);
$ln=htmlentities($row['last_name']);
$email=htmlentities($row['email']);
$hd=htmlentities($row['headline']);
$su=htmlentities($row['summary']);
$profile_id=$row['profile_id'];
?>
<!DOCTYPE html>
<html>
<head><title>
Edit Registry
</title>
</head>
<body>
<div class="container">
<h1>Editing Profile for <?= $_SESSION['name'] ?></h1>
<?php
{
if(isset($_SESSION["failure"]))
echo('<p style="color: red;">'.$_SESSION["failure"]."</p>\n");
unset($_SESSION["failure"]);
}
?>

<form method="POST">
<p>First Name:
<input type="text" name="first_name" value="<?= $fn ?>" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" value="<?= $ln ?>" size="60"/></p>
<p>Email:
<input type="text" name="email" value="<?= $email ?>" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" value="<?= $hd ?>" size="80"/></p>
<p>Summary:<br/>
<input name="summary" value="<?= $su ?>" rows="8" cols="80"></textarea>
<p>
<input type="submit" name="add" value="Update">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</form>
</div>
</body>
</html>
