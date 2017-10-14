<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>登陆后台</title>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
</head>
<body class="BodyNoSpace">
<?
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();
include_once("../function/config.php");
include_once("../function/JSSDK.php");
include_once("../function/Script.php");

//print_r($_SESSION);echo "<br>";
$operate=$_GET['operate'];


$FileName=basename($_SERVER["PHP_SELF"]);
if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);
switch($operate)
{
	case "logout":
		$_SESSION[adminID]="";
		break;
}
/*
//print_r($_SESSION);echo "sdfsdf";
if(isset($_SESSION[adminID]))
{
	Header("page_orders_user.php");
}
*/

//$TargetPage="console.php?singup=do";
$LoginModal="AdminModal";
include_once("chunks/chunk_title.php");
include_once("chunks/chunk_login.php");
?>
</body>
</html>
<script>

//var row=document.getElementById("RegisterPort");
//row.style.display="";
</script>