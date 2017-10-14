<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>配置</title>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();

include_once("../function/config.php");
include_once("../function/JSSDK.php");
include_once("../function/Script.php");

/*
$data->Brand = "三恒";
$data->AppName = "products_sanheng";
$fp = fopen("ConfigInfo.php", "w");
fwrite($fp, json_encode($data));
fclose($fp);*/

//可以访问的系统名称，防止访问不该访问的系统
if(!isset($_SESSION['SysName']))
{
	$config=file_get_contents("ConfigInfo.inf");
	$brand=GetXMLParam($config,"AppName");//获取本地设置
	$_SESSION['SysName']=$brand;//本系统名称
}
//**************

$FileName=basename($_SERVER["PHP_SELF"]);

//必须要管理员才能登陆
if(!isset($_SESSION['adminID']))
{
	//Header("Location:Login.php");
	//$operate="login";
	Header("Location:Login.php");
}
if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);


include_once("chunks/chunk_console_title.php");
//include("chunks/chunk_edit_console.php");
include_once("chunks/chunk_edit_groups.php");
?>
</body>
</html>
