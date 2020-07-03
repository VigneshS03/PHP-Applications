<?php
if (isset($_POST['cancel'])) {
    header("Location:index.php");
return;
}
session_start();
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=profilejs','fred', 'zap');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_POST['delete']) && ($_GET['profile_id']))
{
$sql="DELETE FROM Profile WHERE profile_id=:profile_id";
$stmt=$pdo->prepare($sql);
$stmt->execute(array(':profile_id'=>$_GET['profile_id']));
$_SESSION["success"]="Record deleted";
header("location:index.php");
return;
}
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location:index.php');
  return;
}
$stmt=$pdo->prepare("SELECT * FROM Profile WHERE profile_id=:id");
$stmt->execute(array(
':id'=>$_GET['profile_id']));

$row=$stmt->fetch(PDO::FETCH_ASSOC);
if($row===false)
{
$_SESSION["error"]='Bad value for auto_id';
header("location:index.php");
return;
}
?>
<html>
<head>
<title>
Delete Registry</title>
</head>
<body>
<p>First Name: <?= htmlentities($row['first_name']) ?> </p>
<p>Last Name: <?= htmlentities($row['last_name']) ?> </p>
<form method="post">
<input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
<input type="submit" value="Delete" name="delete">
<input type="submit" value="Cancel" name="cancel">
</form>
</body>
</html>
