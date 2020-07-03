<?php
session_start();

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=autoscrud','fred', 'zap');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
<!DOCTYPE html>

<html>
<head>
<title>Automobile registry</title>
<?php require_once "bootstrap.php"; ?>
<style>
table, th, td {
 border: 1px solid black;
  border-collapse: double;
  
}
</style>
</head>
<body>
<?php
if (!isset($_SESSION['name']))
{
echo '<div class="container">';
echo '<h1>Welcome to Automobile Database</h1>';
echo '<p> <a href="login.php">Please log in</a> </p>';
echo '<p> Attempt to add data <a href="add.php">add.php</a> without logging in.';
echo '</p>';
echo '</div>';

}
else
{
echo "<div class='container'>";
echo "<h1>Welcome to the Automobile database</h1>"; 
if(isset($_SESSION["success"]))
{
 echo('<p style="color: green;">'.$_SESSION["success"]."</p>\n");
unset($_SESSION["success"]);

}
if(isset($_SESSION["error"]))
{
 echo('<p style="color: red;">'.$_SESSION["error"]."</p>\n");
 unset($_SESSION["error"]);

}

$stmt2=$pdo->query("SELECT autos_id,make,model,year,mileage FROM autos");
$stmt3=$pdo->query("SELECT COUNT(autos_id) FROM autos");
$row1=$stmt3->fetch(PDO::FETCH_ASSOC);
$count=implode($row1);
if($count==0)
{ 
echo "<p>No rows found</p>";	
} 
else
{

echo('<table>'."\n"); 
echo("<tr><th>");
echo("Make");
echo("</th><th>");
echo("Model");
echo("</th><th>");
echo("Year");
echo("</th><th>");
echo("Mileage");
echo("</th><th>");
echo("Action");
echo("</th></tr>");
while($row=$stmt2->fetch(PDO::FETCH_ASSOC))
{
echo "<tr><td>";
echo (htmlentities($row['make']));
echo ("</td><td>");
echo (htmlentities($row['model']));
echo ("</td><td>");
echo (htmlentities($row['year']));
echo ("</td><td>");
echo (htmlentities($row['mileage']));
echo ("</td><td>");
echo ('<a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a> /');
echo ('<a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a>');
echo ("</td></tr>");
}


}

echo '</table>';
echo '<p><a href="add.php">Add New Entry</a></p>';
echo '<p><a href="logout.php">Logout</a></p>';
echo '</div>';
}
?>
</body>
</html>
