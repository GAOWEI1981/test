<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>配置</title>
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
include_once("LocalConfig.php");

/*
$data->Brand = "三恒";
$data->AppName = "products_sanheng";
$fp = fopen("ConfigInfo.php", "w");
fwrite($fp, json_encode($data));
fclose($fp);*/
if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);

//可以访问的系统名称，防止访问不该访问的系统
if(!isset($_SESSION['SysName']))
{
	$config=file_get_contents("ConfigInfo.inf");
	$brand=GetXMLParam($config,"AppName");//获取本地设置
	$_SESSION['SysName']=$brand;//本系统名称
}
//**************

$FileName=basename($_SERVER["PHP_SELF"]);

$_SESSION[HomePage]=$FileName;
//必须要管理员才能登陆
if(!isset($_SESSION['adminID']))
{
	//Header("Location:Login.php");
	//$operate="login";
	print_r($_SESSION);
	return;
	Header("Location:login.php?err=请重新登录");
}
else//判断使用权限
{
	
	$UserInfo=GetItemA("signup_users","openid",$_SESSION['adminID']);
	
	$r=HavePopedom($_SESSION['SysName'],$UserInfo[user_type]);
	
	if($r[result]==false)
	{
		print_r($_SESSION);echo "<br>";
		print_r($r);echo "<br>";return;
		//Header("Location:login.php?err={$r[msg]}");
		return;
	}
	//print_r($UserInfo);echo "<br>";
	
}
include_once("chunks/chunk_console_title.php");

$PagePopedom="<a href='page_edit_popedom.php'>权限设置</a>";
$PageWebConsole="<a href='console_web.php'>网站后台</a>";
$PageUserEdit="<a href='page_edit_users.php'>用户管理</a>";
$PagePriceConfig="<a href='page_console_money.php'>价格</a>";
$PageExpressConfig="<a href='page_locations.php'>快递费设置</a>";
$PageGroupConfig="<a href='console_group.php'>产品类别</a>";
$PageGroupConfig="<a href='page_payments.php'>订单管理</a>";
$MsgConsole="<a href='../SysCustomerService/MainPage.php'>公众号客服</a>";
?>
<table width="100%" border="1" cellpadding="2" cellspacing="8">
<tr>
    <td align="center"><?echo $PagePopedom;?>&nbsp;</td>
    <td align="center"><?echo $PageWebConsole;?>&nbsp;</td>
    <td align="center"><?echo $PageUserEdit;?>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><?echo $PagePriceConfig;?>&nbsp;</td>
    <td align="center"><?echo $PageExpressConfig;?>&nbsp;</td>
    <td align="center"><?echo $PageGroupConfig;?>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><?echo $MsgConsole;?>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
</table>

</body>
</html>
<script>

</script>