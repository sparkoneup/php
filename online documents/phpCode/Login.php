<?php
require 'varfilter.php';
require 'data_handler.php';
session_start();
require_once 'router.php';

if(!empty($_SESSION['username']))
{
header('location:doctorhome.php');
}
elseif (!empty($_SESSION['cusername']))
{
header('location:clienthome.php');
}
elseif (!empty($_SESSION['ausername']))
{
	header('location: adminhome.php');
}

require 'database.php';
error_reporting(0);

if (isset($_POST['login']))
{
$name=$_POST['username'];
$pass=$_POST['psswd'];

#$getc= new enc();
#$r=$getc->sam0hack($pass420);//Super Encrypted passsword with sam0hack. for more search on google sam0hack
#$pass=$r;
if (empty($name) && empty($pass))
{
$echo = "Please fill username and password";
}
else
{
$username=unhack($name);
$password=unhack($pass);
$finddoc=mysql_query("select username,password,doc_id,con_id from doctor_login where username='$username' AND password='$password' ")or die(mysql_error());
if (mysql_num_rows($finddoc)==1)
{

$m=mysql_fetch_row($finddoc);
$iid=$m[2];
$_SESSION['username']=$username;

$cod=$m[3];
$_SESSION['con_id']=$cod;
$_SESSION['usertype']="doc";
$_SESSION['did']=$iid;
header('location: doctorhome.php');
}

$findadmin=$conn->prepare("select name,password from admin where name=:username AND password=:pass");
	$findadmin->execute(array(":username"=>$username,":password"=>$pass));
if($findadmin->rowCount()==1)
{
$_SESSION['usertype']="admin";
$_SESSION['ausername']=$username;
header('location: adminhome.php');

}

else {
$findclient=$conn->prepare("select username,password,client_id,con_id from client_login where username=:username AND password=:pass ");
	$findclient->execute(array(":username"=>$username,":password"=>$pass));
if ($findclient->rowCount()==1)
{

$mss=$findclient->fetch($findclient);
$ccid=$mss[2];
$_SESSION['cid']=$ccid;

$cod=$mss[3];
$_SESSION['con_id']=$cod;

$_SESSION['usertype']="cli";
$_SESSION['cusername']=$username;
header('location: clienthome.php');
}
else
{

$echo= "Wrong username and password";
}
}

}
}




?>
<!doctype html>

<head>

	<!-- Basics -->

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>Login</title>

	<!-- CSS -->

	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/styles.css">

</head>

	<!-- Main HTML -->

<body>

	<!-- Begin Page Content -->

	<div id="container">

		<form method="post">

		<label for="name">Username:</label>

		<input type="name" name="username">

		<label for="username">Password:</label>

		<p><a href="ForgotPassword.php">Forgot your password?</a>

		<input type="password" name="psswd" style="height:35px;">

		<div id="lower" style="height:86px;">

		<input type="checkbox" style="float:left; margin-left:10px;"><label class="check" for="checkbox" style="margin-top:-10px; margin-bottom:35px;" > &nbsp;&nbsp;Keep me logged in <input type="submit" name="login" value="Login" style="float: right;
margin-right: 10px;
margin-top: 20px;"></label>

		<?php echo    $echo;?>
<a style="margin-left:10px; margin-top:30px;" href="signup_form.php">New User?    Create an account</a>

		</div>

		</form>

	</div>


	<!-- End Page Content -->

</body>

</html>
