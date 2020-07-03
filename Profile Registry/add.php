<?php
if (isset($_POST['cancel'])) {
    header("Location:index.php");
return;
}
session_start();
if ( ! isset($_SESSION['user_id']) ) {
    die('ACCESS DENIED');
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
header("location:add.php");
return;
}
else if($matches==false)
{
$_SESSION["failure"]="Email must have an at-sign (@)";
header("Location:add.php");
return;
}

else if($matches==true)
{
$sql="INSERT INTO Profile (user_id,first_name,last_name,email,headline,summary) VALUES (:usr,:fn,:ln,:email,:hd,:su)";

$stmt=$pdo->prepare($sql);
echo "before insert";

$stmt->execute(array(
        ':usr' => htmlentities($_SESSION['user_id']),
        ':fn' => htmlentities($_POST['first_name']),
        ':ln' => htmlentities($_POST['last_name']),
        ':email' => htmlentities($_POST['email']),
        ':hd' => htmlentities($_POST['headline']),
        ':su' => htmlentities($_POST['summary'])));

$_SESSION["success"]="Record added";
header("location:index.php");
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
<h1>Adding profile for <?= $_SESSION['name'] ?></h1>
<?php
if(isset($_SESSION["failure"]))
{
echo('<p style="color: red;">'.$_SESSION["failure"]."</p>\n");
unset($_SESSION["failure"]);

}
?>
<form method="POST">
<p>First Name:
<input type="text" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"/></p>
<p>Email:
<input type="text" name="email" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea>
<p>
<input type="submit" name="add" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>
</body>
</html>
