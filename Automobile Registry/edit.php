<?php
session_start();
if (isset($_POST['cancel'])) {
    header("Location:view.php");
return;
}

if ( ! isset($_SESSION['name']) ) {
    die("ACCESS DENIED");
}

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=autoscrud','fred', 'zap');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if(isset($_POST['year']) && isset($_POST['make']) && isset($_POST['mileage']) && isset($_POST['model']) && isset($_GET['autos_id']))
{

if((strlen($_POST['make'])<1) || (strlen($_POST['model'])<1) || (strlen($_POST['year'])<1) || (strlen($_POST['mileage'])<1))
{
$_SESSION["failure"]="All values are required";
header("Location: edit.php?autos_id=".$_GET['autos_id']);
return;
}
else if(is_numeric($_POST['year'])!=1)
{
$_SESSION["failure"]="Year must be an integer";
header("Location: edit.php?autos_id=".$_GET['autos_id']);
return;
}
else if(is_numeric($_POST['mileage'])!=1)
{
$_SESSION["failure"]="Mileage must be an integer";
header("Location: edit.php?autos_id=".$_GET['autos_id']);
return;
}
else
{
$sql="UPDATE autos SET make=:make,model=:model,year=:year,mileage=:mileage WHERE autos_id=:autos_id";

$stmt=$pdo->prepare($sql);

$stmt->execute(array(
        ':make' => htmlentities($_POST['make']),
        ':model' => htmlentities($_POST['model']),
        ':year' => htmlentities($_POST['year']),
        ':mileage' => htmlentities($_POST['mileage']),
        ':autos_id'=>$_GET['autos_id']));
$_SESSION["success"]="Record updated";
header("location:view.php");
return;
}
}
if ( ! isset($_GET['autos_id']) ) {
  $_SESSION['error'] = "Missing user_id";
  header('Location:view.php');
  return;
}
$stmt=$pdo->prepare("SELECT * FROM autos WHERE autos_id=:id");
$stmt->execute(array(":id"=>$_GET['autos_id']));
$row=$stmt->fetch(PDO::FETCH_ASSOC);
if($row===false)
{
$_SESSION["error"]='Bad value for auto_id';
header("location:view.php");
return;
}
$mk=htmlentities($row['make']);
$md=htmlentities($row['model']);
$yr=htmlentities($row['year']);
$ml=htmlentities($row['mileage']);
$autos_id=$row['autos_id'];
?>
<!DOCTYPE html>
<html>
<head><title>
Edit Registry
</title>
</head>
<body>
<div class="container">
<?php
{
if(isset($_SESSION["failure"]))
echo('<p style="color: red;">'.$_SESSION["failure"]."</p>\n");
unset($_SESSION["failure"]);
}
?>
<form method="POST"> 
<input type="hidden" name="user_id" value="<?= $autos_id ?>">
<label for="make">Make:</label>
<input type="text" id="make" name="make" value="<?= $mk ?>"></input><br><br>
<label for="model">Model:</label>
<input type="text" id="model" name="model" value="<?= $md ?>"></input><br><br>
<label for="year">Year:</label>
<input type="text" id="year" name="year" value="<?= $yr ?>"></input><br><br>
<label for="mileage">Mileage:</label>
<input type="text" id="mileage" name="mileage" value="<?= $ml ?>"></input><br><br>
<input type="submit" name="add" value="Save"/>
<input type="submit" name="cancel" value="Cancel"/>
</form>
</div>
</body>
</html>
