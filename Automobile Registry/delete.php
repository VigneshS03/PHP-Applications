<?php
session_start();
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=autoscrud','fred', 'zap');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if(isset($_POST['delete']) && ($_GET['autos_id']))
{
$sql="DELETE FROM autos WHERE autos_id=:autos_id";
$stmt=$pdo->prepare($sql);
$stmt->execute(array(':autos_id'=>$_GET['autos_id']));
$_SESSION["success"]="Record deleted";
header("location:view.php");
return;
}
if ( ! isset($_GET['autos_id']) ) {
  $_SESSION['error'] = "Missing user_id";
  header('Location: view.php');
  return;
}
$stmt=$pdo->prepare("SELECT * FROM autos WHERE autos_id=:id");
$stmt->execute(array(
':id'=>$_GET['autos_id']));

$row=$stmt->fetch(PDO::FETCH_ASSOC);
if($row===false)
{
$_SESSION["error"]='Bad value for auto_id';
header("location:view.php");
return;
}
?>
<html>
<head>
<title>
Delete Registry</title>
</head>
<body>
<p>Confirm: Deleting <?= htmlentities($row['model']) ?> </p>
<form method="post">
<input type="hidden" name="user_id" value="<?= $row['autos_id'] ?>">
<input type="submit" value="Delete" name="delete">
<a href="view.php">Cancel</a>
</form>
</body>
</html>
