<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>登陆</title>
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
//**************/
echo $operate;
$FileName=basename($_SERVER["PHP_SELF"]);
if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);

$config=file_get_contents("ConfigInfo.inf");
$brand=GetXMLParam($config,"brand");//获取本地设置
$UserInfoUrl=GetXMLParam($config,"UserInfoUrl");//微信用户信息获取网址
$SysName=GetXMLParam($config,"AppName");

//为了获取用户ID
include_once("chunks/chunk_get_openid.php");
//print_r($_GET);echo "<br>";
//print_r($_SESSION);echo "<br>";

$UserInfo=GetItemA("signup_users","openid",$_SESSION[adminID]);
if(strlen($UserInfo[phone])==0)//有用户的ID，但是数据库里面信息不全
{
	$_SESSION[adminID]="";
	//Header("Location:page_user_login.php?err=请登录");
	//return;
}
else//有登陆的记录，并且信息完全
{
	Header("Location:page_user_table.php");	
}

include_once("chunks/chunk_title.php");
include_once("chunks/chunk_login.php");
?>
</body>
</html>
<script>

//var row=document.getElementById("RegisterPort");
//row.style.display="";
</script>