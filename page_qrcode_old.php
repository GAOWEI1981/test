<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width"/>

<title>我的二维码</title>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />

</head>
<body class="BodyNoSpace">
<?
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();

include_once("../function/config.php");
include_once("../function/phpqrcode/phpqrcode.php");
//include_once("../function/Script.php");
//include_once("LocalConfig.php");


if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);


$FileName=basename($_SERVER['PHP_SELF']);
//echo dirname($_SERVER['PHP_SELF']);echo "<br>";
$operate=$_GET['operate'];
switch($operate)
{
	case "":
		break;
}
/*$BackUrl="index.php?owner={$_SESSION[WebOwner]}";
$Icon="../icon/icon_order.png";
include_once("chunks/chunk_back_bar.php");*/


$Output="";
if(strlen($_GET[owner])>0)
{
	$value = "http://".$_SERVER[HTTP_HOST].dirname($_SERVER[PHP_SELF])."/index.php?owner={$_GET[owner]}"; //二维码内容 
	//echo $value;
	$errorCorrectionLevel = 'L';//容错级别 
	$matrixPointSize = 8;//生成图片大小 
	//生成二维码图片 
	
	$logo = '../icon/icon_cart.png';//准备好的logo图片 
	$QR = 		"../pic/qrcode_{$_SESSION[adminID]}.png";//的原始二维码图 
	$Output=	"../pic/qrcode_{$_SESSION[adminID]}_output.png";
	QRcode::png($value, $QR, $errorCorrectionLevel, $matrixPointSize, 2); 
	if ($logo !== FALSE)
	{ 
		 $qr = imagecreatefromstring(file_get_contents($QR)); 
		 $logo = imagecreatefromstring(file_get_contents($logo)); 
		 $QR_width = imagesx($qr);//二维码图片宽度 
		 $QR_height = imagesy($qr);//二维码图片高度 
		 $logo_width = imagesx($logo);//logo图片宽度 
		 $logo_height = imagesy($logo);//logo图片高度 
		 $logo_qr_width = $QR_width / 5; 
		 $scale = $logo_width/$logo_qr_width; 
		 $logo_qr_height = $logo_height/$scale; 
		 $from_width = ($QR_width - $logo_qr_width) / 2; 
		 //重新组合图片并调整大小 
		 imagecopyresampled($qr, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height); 
	} 
	//输出图片 
	imagepng($qr, $Output); 
	$qrcode="<img width='100%' src='{$Output}'/>"; 
}
else
{
	echo "请先登陆！";
}
?>
<table width="100%" border="0" cellpadding="2">
  <tbody>
    <tr>
      <td><?echo $value;?>&nbsp;</td>
    </tr>
    <tr>
      <td><?echo $qrcode;?>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>

</body>
</html>
<script>

</script>


