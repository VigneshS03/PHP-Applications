<?php
if (isset($_POST['cancel'])) {
    header("Location:view.php");
return;
}
session_start();
if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');

}

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=autoscrud','fred', 'zap');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_POST['year'])&&isset($_POST['make'])&&isset($_POST['mileage'])&&isset($_POST['model']))
{
if((strlen($_POST['make'])<1)&&(strlen($_POST['model'])<1)&&(strlen($_POST['year'])<1)&&(strlen($_POST['mileage'])<1))
{
$_SESSION["failure"]="All values are required";
header("location:add.php");
return;
}
else if(is_numeric($_POST['year'])!=1)
{
$_SESSION["failure"]="Year must be an integer";
header("location:add.php");
return;
}
else if(is_numeric($_POST['mileage'])!=1)
{
$_SESSION["failure"]="Mileage must be an integer";
header("location:add.php");
return;
}
else
{
$sql="INSERT INTO autos(make,model,year,mileage) values(:make,:model,:year,:mileage)";
$stmt=$pdo->prepare($sql);
$stmt->execute(array(
        ':make' => htmlentities($_POST['make']),
        ':model' => htmlentities($_POST['model']),
        ':year' => htmlentities($_POST['year']),
        ':mileage' => htmlentities($_POST['mileage'])));
$_SESSION["success"]="Record added";
header("location:view.php");
return;


}
}
?>


<!DOCTYPE html>
<html>
<head><title>
Add Registry 
</title>

</head>
<body>
<div class="container">
<?php
if(isset($_SESSION["failure"]))
{
echo('<p style="color: red;">'.$_SESSION["failure"]."</p>\n");
unset($_SESSION["failure"]);

}
?>
<form method="POST"> 
<label for="make">Make:</label>
<input type="text" id="make" name="make"></input><br><br>
<label for="model">Model:</label>
<input type="text" id="model" name="model"></input><br><br>
<label for="year">Year:</label>
<input type="text" id="year" name="year"></input><br><br>
<label for="mileage">Mileage:</label>
<input type="text" id="mileage" name="mileage"></input><br><br>
<input type="submit" name="add" value="Add"/>
<input type="submit" name="cancel" value="Cancel"></input>
</form>
</div>
</body>
</html>
