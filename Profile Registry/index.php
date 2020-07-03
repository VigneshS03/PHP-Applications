<?php
session_start();


$pdo = new PDO('mysql:host=localhost;port=3306;dbname=profilejs','fred', 'zap');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
<!DOCTYPE HTML>
<html>
<head>
<title>
Resume Registry
</title>
<style>
table, th, td {
  border: 1px solid black;
}
</style>

</head>
<body>
<div class="container">
<h1>
Resume Registry
</h1>
<?php
$stmt=$pdo->query("SELECT profile_id,user_id,first_name,last_name,headline FROM Profile");
$stmt3=$pdo->query("SELECT COUNT(profile_id) FROM Profile");
$row1=$stmt3->fetch(PDO::FETCH_ASSOC);
$count=implode($row1);
if(isset($_SESSION['name']))
{
echo'<p><a href="logout.php">Logout</a></p>';
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
if($count==0)
{
echo " ";

}
else{
echo("<p>");
echo("<table>");
echo ("<tr><th>");
echo ("Name");
echo("</th><th>");
echo("Headline");
echo("</th><th>");
echo("Action");
echo("</th></tr>");
while($row=$stmt->fetch(PDO::FETCH_ASSOC))
{
echo("<tr><td>");
echo (htmlentities($row['first_name']));
echo (htmlentities($row['last_name']));
echo("</td><td>");
echo (htmlentities($row['headline']));
echo("</td><td>");
echo ('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> /');
echo ('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
echo ("</td></tr>");
}
echo("</table>");
echo("</p>");
}
echo'<p><a href="add.php">Add New Entry</a></p>';
}
else if(!isset($_SESSSION['name']))
{
echo'<p><a href="login.php">Login</a></p>';
if($count==0)
{
echo " ";
}
else
{
echo("<table>");
echo ("<tr><th>");
echo ("Name");
echo("</th><th>");
echo("Headline");
echo("</th></tr>");
while($row=$stmt->fetch(PDO::FETCH_ASSOC))
{
echo("<tr><td>");
echo (htmlentities($row['first_name']));
echo (htmlentities($row['last_name']));
echo("</td><td>");
echo (htmlentities($row['headline']));
echo("</td></tr>");
echo("</table>");
}
}
}
?>
</div>
</body>
</html>
